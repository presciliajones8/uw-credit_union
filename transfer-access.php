<?php
require_once __DIR__ . '/transfer_service.php';
require_auth();

$senderId = current_user_id();
$reference = trim((string) ($_GET['reference'] ?? $_POST['reference'] ?? ''));
if ($reference === '') {
    redirect_to('transfer.php');
}

$conn = arffib_db();
$error = '';

function load_owned_demo_transfer(mysqli $conn, int $senderId, string $reference): ?array
{
    $stmt = $conn->prepare('SELECT t.*, s.fullName AS sender_name, s.balance AS sender_current_balance, a.status AS authorization_status, a.attempt_count, a.max_attempts, a.expires_at FROM internal_transfers t JOIN users s ON s.id = t.sender_user_id JOIN transfer_authorizations a ON a.transfer_id = t.id WHERE t.reference = ? AND t.sender_user_id = ? LIMIT 1');
    $stmt->bind_param('si', $reference, $senderId);
    $stmt->execute();
    $transfer = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $transfer ?: null;
}

$transfer = load_owned_demo_transfer($conn, $senderId, $reference);
if ($transfer === null) {
    http_response_code(404);
    exit('Transfer not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['authorize_transfer'])) {
    $submittedCode = trim((string) ($_POST['access_code'] ?? ''));
    if (!require_valid_transfer_csrf()) {
        $error = 'Your session could not be verified. Refresh the page and try again.';
    } elseif (!preg_match('/^\d{6}$/', $submittedCode)) {
        $error = 'Enter the authorization code.';
    } else {
        $conn->begin_transaction();
        try {
            $transferLock = $conn->prepare('SELECT * FROM internal_transfers WHERE reference = ? AND sender_user_id = ? FOR UPDATE');
            $transferLock->bind_param('si', $reference, $senderId);
            $transferLock->execute();
            $lockedTransfer = $transferLock->get_result()->fetch_assoc();
            $transferLock->close();

            if ($lockedTransfer === null || $lockedTransfer['status'] !== 'PENDING_AUTHORIZATION') {
                throw new RuntimeException('This transfer is no longer awaiting authorization.');
            }

            $authorizationLock = $conn->prepare('SELECT * FROM transfer_authorizations WHERE transfer_id = ? FOR UPDATE');
            $authorizationLock->bind_param('i', $lockedTransfer['id']);
            $authorizationLock->execute();
            $authorization = $authorizationLock->get_result()->fetch_assoc();
            $authorizationLock->close();

            if ($authorization === null || $authorization['status'] !== 'ACTIVE') {
                throw new RuntimeException('This authorization code is no longer active.');
            }

            if (strtotime((string) $authorization['expires_at']) <= time()) {
                $expireAuthorization = $conn->prepare("UPDATE transfer_authorizations SET status = 'EXPIRED' WHERE id = ?");
                $expireAuthorization->bind_param('i', $authorization['id']);
                $expireAuthorization->execute();
                $expireAuthorization->close();
                $expireTransfer = $conn->prepare("UPDATE internal_transfers SET status = 'EXPIRED' WHERE id = ?");
                $expireTransfer->bind_param('i', $lockedTransfer['id']);
                $expireTransfer->execute();
                $expireTransfer->close();
                $conn->commit();
                $error = 'This authorization code has expired. Start a new transfer preview.';
            } elseif (!password_verify($submittedCode, (string) $authorization['code_hash'])) {
                $attempts = (int) $authorization['attempt_count'] + 1;
                $newStatus = $attempts >= (int) $authorization['max_attempts'] ? 'LOCKED' : 'ACTIVE';
                $attemptUpdate = $conn->prepare('UPDATE transfer_authorizations SET attempt_count = ?, status = ? WHERE id = ?');
                $attemptUpdate->bind_param('isi', $attempts, $newStatus, $authorization['id']);
                $attemptUpdate->execute();
                $attemptUpdate->close();
                if ($newStatus === 'LOCKED') {
                    $failTransfer = $conn->prepare("UPDATE internal_transfers SET status = 'FAILED' WHERE id = ?");
                    $failTransfer->bind_param('i', $lockedTransfer['id']);
                    $failTransfer->execute();
                    $failTransfer->close();
                }
                $conn->commit();
                $error = $newStatus === 'LOCKED' ? 'Too many incorrect attempts. This demo transfer has been locked.' : 'The authorization code is incorrect.';
            } else {
                $beneficiaryType = (string) $lockedTransfer['beneficiary_type'];

                if ($beneficiaryType === 'LOCAL_DEMO') {
                    // Local demo transfer: debit sender, credit recipient
                    $firstId = min((int) $lockedTransfer['sender_user_id'], (int) $lockedTransfer['recipient_user_id']);
                    $secondId = max((int) $lockedTransfer['sender_user_id'], (int) $lockedTransfer['recipient_user_id']);
                    $accountLock = $conn->prepare('SELECT id, fullName, balance FROM users WHERE id IN (?, ?) ORDER BY id FOR UPDATE');
                    $accountLock->bind_param('ii', $firstId, $secondId);
                    $accountLock->execute();
                    $accounts = $accountLock->get_result()->fetch_all(MYSQLI_ASSOC);
                    $accountLock->close();

                    $accountsById = [];
                    foreach ($accounts as $account) {
                        $accountsById[(int) $account['id']] = $account;
                    }
                    $senderAccount = $accountsById[(int) $lockedTransfer['sender_user_id']] ?? null;
                    $recipientAccount = $accountsById[(int) $lockedTransfer['recipient_user_id']] ?? null;
                    if ($senderAccount === null || $recipientAccount === null) {
                        throw new RuntimeException('A transfer account could not be locked.');
                    }

                    $amount = (string) $lockedTransfer['total_debit'];
                    $debit = $conn->prepare('UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?');
                    $debit->bind_param('sis', $amount, $lockedTransfer['sender_user_id'], $amount);
                    $debit->execute();
                    $debited = $debit->affected_rows === 1;
                    $debit->close();
                    if (!$debited) {
                        throw new RuntimeException('Insufficient available balance to complete this transfer.');
                    }

                    $credit = $conn->prepare('UPDATE users SET balance = balance + ? WHERE id = ?');
                    $credit->bind_param('si', $amount, $lockedTransfer['recipient_user_id']);
                    $credit->execute();
                    $credit->close();

                    $balanceRead = $conn->prepare('SELECT id, balance FROM users WHERE id IN (?, ?)');
                    $balanceRead->bind_param('ii', $lockedTransfer['sender_user_id'], $lockedTransfer['recipient_user_id']);
                    $balanceRead->execute();
                    $updatedAccounts = $balanceRead->get_result()->fetch_all(MYSQLI_ASSOC);
                    $balanceRead->close();
                    $updatedBalances = [];
                    foreach ($updatedAccounts as $account) {
                        $updatedBalances[(int) $account['id']] = (string) $account['balance'];
                    }

                    $senderDetails = 'Demo transfer ' . $reference . ' to ' . $recipientAccount['fullName'] . ($lockedTransfer['description'] !== '' ? ' — ' . $lockedTransfer['description'] : '');
                    $recipientDetails = 'Demo transfer ' . $reference . ' from ' . $senderAccount['fullName'] . ($lockedTransfer['description'] !== '' ? ' — ' . $lockedTransfer['description'] : '');
                    $senderTransaction = $conn->prepare("INSERT INTO transactions (user_id, type, amount, details, transaction_date) VALUES (?, 'transfer', ?, ?, NOW())");
                    $senderTransaction->bind_param('iss', $lockedTransfer['sender_user_id'], $amount, $senderDetails);
                    $senderTransaction->execute();
                    $senderTransaction->close();
                    $recipientTransaction = $conn->prepare("INSERT INTO transactions (user_id, type, amount, details, transaction_date) VALUES (?, 'deposit', ?, ?, NOW())");
                    $recipientTransaction->bind_param('iss', $lockedTransfer['recipient_user_id'], $amount, $recipientDetails);
                    $recipientTransaction->execute();
                    $recipientTransaction->close();

                    $debitDirection = 'DEBIT';
                    $creditDirection = 'CREDIT';
                    $senderLedger = $conn->prepare('INSERT INTO internal_ledger_entries (transfer_id, user_id, direction, amount, balance_after, description) VALUES (?, ?, ?, ?, ?, ?)');
                    $senderBalanceAfter = $updatedBalances[(int) $lockedTransfer['sender_user_id']];
                    $senderLedger->bind_param('iissss', $lockedTransfer['id'], $lockedTransfer['sender_user_id'], $debitDirection, $amount, $senderBalanceAfter, $senderDetails);
                    $senderLedger->execute();
                    $senderLedger->close();
                    $recipientLedger = $conn->prepare('INSERT INTO internal_ledger_entries (transfer_id, user_id, direction, amount, balance_after, description) VALUES (?, ?, ?, ?, ?, ?)');
                    $recipientBalanceAfter = $updatedBalances[(int) $lockedTransfer['recipient_user_id']];
                    $recipientLedger->bind_param('iissss', $lockedTransfer['id'], $lockedTransfer['recipient_user_id'], $creditDirection, $amount, $recipientBalanceAfter, $recipientDetails);
                    $recipientLedger->execute();
                    $recipientLedger->close();
                } else {
                    // External simulation: debit sender only, no credit to local account
                    $accountLock = $conn->prepare('SELECT id, fullName, balance FROM users WHERE id = ? FOR UPDATE');
                    $accountLock->bind_param('i', $lockedTransfer['sender_user_id']);
                    $accountLock->execute();
                    $senderAccount = $accountLock->get_result()->fetch_assoc();
                    $accountLock->close();

                    if ($senderAccount === null) {
                        throw new RuntimeException('Sender account could not be locked.');
                    }

                    $amount = (string) $lockedTransfer['total_debit'];
                    $debit = $conn->prepare('UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?');
                    $debit->bind_param('sis', $amount, $lockedTransfer['sender_user_id'], $amount);
                    $debit->execute();
                    $debited = $debit->affected_rows === 1;
                    $debit->close();
                    if (!$debited) {
                        throw new RuntimeException('Insufficient available balance to complete this transfer.');
                    }

                    $balanceRead = $conn->prepare('SELECT id, balance FROM users WHERE id = ?');
                    $balanceRead->bind_param('i', $lockedTransfer['sender_user_id']);
                    $balanceRead->execute();
                    $updatedAccount = $balanceRead->get_result()->fetch_assoc();
                    $balanceRead->close();
                    $senderBalanceAfter = (string) $updatedAccount['balance'];

                    $senderDetails = 'External simulation transfer ' . $reference . ' to ' . $lockedTransfer['beneficiary_name'] . ' at ' . $lockedTransfer['bank_name'] . ($lockedTransfer['description'] !== '' ? ' — ' . $lockedTransfer['description'] : '');
                    $senderTransaction = $conn->prepare("INSERT INTO transactions (user_id, type, amount, details, transaction_date) VALUES (?, 'transfer', ?, ?, NOW())");
                    $senderTransaction->bind_param('iss', $lockedTransfer['sender_user_id'], $amount, $senderDetails);
                    $senderTransaction->execute();
                    $senderTransaction->close();

                    $debitDirection = 'DEBIT';
                    $senderLedger = $conn->prepare('INSERT INTO internal_ledger_entries (transfer_id, user_id, direction, amount, balance_after, description) VALUES (?, ?, ?, ?, ?, ?)');
                    $senderLedger->bind_param('iissss', $lockedTransfer['id'], $lockedTransfer['sender_user_id'], $debitDirection, $amount, $senderBalanceAfter, $senderDetails);
                    $senderLedger->execute();
                    $senderLedger->close();
                }

                $markTransfer = $conn->prepare("UPDATE internal_transfers SET status = 'COMPLETED', completed_at = NOW() WHERE id = ? AND status = 'PENDING_AUTHORIZATION'");
                $markTransfer->bind_param('i', $lockedTransfer['id']);
                $markTransfer->execute();
                if ($markTransfer->affected_rows !== 1) {
                    throw new RuntimeException('This transfer was already processed.');
                }
                $markTransfer->close();
                $markAuthorization = $conn->prepare("UPDATE transfer_authorizations SET status = 'USED', used_at = NOW() WHERE id = ? AND status = 'ACTIVE'");
                $markAuthorization->bind_param('i', $authorization['id']);
                $markAuthorization->execute();
                if ($markAuthorization->affected_rows !== 1) {
                    throw new RuntimeException('This authorization code was already used.');
                }
                $markAuthorization->close();
                $conn->commit();
                redirect_to('transfer-success.php?reference=' . rawurlencode($reference));
            }
        } catch (Throwable $exception) {
            $conn->rollback();
            $error = $exception->getMessage() === 'Insufficient available balance to complete this transfer.' ? $exception->getMessage() : 'The transfer could not be completed. No balance changes were made.';
        }
    }
    $transfer = load_owned_demo_transfer($conn, $senderId, $reference);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorize Demo Transfer | UW CREDIT UNION</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = '4b87e7632827dd8d220f5013a1d7b8ba80b6996e';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript>Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript>

    <main class="mx-auto max-w-xl px-4 py-10">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="bg-gradient-to-r from-sky-700 to-sky-900 px-6 py-8 text-center text-white">
                <h1 class="text-3xl font-bold">Transfer Preview</h1>
                <p class="mt-2 text-sky-100">Authorization is required before this internal demo ledger transfer
                    executes.</p>
            </header>
            <div class="space-y-6 p-6">
                <?php if ($error !== ''): ?>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                        <?php echo htmlspecialchars($error); ?></div><?php endif; ?>
                <dl class="space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt>Reference</dt>
                        <dd class="font-semibold"><?php echo htmlspecialchars($transfer['reference']); ?></dd>
                    </div>
                    
                    <div class="flex justify-between gap-4">
                        <dt>Beneficiary</dt>
                        <dd class="font-semibold"><?php echo htmlspecialchars($transfer['beneficiary_name']); ?></dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Account</dt>
                        <dd class="font-semibold"><?php echo htmlspecialchars($transfer['beneficiary_account']); ?></dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Bank</dt>
                        <dd class="font-semibold"><?php echo htmlspecialchars($transfer['bank_name']); ?></dd>
                    </div><?php if ($transfer['country'] !== ''): ?>
                        <div class="flex justify-between gap-4">
                            <dt>Country</dt>
                            <dd class="font-semibold"><?php echo htmlspecialchars($transfer['country']); ?></dd>
                        </div><?php endif; ?><?php if ($transfer['swift_bic'] !== ''): ?>
                        <div class="flex justify-between gap-4">
                            <dt>SWIFT/BIC</dt>
                            <dd class="font-semibold"><?php echo htmlspecialchars($transfer['swift_bic']); ?></dd>
                        </div><?php endif; ?>
                    <div class="flex justify-between gap-4">
                        <dt>Transfer amount</dt>
                        <dd class="font-semibold"><?php echo htmlspecialchars($transfer['currency']); ?>
                            <?php echo htmlspecialchars(number_format((float) $transfer['amount'], 2)); ?></dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Fee</dt>
                        <dd class="font-semibold"><?php echo htmlspecialchars($transfer['currency']); ?>
                            <?php echo htmlspecialchars(number_format((float) $transfer['fee'], 2)); ?></dd>
                    </div>
                    <div class="flex justify-between gap-4 border-t border-slate-200 pt-3">
                        <dt class="font-semibold">Total debit</dt>
                        <dd class="font-bold"><?php echo htmlspecialchars($transfer['currency']); ?>
                            <?php echo htmlspecialchars(number_format((float) $transfer['total_debit'], 2)); ?></dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Current available balance</dt>
                        <dd class="font-semibold"><?php echo htmlspecialchars($transfer['currency']); ?>
                            <?php echo htmlspecialchars(number_format((float) $transfer['sender_current_balance'], 2)); ?>
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt>Estimated remaining balance</dt>
                        <dd class="font-semibold"><?php echo htmlspecialchars($transfer['currency']); ?>
                            <?php echo htmlspecialchars(number_format((float) $transfer['sender_current_balance'] - (float) $transfer['total_debit'], 2)); ?>
                        </dd>
                    </div>
                </dl>
                <p class="text-sm text-slate-600">This preview does not change any account balance. An administrator
                    must issue the access code. it is never sent as SMS or email.<br> Contact support below</p>
                <?php if ($transfer['status'] === 'PENDING_AUTHORIZATION' && $transfer['authorization_status'] === 'ACTIVE'): ?>
                    <form method="post"
                        action="<?php echo htmlspecialchars(app_url('transfer-access.php?reference=' . rawurlencode($reference))); ?>"
                        class="space-y-4"><input type="hidden" name="csrf_token"
                            value="<?php echo htmlspecialchars(transfer_csrf_token()); ?>"><input type="hidden"
                            name="reference" value="<?php echo htmlspecialchars($reference); ?>">
                        <div><label class="mb-2 block text-sm font-medium" for="access_code">Authorization
                                code</label><input id="access_code" name="access_code" inputmode="numeric" pattern="\d{6}"
                                maxlength="6" required class="w-full rounded-lg border border-slate-300 px-4 py-3"></div>
                        <button name="authorize_transfer" value="1"
                            class="w-full rounded-lg bg-sky-700 px-4 py-3 font-semibold text-white hover:bg-sky-800"><?php echo $transfer['beneficiary_type'] === 'EXTERNAL_SIMULATION' ? 'Authorize Payment' : 'Complete internal demo transfer'; ?></button>
                    </form><?php else: ?>
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">This transfer is
                        <?php echo htmlspecialchars(strtolower(str_replace('_', ' ', $transfer['status']))); ?> and cannot
                        be authorized.</div><?php endif; ?>
                <a class="block text-center text-sm font-semibold text-slate-600 hover:text-slate-900"
                    href="<?php echo htmlspecialchars(app_url('transfer.php')); ?>">Start a new transfer</a>
            </div>
        </section>
    </main>
</body>

</html>
<?php
require_once __DIR__ . '/transfer_service.php';
require_auth();

$senderId = current_user_id();
$conn = arffib_db();
$senderStmt = $conn->prepare('SELECT id, fullName, balance, currency FROM users WHERE id = ? LIMIT 1');
$senderStmt->bind_param('i', $senderId);
$senderStmt->execute();
$sender = $senderStmt->get_result()->fetch_assoc();
$senderStmt->close();

if ($sender === null) {
    redirect_to('login.php');
}

$currency = preg_match('/^[A-Z]{3}$/', (string) ($sender['currency'] ?? '')) ? $sender['currency'] : 'USD';
$error = '';
$form = ['amount' => '', 'beneficiary_name' => '', 'beneficiary_account' => '', 'bank_name' => '', 'country' => '', 'swift_bic' => '', 'description' => ''];

$bankDirectory = require __DIR__ . '/bank_directory.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['preview_transfer'])) {
    $form['amount'] = trim((string) ($_POST['amount'] ?? ''));
    $form['beneficiary_name'] = trim((string) ($_POST['beneficiary_name'] ?? ''));
    $form['beneficiary_account'] = trim((string) ($_POST['beneficiary_account'] ?? ''));
    $form['bank_name'] = trim((string) ($_POST['bank_name'] ?? ''));
    $form['country'] = trim((string) ($_POST['country'] ?? ''));
    $form['swift_bic'] = trim((string) ($_POST['swift_bic'] ?? ''));
    $form['description'] = trim((string) ($_POST['description'] ?? ''));

    $amount = valid_demo_transfer_amount($form['amount']);
    if (!require_valid_transfer_csrf()) {
        $error = 'Your session could not be verified. Refresh the page and try again.';
    } elseif ($amount === null) {
        $error = 'Enter a transfer amount greater than zero with no more than two decimal places.';
    } elseif ($form['beneficiary_name'] === '' || $form['beneficiary_account'] === '') {
        $error = 'Enter the beneficiary name and account number.';
    } elseif ($form['bank_name'] === '') {
        $error = 'Select a beneficiary bank from the directory.';
    } elseif (strlen($form['description']) > 500) {
        $error = 'The transfer description must be 500 characters or fewer.';
    } elseif ((float) $sender['balance'] < (float) $amount) {
        $error = 'Insufficient available balance for this transfer.';
    } else {
        // Check if beneficiary is a local demo account
        $recipientStmt = $conn->prepare('SELECT id, fullName FROM users WHERE idNumber = ? LIMIT 1');
        $recipientStmt->bind_param('s', $form['beneficiary_account']);
        $recipientStmt->execute();
        $recipient = $recipientStmt->get_result()->fetch_assoc();
        $recipientStmt->close();

        $beneficiaryType = 'EXTERNAL_SIMULATION';
        $recipientId = null;
        $recipientName = $form['beneficiary_name'];

        if ($recipient !== null) {
            if ((int) $recipient['id'] === (int) $senderId) {
                $error = 'You cannot transfer simulated funds to your own account.';
            } else {
                $beneficiaryType = 'LOCAL_DEMO';
                $recipientId = (int) $recipient['id'];
                $recipientName = (string) $recipient['fullName'];
            }
        }

        if ($error === '') {
            $reference = demo_transfer_reference();
            $fee = '0.00';
            $status = 'PENDING_AUTHORIZATION';
            $code = (string) random_int(100000, 999999);
            $codeHash = password_hash($code, PASSWORD_DEFAULT);
            $expiresAt = date('Y-m-d H:i:s', time() + DEMO_TRANSFER_AUTHORIZATION_TTL_SECONDS);

            $conn->begin_transaction();
            try {
                // Use single query with bind_param that can handle NULL
                $insertTransfer = $conn->prepare('INSERT INTO internal_transfers (reference, sender_user_id, recipient_user_id, beneficiary_name, beneficiary_account, bank_name, country, swift_bic, beneficiary_type, currency, amount, fee, total_debit, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                
                // 15 parameters: reference(s), senderId(i), recipientId(i or null), recipientName(s), beneficiary_account(s), bank_name(s), country(s), swift_bic(s), beneficiary_type(s), currency(s), amount(s), fee(s), total_debit(s), description(s), status(s)
                // MySQLi handles NULL for integer types when variable is NULL
                $insertTransfer->bind_param('siissssssssssss', $reference, $senderId, $recipientId, $recipientName, $form['beneficiary_account'], $form['bank_name'], $form['country'], $form['swift_bic'], $beneficiaryType, $currency, $amount, $fee, $amount, $form['description'], $status);
                
                $insertTransfer->execute();
                $transferId = $conn->insert_id;
                $insertTransfer->close();

                $insertAuthorization = $conn->prepare('INSERT INTO transfer_authorizations (transfer_id, code_hash, status, max_attempts, expires_at) VALUES (?, ?, \'ACTIVE\', ?, ?)');
                $maxAttempts = DEMO_TRANSFER_MAX_AUTHORIZATION_ATTEMPTS;
                $insertAuthorization->bind_param('isis', $transferId, $codeHash, $maxAttempts, $expiresAt);
                $insertAuthorization->execute();
                $insertAuthorization->close();
                $conn->commit();

                // The generated code is never sent to the customer browser. Use the local development admin page to issue a test code.
                redirect_to('transfer-access.php?reference=' . rawurlencode($reference));
            } catch (Throwable $exception) {
                $conn->rollback();
                $error = 'The transfer preview could not be created. Please try again. Error: ' . $exception->getMessage();
                error_log('Transfer preview error: ' . $exception->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Funds | UW CREDIT UNION</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
<main class="mx-auto max-w-3xl px-4 py-10">
    <div class="mb-6 flex items-center justify-between"><a href="<?php echo htmlspecialchars(app_url('dash.php')); ?>" class="text-sm font-semibold text-sky-700 hover:text-sky-900">← Dashboard</a></div>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <header class="bg-gradient-to-r from-sky-700 to-sky-900 px-6 py-8 text-white"><h1 class="text-3xl font-bold">Transfer Funds</h1></header>
        <div class="p-6 sm:p-8">
            <?php if ($error !== ''): ?><div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <div class="mb-6 rounded-xl border border-sky-100 bg-sky-50 p-4"><p class="text-sm text-slate-600">Available balance</p><p class="mt-1 text-2xl font-bold"><?php echo htmlspecialchars($currency); ?> <?php echo htmlspecialchars(number_format((float) $sender['balance'], 2)); ?></p></div>
            <form method="post" action="<?php echo htmlspecialchars(app_url('transfer.php')); ?>" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(transfer_csrf_token()); ?>">
                <div><label class="mb-2 block text-sm font-medium" for="amount">Transfer amount (<?php echo htmlspecialchars($currency); ?>)</label><input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="amount" name="amount" inputmode="decimal" pattern="\d+(\.\d{1,2})?" value="<?php echo htmlspecialchars($form['amount']); ?>" required></div>
                <div class="grid gap-5 sm:grid-cols-2"><div><label class="mb-2 block text-sm font-medium" for="beneficiary_name">Beneficiary name</label><input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="beneficiary_name" name="beneficiary_name" value="<?php echo htmlspecialchars($form['beneficiary_name']); ?>" required></div><div><label class="mb-2 block text-sm font-medium" for="beneficiary_account">Beneficiary account number</label><input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="beneficiary_account" name="beneficiary_account" value="<?php echo htmlspecialchars($form['beneficiary_account']); ?>" required></div></div>
                <div><label class="mb-2 block text-sm font-medium" for="bank_search">Beneficiary bank</label><input class="w-full rounded-lg border border-slate-300 px-4 py-3" id="bank_search" name="bank_search" placeholder="Search banks by name or country" autocomplete="off"><div id="bank_results" class="mt-2 hidden rounded-lg border border-slate-200 bg-white shadow-sm max-h-48 overflow-y-auto"></div><input type="hidden" id="bank_name" name="bank_name" value="<?php echo htmlspecialchars($form['bank_name']); ?>"><input type="hidden" id="country" name="country" value="<?php echo htmlspecialchars($form['country']); ?>"><input type="hidden" id="swift_bic" name="swift_bic" value="<?php echo htmlspecialchars($form['swift_bic']); ?>"><div id="selected_bank" class="mt-2 rounded-lg bg-sky-50 p-3 text-sm <?php echo $form['bank_name'] === '' ? 'hidden' : ''; ?>"><p class="font-semibold text-sky-900"><?php echo htmlspecialchars($form['bank_name']); ?></p><p class="text-sky-700"><?php echo htmlspecialchars($form['country']); ?><?php echo $form['swift_bic'] !== '' ? ' • SWIFT: ' . htmlspecialchars($form['swift_bic']) : ''; ?></p></div></div>
                <div><label class="mb-2 block text-sm font-medium" for="description">Description / reference</label><textarea class="w-full rounded-lg border border-slate-300 px-4 py-3" id="description" name="description" maxlength="500" rows="3"><?php echo htmlspecialchars($form['description']); ?></textarea></div>
                <button class="w-full rounded-lg bg-sky-700 px-4 py-3 font-semibold text-white hover:bg-sky-800" name="preview_transfer" value="1" type="submit">Preview transfer</button>
            </form>
        </div>
    </section>
</main>
<script>
const bankDirectory = <?php echo json_encode($bankDirectory); ?>;
const bankSearch = document.getElementById('bank_search');
const bankResults = document.getElementById('bank_results');
const bankNameInput = document.getElementById('bank_name');
const countryInput = document.getElementById('country');
const swiftBicInput = document.getElementById('swift_bic');
const selectedBankDiv = document.getElementById('selected_bank');

bankSearch.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    if (query.length < 2) {
        bankResults.classList.add('hidden');
        return;
    }
    
    const matches = bankDirectory.filter(bank => 
        bank.name.toLowerCase().includes(query) || 
        bank.country.toLowerCase().includes(query)
    ).slice(0, 10);
    
    if (matches.length === 0) {
        bankResults.classList.add('hidden');
        return;
    }
    
    bankResults.innerHTML = matches.map(bank => 
        '<div class="cursor-pointer border-b border-slate-100 px-4 py-3 hover:bg-sky-50" data-name="' + encodeURIComponent(bank.name) + '" data-country="' + encodeURIComponent(bank.country) + '" data-swift="' + encodeURIComponent(bank.swift_bic || '') + '">' +
        '<p class="font-semibold text-slate-900">' + bank.name + '</p>' +
        '<p class="text-sm text-slate-600">' + bank.country + (bank.swift_bic ? ' • SWIFT: ' + bank.swift_bic : '') + '</p>' +
        '</div>'
    ).join('');
    
    bankResults.classList.remove('hidden');
});

bankResults.addEventListener('click', function(e) {
    const item = e.target.closest('[data-name]');
    if (!item) return;
    
    const name = decodeURIComponent(item.dataset.name);
    const country = decodeURIComponent(item.dataset.country);
    const swift = decodeURIComponent(item.dataset.swift);
    
    bankNameInput.value = name;
    countryInput.value = country;
    swiftBicInput.value = swift;
    bankSearch.value = name;
    bankResults.classList.add('hidden');
    
    selectedBankDiv.querySelector('p:first-child').textContent = name;
    selectedBankDiv.querySelector('p:last-child').textContent = country + (swift ? ' • SWIFT: ' + swift : '');
    selectedBankDiv.classList.remove('hidden');
});

document.addEventListener('click', function(e) {
    if (!bankSearch.contains(e.target) && !bankResults.contains(e.target)) {
        bankResults.classList.add('hidden');
    }
});
</script>
</body>
</html>

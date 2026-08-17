<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/btc_deposit_config.php';
require_auth();

$userId = current_user_id();
if ($userId === null) {
    redirect_to('login.php');
}

$receivingAddress = configured_btc_receiving_address();
$depositError = '';
$submittedDeposit = null;

if (!isset($_SESSION['btc_deposit_csrf'])) {
    $_SESSION['btc_deposit_csrf'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION['btc_deposit_idempotency_key'])) {
    $_SESSION['btc_deposit_idempotency_key'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_deposit_request'])) {
    $csrfToken = (string) ($_POST['csrf_token'] ?? '');
    $idempotencyKey = (string) ($_POST['idempotency_key'] ?? '');

    if ($receivingAddress === null) {
        $depositError = 'Bitcoin deposits are not available until a receiving address is configured securely.';
    } elseif (!hash_equals($_SESSION['btc_deposit_csrf'], $csrfToken)) {
        $depositError = 'Your session could not be verified. Refresh the page and try again.';
    } elseif (!hash_equals($_SESSION['btc_deposit_idempotency_key'], $idempotencyKey)) {
        $depositError = 'This deposit request has already been submitted or is no longer valid. Refresh the page to start again.';
    } else {
        $declaredAmount = trim((string) ($_POST['declared_amount'] ?? ''));
        $amount = null;

        if ($declaredAmount !== '') {
            if (preg_match('/^\d+(?:\.\d{1,8})?$/', $declaredAmount) !== 1 || (float) $declaredAmount <= 0) {
                $depositError = 'Enter a valid declared BTC amount with up to 8 decimal places, or leave it blank.';
            } else {
                $amount = number_format((float) $declaredAmount, 8, '.', '');
            }
        }

        if ($depositError === '') {
            $conn = arffib_db();
            $reference = 'BTC-' . strtoupper(bin2hex(random_bytes(6)));
            $status = 'PENDING_VERIFICATION';
            $method = 'BTC';

            try {
                $stmt = $conn->prepare(
                    'INSERT INTO deposit_requests (user_id, reference, method, receiving_address, declared_amount, status, idempotency_key) VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->bind_param('issssss', $userId, $reference, $method, $receivingAddress, $amount, $status, $idempotencyKey);
                $stmt->execute();
                $stmt->close();

                unset($_SESSION['btc_deposit_idempotency_key']);
                redirect_to('deposit.php?submitted=' . rawurlencode($reference));
            } catch (mysqli_sql_exception $exception) {
                // A duplicate idempotency key means a repeated submission; never create a second request.
                $existing = $conn->prepare('SELECT reference, method, receiving_address, declared_amount, status FROM deposit_requests WHERE user_id = ? AND idempotency_key = ? LIMIT 1');
                $existing->bind_param('is', $userId, $idempotencyKey);
                $existing->execute();
                $result = $existing->get_result();
                $submittedDeposit = $result ? $result->fetch_assoc() : null;
                $existing->close();

                if ($submittedDeposit === null) {
                    $depositError = 'We could not submit the deposit notification. Please try again later.';
                }
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['submitted'])) {
    $reference = trim((string) $_GET['submitted']);
    if ($reference !== '') {
        $conn = arffib_db();
        $stmt = $conn->prepare('SELECT reference, method, receiving_address, declared_amount, status FROM deposit_requests WHERE user_id = ? AND reference = ? LIMIT 1');
        $stmt->bind_param('is', $userId, $reference);
        $stmt->execute();
        $result = $stmt->get_result();
        $submittedDeposit = $result ? $result->fetch_assoc() : null;
        $stmt->close();
    }
}

$bitcoinUri = $receivingAddress === null ? '' : 'bitcoin:' . $receivingAddress;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin Deposit | UW CREDIT UNION</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
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
<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="mx-auto max-w-3xl px-4 py-10 sm:py-14">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <header class="bg-gradient-to-r from-sky-700 to-sky-900 px-6 py-8 text-center text-white">
                <h1 class="text-3xl font-bold">Deposit Bitcoin</h1>
                <p class="mt-2 text-sky-100">Send Bitcoin only to the configured receiving address below.</p>
            </header>

            <div class="space-y-6 p-6 sm:p-8">
                <?php if ($depositError !== ''): ?>
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert"><?php echo htmlspecialchars($depositError); ?></div>
                <?php endif; ?>

                <?php if ($submittedDeposit !== null): ?>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-5" role="status">
                        <h2 class="text-lg font-semibold text-amber-900">Deposit Notification Submitted</h2>
                        <p class="mt-2 text-sm text-amber-800">Your deposit notification is pending verification. Bitcoin has not been confirmed or credited to your account.</p>
                        <dl class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
                            <div><dt class="text-amber-700">Reference</dt><dd class="font-semibold text-amber-950"><?php echo htmlspecialchars($submittedDeposit['reference']); ?></dd></div>
                            <div><dt class="text-amber-700">Status</dt><dd class="font-semibold text-amber-950"><?php echo htmlspecialchars(str_replace('_', ' ', $submittedDeposit['status'])); ?></dd></div>
                        </dl>
                        <p class="mt-4 text-sm text-amber-900">Please contact Customer Care using the chat icon below and provide this deposit reference.</p>
                    </div>
                <?php elseif ($receivingAddress === null): ?>
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800" role="alert">Bitcoin deposits are temporarily unavailable because no verified receiving address has been configured.</div>
                <?php else: ?>
                    <p class="text-center text-sm text-slate-600">After sending Bitcoin, submit a notification for independent verification. This action never credits your balance.</p>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="btc-address">BTC wallet address</label>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <input id="btc-address" class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 font-mono text-sm text-slate-900" type="text" readonly value="<?php echo htmlspecialchars($receivingAddress); ?>">
                            <button id="copy-address" type="button" class="rounded-lg border border-sky-700 px-4 py-3 font-semibold text-sky-800 hover:bg-sky-50">Copy Address</button>
                        </div>
                    </div>

                    <div class="flex justify-center py-2">
                        <div id="btc-qr" class="rounded-xl border border-slate-200 bg-white p-4" data-bitcoin-uri="<?php echo htmlspecialchars($bitcoinUri); ?>" aria-label="Bitcoin deposit QR code"></div>
                    </div>

                    <form method="post" action="<?php echo htmlspecialchars(app_url('deposit.php')); ?>" class="space-y-5">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['btc_deposit_csrf']); ?>">
                        <input type="hidden" name="idempotency_key" value="<?php echo htmlspecialchars($_SESSION['btc_deposit_idempotency_key']); ?>">
                      
                        <button name="submit_deposit_request" value="1" type="submit" class="w-full rounded-lg bg-sky-700 px-4 py-3 font-semibold text-white hover:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">I Have Completed the Deposit</button>
                    </form>

                    <p class="text-center text-sm text-slate-600">Need help? Use the chat icon below to contact Customer Care.</p>
                <?php endif; ?>

                <a href="<?php echo htmlspecialchars(app_url('dash.php')); ?>" class="block text-center text-sm font-semibold text-slate-600 hover:text-slate-900">Back to dashboard</a>
            </div>
        </section>
    </main>

    <?php if ($receivingAddress !== null && $submittedDeposit === null): ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" referrerpolicy="no-referrer"></script>
        <script>
            const addressInput = document.getElementById('btc-address');
            const copyButton = document.getElementById('copy-address');
            const qrTarget = document.getElementById('btc-qr');

            new QRCode(qrTarget, { text: qrTarget.dataset.bitcoinUri, width: 224, height: 224, correctLevel: QRCode.CorrectLevel.M });

            copyButton.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(addressInput.value);
                    copyButton.textContent = 'Copied ✓';
                    window.setTimeout(() => { copyButton.textContent = 'Copy Address'; }, 1800);
                } catch (error) {
                    copyButton.textContent = 'Copy unavailable';
                    window.setTimeout(() => { copyButton.textContent = 'Copy Address'; }, 1800);
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>

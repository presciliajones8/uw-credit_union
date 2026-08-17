<?php
require_once __DIR__ . '/transfer_service.php';
require_auth();

$isLocalRequest = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
$configuredAdminToken = (string) getenv('DEMO_TRANSFER_ADMIN_TOKEN');
if (!$isLocalRequest || $configuredAdminToken === '') {
    http_response_code(403);
    exit('The local development transfer console is unavailable.');
}

$adminAuthorized = (int) ($_SESSION['demo_transfer_admin_until'] ?? 0) >= time();
$error = '';
$issuedCode = null;
$issuedReference = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unlock_console'])) {
    $submittedToken = (string) ($_POST['admin_token'] ?? '');
    if (hash_equals($configuredAdminToken, $submittedToken)) {
        $_SESSION['demo_transfer_admin_until'] = time() + 900;
        $adminAuthorized = true;
    } else {
        $error = 'Invalid development administrator token.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issue_code']) && $adminAuthorized) {
    if (!require_valid_transfer_csrf()) {
        $error = 'Your session could not be verified. Refresh the page and try again.';
    } else {
        $reference = trim((string) ($_POST['reference'] ?? ''));
        $conn = arffib_db();
        $conn->begin_transaction();
        try {
            $transferStmt = $conn->prepare("SELECT id FROM internal_transfers WHERE reference = ? AND status = 'PENDING_AUTHORIZATION' FOR UPDATE");
            $transferStmt->bind_param('s', $reference);
            $transferStmt->execute();
            $transfer = $transferStmt->get_result()->fetch_assoc();
            $transferStmt->close();
            if ($transfer === null) {
                throw new RuntimeException('The transfer is not awaiting authorization.');
            }

            $code = (string) random_int(100000, 999999);
            $codeHash = password_hash($code, PASSWORD_DEFAULT);
            $expiresAt = date('Y-m-d H:i:s', time() + DEMO_TRANSFER_AUTHORIZATION_TTL_SECONDS);
            $activeStatus = 'ACTIVE';
            $maxAttempts = DEMO_TRANSFER_MAX_AUTHORIZATION_ATTEMPTS;
            $authorizationStmt = $conn->prepare('UPDATE transfer_authorizations SET code_hash = ?, status = ?, attempt_count = 0, max_attempts = ?, expires_at = ?, used_at = NULL WHERE transfer_id = ?');
            $authorizationStmt->bind_param('ssisi', $codeHash, $activeStatus, $maxAttempts, $expiresAt, $transfer['id']);
            $authorizationStmt->execute();
            if ($authorizationStmt->affected_rows !== 1) {
                throw new RuntimeException('The authorization record could not be updated.');
            }
            $authorizationStmt->close();
            $conn->commit();
            $issuedCode = $code;
            $issuedReference = $reference;
        } catch (Throwable $exception) {
            $conn->rollback();
            $error = 'Unable to issue a development code: ' . $exception->getMessage();
        }
    }
}

$transfers = [];
if ($adminAuthorized) {
    $conn = arffib_db();
    $result = $conn->query("SELECT t.reference, t.beneficiary_name, t.amount, t.currency, t.status, t.created_at, a.status AS authorization_status, a.expires_at FROM internal_transfers t JOIN transfer_authorizations a ON a.transfer_id = t.id WHERE t.status = 'PENDING_AUTHORIZATION' ORDER BY t.created_at DESC LIMIT 50");
    if ($result) {
        $transfers = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Demo Transfer Console</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="min-h-screen bg-slate-100 text-slate-900"><main class="mx-auto max-w-5xl px-4 py-10"><section class="rounded-2xl border border-amber-300 bg-white p-6 shadow-sm"><h1 class="text-2xl font-bold">Development-only transfer authorization console</h1><p class="mt-2 text-sm text-slate-600">Local demo use only. Codes are shown once after issuance and are stored as hashes.</p><?php if ($error !== ''): ?><div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if (!$adminAuthorized): ?><form method="post" class="mt-6 max-w-md space-y-3"><label class="block text-sm font-medium" for="admin_token">Development administrator token</label><input id="admin_token" name="admin_token" type="password" required class="w-full rounded-lg border border-slate-300 px-3 py-2"><button name="unlock_console" value="1" class="rounded-lg bg-sky-700 px-4 py-2 font-semibold text-white">Unlock console</button></form><?php else: ?>
<?php if ($issuedCode !== null): ?><div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 p-4"><p class="font-semibold text-emerald-900">Development code issued for <?php echo htmlspecialchars($issuedReference); ?></p><p class="mt-2 font-mono text-2xl font-bold tracking-widest text-emerald-950"><?php echo htmlspecialchars($issuedCode); ?></p><p class="mt-2 text-sm text-emerald-800">Copy this for local testing now. It is not retained in plaintext.</p></div><?php endif; ?>
<div class="mt-6 overflow-x-auto"><table class="min-w-full text-sm"><thead class="border-b text-left text-slate-500"><tr><th class="p-3">Reference</th><th class="p-3">Beneficiary</th><th class="p-3">Amount</th><th class="p-3">Expires</th><th class="p-3"></th></tr></thead><tbody><?php foreach ($transfers as $transfer): ?><tr class="border-b"><td class="p-3 font-mono"><?php echo htmlspecialchars($transfer['reference']); ?></td><td class="p-3"><?php echo htmlspecialchars($transfer['beneficiary_name']); ?></td><td class="p-3"><?php echo htmlspecialchars($transfer['currency']); ?> <?php echo htmlspecialchars(number_format((float) $transfer['amount'], 2)); ?></td><td class="p-3"><?php echo htmlspecialchars($transfer['expires_at']); ?></td><td class="p-3"><form method="post"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(transfer_csrf_token()); ?>"><input type="hidden" name="reference" value="<?php echo htmlspecialchars($transfer['reference']); ?>"><button name="issue_code" value="1" class="rounded bg-sky-700 px-3 py-2 font-semibold text-white">Issue test code</button></form></td></tr><?php endforeach; ?><?php if ($transfers === []): ?><tr><td class="p-6 text-center text-slate-500" colspan="5">No transfers are awaiting authorization.</td></tr><?php endif; ?></tbody></table></div><?php endif; ?></section></main></body></html>

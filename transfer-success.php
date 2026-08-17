<?php
require_once __DIR__ . '/auth.php';
require_auth();

$userId = current_user_id();
$reference = trim((string) ($_GET['reference'] ?? ''));
if ($reference === '') {
    redirect_to('transactions.php');
}

$conn = arffib_db();
$stmt = $conn->prepare("SELECT t.reference, t.beneficiary_name, t.beneficiary_account, t.bank_name, t.country, t.swift_bic, t.beneficiary_type, t.currency, t.amount, t.completed_at, t.status FROM internal_transfers t WHERE t.reference = ? AND t.sender_user_id = ? AND t.status = 'COMPLETED' LIMIT 1");
$stmt->bind_param('si', $reference, $userId);
$stmt->execute();
$transfer = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($transfer === null) {
    http_response_code(404);
    exit('Completed transfer not found.');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Successful | UW CREDIT UNION</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="mx-auto max-w-xl px-4 py-12">
        <section class="rounded-2xl border border-emerald-200 bg-white p-7 text-center shadow-sm">
            <div
                class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-3xl text-emerald-700">
                ✓</div>
            <h1 class="mt-5 text-3xl font-bold">
                <?php echo $transfer['beneficiary_type'] === 'EXTERNAL_SIMULATION' ? 'Transaction Recorded' : 'Transfer Successful'; ?>
            </h1>
            <p class="mt-2 text-slate-600">
            </p>
            <dl class="mt-7 space-y-3 rounded-xl bg-slate-50 p-5 text-left text-sm">
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
                    <dt>Amount</dt>
                    <dd class="font-semibold"><?php echo htmlspecialchars($transfer['currency']); ?>
                        <?php echo htmlspecialchars(number_format((float) $transfer['amount'], 2)); ?></dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt>Date</dt>
                    <dd class="font-semibold"><?php echo htmlspecialchars((string) $transfer['completed_at']); ?></dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt>Status</dt>
                    <dd class="font-semibold text-yellow-700">Pending</dd>
                </div>
            </dl>
            <div class="mt-6 flex gap-3"><a
                    class="flex-1 rounded-lg bg-sky-700 px-4 py-3 font-semibold text-white hover:bg-sky-800"
                    href="<?php echo htmlspecialchars(app_url('transactions.php')); ?>">Transaction history</a><a
                    class="flex-1 rounded-lg border border-slate-300 px-4 py-3 font-semibold text-slate-700 hover:bg-slate-50"
                    href="<?php echo htmlspecialchars(app_url('dash.php')); ?>">Dashboard</a></div>
        </section>
    </main>
</body>

</html>
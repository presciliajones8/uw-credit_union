<?php
require_once __DIR__ . '/auth.php';
require_auth();

$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbname = "bank_system";

$conn = new mysqli($host, $dbUser, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
if (!is_numeric($user_id)) {
    $user_id = null;
}

$user = [
    'fullName' => 'Guest Customer',
    'balance' => '0.00'
];

$transactions = [];
if ($user_id !== null) {
    $userStmt = $conn->prepare("SELECT fullName, balance FROM users WHERE id = ?");
    $userStmt->bind_param('i', $user_id);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    if ($userResult && $userResult->num_rows > 0) {
        $user = $userResult->fetch_assoc();
    }
    $userStmt->close();

    $txStmt = $conn->prepare("SELECT id, user_id, type, amount, details, transaction_date FROM transactions WHERE user_id = ? ORDER BY transaction_date DESC, id DESC");
    $txStmt->bind_param('i', $user_id);
    $txStmt->execute();
    $txResult = $txStmt->get_result();
    if ($txResult) {
        $transactions = $txResult->fetch_all(MYSQLI_ASSOC);
    }
    $txStmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions | UW CREDIT UNION</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Account overview</p>
                <h1 class="text-2xl font-bold text-slate-900">Transaction history</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="dash.php" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Dashboard</a>
                <a href="transfer.php" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">New transfer</a>
            </div>
        </div>

        <div class="mb-6 grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Account holder</p>
                <p class="mt-2 text-xl font-semibold text-slate-900"><?php echo htmlspecialchars($user['fullName']); ?></p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Current balance</p>
                <p class="mt-2 text-xl font-semibold text-slate-900">$<?php echo htmlspecialchars(number_format((float) ($user['balance'] ?? 0), 2, '.', ',')); ?></p>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Details</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">No transactions found for this account yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm text-slate-600"><?php echo htmlspecialchars((string) ($transaction['transaction_date'] ?? '')); ?></td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold <?php echo strtolower((string) $transaction['type']) === 'transfer' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700'; ?>">
                                            <?php echo htmlspecialchars(ucfirst((string) ($transaction['type'] ?? ''))); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-700"><?php echo htmlspecialchars((string) ($transaction['details'] ?? '')); ?></td>
                                    <td class="px-4 py-3 text-right text-sm font-semibold <?php echo strtolower((string) $transaction['type']) === 'transfer' ? 'text-red-600' : 'text-emerald-600'; ?>">
                                            <?php echo strtolower((string) $transaction['type']) === 'transfer' ? '-' : '+'; ?>$<?php echo htmlspecialchars(number_format((float) ($transaction['amount'] ?? 0), 2, '.', ',')); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

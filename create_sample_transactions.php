<?php
require_once __DIR__ . '/auth.php';

$conn = arffib_db();

// Get current user ID
$user_id = current_user_id();

if ($user_id === null) {
    die("No user logged in. Please login first.\n");
}

// Get current user balance
$stmt = $conn->prepare("SELECT id, fullName, balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.\n");
}

echo "Current User: " . $user['fullName'] . " (ID: " . $user['id'] . ")\n";
echo "Current Balance: $" . number_format($user['balance'], 2) . "\n\n";

// Starting balance for calculations
$startingBalance = (float) $user['balance'];

// Function to generate random date within last 30 days
function getRandomDate($daysBack = 30) {
    $timestamp = time() - rand(0, $daysBack * 86400);
    $randomHour = rand(0, 23);
    $randomMinute = rand(0, 59);
    $randomSecond = rand(0, 59);
    return date('Y-m-d H:i:s', $timestamp - ($timestamp % 86400) + ($randomHour * 3600) + ($randomMinute * 60) + $randomSecond);
}

// Real names for transfers
$realNames = [
    'Sarah Johnson', 'Michael Williams', 'Emily Davis', 'James Brown', 
    'Jennifer Miller', 'David Wilson', 'Lisa Anderson', 'Robert Taylor',
    'Amanda Thomas', 'Christopher Martinez'
];

// Sample transactions with realistic amounts and random dates
$sampleTransactions = [
    [
        'type' => 'deposit',
        'amount' => 5000.00,
        'details' => 'Salary Deposit - Monthly Paycheck',
        'date' => getRandomDate()
    ],
    [
        'type' => 'transfer',
        'amount' => -250.00,
        'details' => 'Transfer to ' . $realNames[0] . ' - Personal',
        'date' => getRandomDate()
    ],
    [
        'type' => 'transfer',
        'amount' => -120.50,
        'details' => 'Transfer to ' . $realNames[1] . ' - Expense Sharing',
        'date' => getRandomDate()
    ],
    [
        'type' => 'deposit',
        'amount' => 1500.00,
        'details' => 'Investment Return - Stock Dividend Payment',
        'date' => getRandomDate()
    ],
    [
        'type' => 'transfer',
        'amount' => -89.99,
        'details' => 'Transfer to ' . $realNames[2] . ' - Service Payment',
        'date' => getRandomDate()
    ],
    [
        'type' => 'transfer',
        'amount' => -450.00,
        'details' => 'Transfer to ' . $realNames[3] . ' - Loan Repayment',
        'date' => getRandomDate()
    ],
    [
        'type' => 'deposit',
        'amount' => 750.00,
        'details' => 'Investment Return - Mutual Fund Distribution',
        'date' => getRandomDate()
    ],
    [
        'type' => 'transfer',
        'amount' => -65.00,
        'details' => 'Transfer to ' . $realNames[4] . ' - Utility Bill Split',
        'date' => getRandomDate()
    ],
    [
        'type' => 'transfer',
        'amount' => -35.00,
        'details' => 'Transfer to ' . $realNames[5] . ' - Small Expense',
        'date' => getRandomDate()
    ],
    [
        'type' => 'deposit',
        'amount' => 2000.00,
        'details' => 'Investment Return - Bond Interest Payment',
        'date' => getRandomDate()
    ]
];

// Calculate what the final balance should be
$calculatedBalance = $startingBalance;
foreach ($sampleTransactions as $tx) {
    $calculatedBalance += $tx['amount'];
}

echo "=== Transaction Summary ===\n";
echo "Starting Balance: $" . number_format($startingBalance, 2) . "\n";
echo "Total Deposits: $" . number_format(array_sum(array_filter(array_column($sampleTransactions, 'amount'), function($a) { return $a > 0; })), 2) . "\n";
echo "Total Withdrawals: $" . number_format(abs(array_sum(array_filter(array_column($sampleTransactions, 'amount'), function($a) { return $a < 0; }))), 2) . "\n";
echo "Calculated Final Balance: $" . number_format($calculatedBalance, 2) . "\n\n";

// Start transaction for data integrity
$conn->begin_transaction();

try {
    // Insert each transaction
    foreach ($sampleTransactions as $tx) {
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, details, transaction_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdss", $user_id, $tx['type'], $tx['amount'], $tx['details'], $tx['date']);
        $stmt->execute();
        $stmt->close();
        echo "✓ Inserted: " . $tx['type'] . " - " . $tx['details'] . " ($" . number_format($tx['amount'], 2) . ")\n";
    }

    // Update user balance
    $updateStmt = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $updateStmt->bind_param("di", $calculatedBalance, $user_id);
    $updateStmt->execute();
    $updateStmt->close();

    // Commit transaction
    $conn->commit();

    echo "\n=== Success ===\n";
    echo "All 10 transactions inserted successfully.\n";
    echo "User balance updated to: $" . number_format($calculatedBalance, 2) . "\n";
    echo "\nYou can now view these transactions at: transactions.php\n";

} catch (Exception $e) {
    $conn->rollback();
    echo "\n=== Error ===\n";
    echo "Transaction failed: " . $e->getMessage() . "\n";
    echo "All changes have been rolled back.\n";
}

$conn->close();

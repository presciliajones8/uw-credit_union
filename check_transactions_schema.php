<?php
require __DIR__ . '/auth.php';

$conn = arffib_db();

echo "=== TRANSACTIONS TABLE SCHEMA ===\n\n";
$result = $conn->query("DESCRIBE transactions");
while ($row = $result->fetch_assoc()) {
    echo "Column: " . $row['Field'] . "\n";
    echo "  Type: " . $row['Type'] . "\n";
    echo "  Null: " . $row['Null'] . "\n";
    echo "  Key: " . $row['Key'] . "\n";
    echo "  Default: " . ($row['Default'] ?? 'NULL') . "\n";
    echo "  Extra: " . $row['Extra'] . "\n";
    echo "\n";
}

echo "\n=== SHOW CREATE TABLE ===\n\n";
$result = $conn->query("SHOW CREATE TABLE transactions");
$row = $result->fetch_assoc();
echo $row['Create Table'] . "\n";

echo "\n=== EXISTING TRANSACTIONS ===\n\n";
$result = $conn->query("SELECT * FROM transactions LIMIT 5");
while ($row = $result->fetch_assoc()) {
    echo "Transaction ID: " . $row['id'] . "\n";
    foreach ($row as $key => $value) {
        echo "  $key: $value\n";
    }
    echo "\n";
}

$conn->close();

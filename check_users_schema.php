<?php
require __DIR__ . '/auth.php';

$conn = arffib_db();

echo "=== USERS TABLE SCHEMA ===\n\n";
$result = $conn->query("DESCRIBE users");
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
$result = $conn->query("SHOW CREATE TABLE users");
$row = $result->fetch_assoc();
echo $row['Create Table'] . "\n";

$conn->close();

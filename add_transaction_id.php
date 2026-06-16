<?php
// add_transaction_id.php
// This script adds the transaction_id column to the payments table if it does not exist.

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'rwanda_marketplace';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    // Check if column exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'payments' AND COLUMN_NAME = 'transaction_id'");
    $stmt->execute([$DB_NAME]);
    $exists = $stmt->fetchColumn();
    if ($exists) {
        echo "Column 'transaction_id' already exists. No changes made.\n";
    } else {
        $pdo->exec("ALTER TABLE payments ADD COLUMN transaction_id VARCHAR(100) NULL");
        echo "Column 'transaction_id' added successfully.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>

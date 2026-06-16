<?php
/**
 * Migration script to add `transaction_id` column to `payments` table if it does not exist.
 *
 * Usage: php add_transaction_id_migration.php
 */

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'rwanda_marketplace';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Check if column already exists
    $stmt = $pdo->prepare("SHOW COLUMNS FROM payments LIKE 'transaction_id'");
    $stmt->execute();
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($exists) {
        echo "[Info] Column 'transaction_id' already exists in `payments` table. No action taken.\n";
    } else {
        $alterSql = "ALTER TABLE payments ADD COLUMN transaction_id VARCHAR(100) NULL";
        $pdo->exec($alterSql);
        echo "[Success] Column 'transaction_id' added to `payments` table.\n";
    }
} catch (PDOException $e) {
    echo "[Error] PDOException: " . $e->getMessage() . "\n";
    exit(1);
}
?>

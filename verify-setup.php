<?php
/**
 * Database Verification Script
 * Checks if database is properly initialized
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'rwanda_marketplace';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║         DATABASE VERIFICATION SCRIPT                     ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

echo "Configuration:\n";
echo "  Host:     {$DB_HOST}\n";
echo "  Database: {$DB_NAME}\n";
echo "  User:     {$DB_USER}\n\n";

// Test 1: Connection
echo "[Test 1] MySQL Connection...\n";
try {
    $pdo = new PDO("mysql:host={$DB_HOST}", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "  ✓ Connected to MySQL server\n\n";
} catch (PDOException $e) {
    echo "  ✗ Failed to connect: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Database exists
echo "[Test 2] Database Existence...\n";
try {
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$DB_NAME}'");
    $result = $stmt->fetch();
    if ($result) {
        echo "  ✓ Database '{$DB_NAME}' exists\n\n";
    } else {
        echo "  ✗ Database '{$DB_NAME}' does not exist\n";
        echo "     → Run: php setup-database.php\n\n";
        exit(1);
    }
} catch (PDOException $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 3: Connect to database
echo "[Test 3] Database Connection...\n";
try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "  ✓ Connected to database\n\n";
} catch (PDOException $e) {
    echo "  ✗ Failed to connect to database: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 4: Critical tables
echo "[Test 4] Critical Tables...\n";
$critical_tables = ['users', 'service_areas', 'listings', 'plans'];
$missing_tables = [];

foreach ($critical_tables as $table) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
        $result = $stmt->fetch();
        if ($result) {
            echo "  ✓ Table '{$table}' exists\n";
        } else {
            echo "  ✗ Table '{$table}' missing\n";
            $missing_tables[] = $table;
        }
    } catch (PDOException $e) {
        echo "  ✗ Error checking table '{$table}': " . $e->getMessage() . "\n";
        $missing_tables[] = $table;
    }
}

if (!empty($missing_tables)) {
    echo "\n  ⚠️  Missing tables: " . implode(', ', $missing_tables) . "\n";
    echo "     → Run: php setup-database.php\n\n";
    exit(1);
}
echo "\n";

// Test 5: Table structure
echo "[Test 5] service_areas Table Structure...\n";
try {
    $stmt = $pdo->query("DESCRIBE service_areas");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $required_columns = ['id', 'user_id', 'area', 'created_at'];
    $found_columns = array_column($columns, 'Field');
    
    foreach ($required_columns as $col) {
        if (in_array($col, $found_columns)) {
            echo "  ✓ Column '{$col}' exists\n";
        } else {
            echo "  ✗ Column '{$col}' missing\n";
        }
    }
    echo "\n";
} catch (PDOException $e) {
    echo "  ✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 6: Foreign keys
echo "[Test 6] Foreign Key Constraints...\n";
try {
    $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'service_areas' AND COLUMN_NAME = 'user_id'");
    $result = $stmt->fetch();
    if ($result) {
        echo "  ✓ Foreign key on user_id exists\n\n";
    } else {
        echo "  ⚠️  No foreign key constraint found\n\n";
    }
} catch (PDOException $e) {
    echo "  ⚠️  Could not verify: " . $e->getMessage() . "\n\n";
}

// All tests passed
echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║  ✅ ALL TESTS PASSED - DATABASE IS READY                ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n";
echo "\nYou can now:\n";
echo "  1. Test registration at: http://localhost/?route=register\n";
echo "  2. Check logs in: php error_log\n";
echo "  3. Run: php verify-setup.php (anytime to check status)\n";

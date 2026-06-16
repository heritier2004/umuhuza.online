<?php
/**
 * Database Setup Script
 * 
 * Run this manually if the auto-initialization in database.php doesn't work:
 * php setup-database.php
 * 
 * This script:
 * 1. Creates the 'rwanda_marketplace' database
 * 2. Runs all table creation statements from schema.sql
 * 3. Seeds initial data (plans, categories, provinces)
 */

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'rwanda_marketplace';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host={$DB_HOST};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "[1] Creating database '{$DB_NAME}'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$DB_NAME}`");
    $pdo->exec("USE `{$DB_NAME}`");
    
    // Read schema
    $schema_path = __DIR__ . '/database/schema.sql';
    if (!file_exists($schema_path)) {
        throw new Exception("Schema file not found at: {$schema_path}");
    }
    
    $schema = file_get_contents($schema_path);
    
    // Parse and execute statements
    $statements = array_filter(
        array_map('trim', explode(';', $schema)),
        function($stmt) {
            $trimmed = trim($stmt);
            return !empty($trimmed) && substr($trimmed, 0, 2) !== '--';
        }
    );
    
    $count = 0;
    echo "\n[2] Creating tables and seeding data...\n";
    foreach ($statements as $statement) {
        try {
            $pdo->exec($statement . ';');
            $count++;
            
            // Show progress for important statements
            if (strpos($statement, 'CREATE TABLE') !== false) {
                preg_match('/CREATE TABLE.*?`(\w+)`/', $statement, $matches);
                if (!empty($matches[1])) {
                    echo "    ✓ Created table: {$matches[1]}\n";
                }
            } elseif (strpos($statement, 'INSERT INTO') !== false) {
                preg_match('/INSERT INTO `(\w+)`/', $statement, $matches);
                if (!empty($matches[1])) {
                    echo "    ✓ Seeded data: {$matches[1]}\n";
                }
            }
        } catch (PDOException $e) {
            // Some statements might fail (duplicates, etc) - that's OK
            error_log("Non-critical error: " . $e->getMessage());
        }
    }
    
    echo "\n[3] Verifying service_areas table...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'service_areas'");
    $result = $stmt->fetch();
    
    if ($result) {
        echo "    ✓ service_areas table exists\n";
        
        // Check structure
        $stmt = $pdo->query("DESCRIBE service_areas");
        $columns = $stmt->fetchAll();
        echo "    ✓ Columns: " . implode(', ', array_column($columns, 'Field')) . "\n";
    } else {
        throw new Exception("service_areas table was not created!");
    }
    
    echo "\n✅ Database setup complete!\n";
    echo "   Executed {$count} statements\n";
    echo "   Database: {$DB_NAME}\n";
    echo "   Host: {$DB_HOST}\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}


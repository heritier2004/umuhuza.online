<?php

$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'rwanda_marketplace';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

$pdo = null;

try {
    // Try to connect with database name first
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    try {
        // If database doesn't exist, create it and initialize schema
        $pdo_temp = new PDO("mysql:host={$DB_HOST};charset=utf8mb4", $DB_USER, $DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        
        // Read and execute schema
        $schema_path = __DIR__ . '/../../database/schema.sql';
        if (file_exists($schema_path)) {
            $schema = file_get_contents($schema_path);
            
            // Split by semicolon and execute each statement
            $statements = array_filter(array_map('trim', explode(';', $schema)), function($stmt) {
                return !empty($stmt) && substr(trim($stmt), 0, 2) !== '--';
            });
            
            foreach ($statements as $statement) {
                try {
                    $pdo_temp->exec($statement . ';');
                } catch (PDOException $stmtError) {
                    error_log("Schema statement error: " . $stmtError->getMessage() . " | Statement: " . substr($statement, 0, 100));
                }
            }
        }
        
        // Now connect to the database
        $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4", $DB_USER, $DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e2) {
        error_log("Database initialization failed: " . $e2->getMessage());
        $pdo = null;
    }
}

// Auto-migration check: ensure all expected columns are present in the database tables
// Auto-migration check: ensure all expected tables and columns are present in the database
if ($pdo) {
    try {
        // 1. Verify and create missing tables using schema.sql
        $stmt = $pdo->query("SHOW TABLES");
        $existingTables = array_column($stmt->fetchAll(PDO::FETCH_NUM), 0);
        
        $requiredTables = [
            'plans', 'users', 'user_plans', 'categories', 'provinces', 
            'districts', 'sectors', 'cells', 'service_areas', 'listings', 
            'listing_images', 'requests', 'request_matches', 'listing_views', 
            'listing_contacts', 'payments', 'notifications', 'verification_requests'
        ];
        
        $hasMissingTable = false;
        foreach ($requiredTables as $reqTable) {
            if (!in_array($reqTable, $existingTables)) {
                $hasMissingTable = true;
                break;
            }
        }
        
        if ($hasMissingTable) {
            $schema_path = __DIR__ . '/../../database/schema.sql';
            if (file_exists($schema_path)) {
                $schema = file_get_contents($schema_path);
                $statements = array_filter(array_map('trim', explode(';', $schema)), function($stmt) {
                    return !empty($stmt) && substr(trim($stmt), 0, 2) !== '--';
                });
                foreach ($statements as $statement) {
                    try {
                        $pdo->exec($statement . ';');
                    } catch (PDOException $stmtError) {
                        // Ignore duplicate keys or non-critical errors during creation
                    }
                }
            }
        }

        // 2. Check and migrate individual columns if necessary
        // Check payments table
        $stmt = $pdo->query("DESCRIBE payments");
        $existingPaymentsCols = array_column($stmt->fetchAll(), 'Field');
        
        $expectedPaymentsCols = [
            'transaction_id' => "ALTER TABLE payments ADD COLUMN transaction_id VARCHAR(100) NULL AFTER plan_id",
            'sender_name' => "ALTER TABLE payments ADD COLUMN sender_name VARCHAR(120) NULL AFTER transaction_id",
            'sender_phone' => "ALTER TABLE payments ADD COLUMN sender_phone VARCHAR(30) NULL AFTER sender_name",
            'method' => "ALTER TABLE payments ADD COLUMN method VARCHAR(50) NOT NULL AFTER status",
            'approved_at' => "ALTER TABLE payments ADD COLUMN approved_at TIMESTAMP NULL AFTER created_at",
        ];

        foreach ($expectedPaymentsCols as $columnName => $alterQuery) {
            if (!in_array($columnName, $existingPaymentsCols)) {
                $pdo->exec($alterQuery);
            }
        }

        // Check user_plans table
        $stmt = $pdo->query("DESCRIBE user_plans");
        $existingUserPlansCols = array_column($stmt->fetchAll(), 'Field');

        if (!in_array('status', $existingUserPlansCols)) {
            $pdo->exec("ALTER TABLE user_plans ADD COLUMN status VARCHAR(30) NOT NULL DEFAULT 'active'");
        }

        // Check users table
        $stmt = $pdo->query("DESCRIBE users");
        $existingUsersCols = array_column($stmt->fetchAll(), 'Field');
        if (!in_array('service_category', $existingUsersCols)) {
            $pdo->exec("ALTER TABLE users ADD COLUMN service_category VARCHAR(100) NULL AFTER account_type");
        }

        // Check request_matches table
        $stmt = $pdo->query("DESCRIBE request_matches");
        $existingMatchesCols = array_column($stmt->fetchAll(), 'Field');
        if (!in_array('status', $existingMatchesCols)) {
            $pdo->exec("ALTER TABLE request_matches ADD COLUMN status VARCHAR(30) NOT NULL DEFAULT 'delivered'");
        }
        if (!in_array('delivered_at', $existingMatchesCols)) {
            $pdo->exec("ALTER TABLE request_matches ADD COLUMN delivered_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
        }

        // Check notifications table
        $stmt = $pdo->query("DESCRIBE notifications");
        $existingNotifCols = array_column($stmt->fetchAll(), 'Field');
        if (!in_array('is_archived', $existingNotifCols)) {
            $pdo->exec("ALTER TABLE notifications ADD COLUMN is_archived TINYINT(1) NOT NULL DEFAULT 0 AFTER is_read");
        }

        // Generate local notification sound files if they don't exist
        $audioDir = __DIR__ . '/../../public/assets/audio';
        if (!is_dir($audioDir)) {
            @mkdir($audioDir, 0755, true);
        }
        
        $sounds = [
            'request.wav' => 587.33, // D5 note for new request/lead
            'success.wav' => 880.00, // A5 note for payment/subscription approval
            'listing.wav' => 698.46, // F5 note for listing approval
        ];
        
        foreach ($sounds as $filename => $freq) {
            $soundPath = $audioDir . '/' . $filename;
            if (!file_exists($soundPath)) {
                $sampleRate = 11025;
                $duration = 0.8;
                $numSamples = $sampleRate * $duration;
                $data = '';
                for ($i = 0; $i < $numSamples; $i++) {
                    $t = $i / $sampleRate;
                    $amplitude = exp(-5 * $t); // decay
                    $val = 128 + 127 * $amplitude * sin(2 * M_PI * $freq * $t);
                    $data .= chr((int)round($val));
                }
                $header = 'RIFF' . pack('V', 36 + strlen($data)) . 'WAVEfmt ' . pack('V', 16) . pack('v', 1) . pack('v', 1) . pack('V', $sampleRate) . pack('V', $sampleRate) . pack('v', 1) . pack('v', 8) . 'data' . pack('V', strlen($data));
                @file_put_contents($soundPath, $header . $data);
            }
        }
    } catch (PDOException $migrationError) {
        error_log("Failed to auto-migrate database: " . $migrationError->getMessage());
    }
}

// Release pending delayed request matches that have expired
if ($pdo) {
    try {
        $stmt = $pdo->prepare('
            SELECT rm.*, r.province, r.district, r.sector, r.type 
            FROM request_matches rm 
            JOIN requests r ON r.id = rm.request_id 
            WHERE rm.status = "pending" AND rm.delivered_at <= NOW()
        ');
        $stmt->execute();
        $pendingRelease = $stmt->fetchAll();
        
        if (!empty($pendingRelease)) {
            $updateStmt = $pdo->prepare('UPDATE request_matches SET status = "delivered" WHERE id = ?');
            foreach ($pendingRelease as $match) {
                $updateStmt->execute([$match['id']]);
                
                // Create the notification for the provider
                NotificationModel::create($pdo, (int)$match['provider_id'], 'New request in ' . trim(($match['district'] ?: $match['province']) . ' / ' . ($match['sector'] ?: '')) . ': ' . sanitize($match['type'] ?? 'service'));
            }
        }
    } catch (PDOException $e) {
        error_log("Failed to release pending request matches: " . $e->getMessage());
    }
}







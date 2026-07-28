<?php

class AdminLog {
    public static function log($pdo, $adminId, $action, $entityType, $entityId, $oldValue = null, $newValue = null, $reason = null) {
        if (!$pdo) return false;
        
        $stmt = $pdo->prepare("
            INSERT INTO admin_logs (admin_id, action, entity_type, entity_id, old_value, new_value, reason, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        return $stmt->execute([
            $adminId,
            $action,
            $entityType,
            $entityId,
            $oldValue ? json_encode($oldValue) : null,
            $newValue ? json_encode($newValue) : null,
            $reason,
            $ipAddress,
            substr($userAgent, 0, 255)
        ]);
    }

    public static function getAllLogs($pdo, $entityType = null, $limit = 100) {
        if (!$pdo) return [];
        
        // PHP PDO bindValue/execute with limit requires integer or custom binding, or we can just interpolate since limit is hardcoded/sanitized
        $limit = (int)$limit;
        if ($entityType) {
            $stmt = $pdo->prepare("
                SELECT al.*, u.full_name, u.email 
                FROM admin_logs al
                JOIN users u ON u.id = al.admin_id
                WHERE al.entity_type = ?
                ORDER BY al.created_at DESC
                LIMIT {$limit}
            ");
            $stmt->execute([$entityType]);
        } else {
            $stmt = $pdo->prepare("
                SELECT al.*, u.full_name, u.email 
                FROM admin_logs al
                JOIN users u ON u.id = al.admin_id
                ORDER BY al.created_at DESC
                LIMIT {$limit}
            ");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

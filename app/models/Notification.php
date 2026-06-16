<?php

class NotificationModel {
    public static function create($pdo, $userId, $message) {
        $stmt = $pdo->prepare('INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)');
        return $stmt->execute([$userId, $message]);
    }

    public static function all($pdo, $userId) {
        $stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function unreadCount($pdo, $userId) {
        $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM notifications WHERE user_id = ? AND is_read = 0');
        $stmt->execute([$userId]);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public static function markAllRead($pdo, $userId) {
        $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = ?');
        return $stmt->execute([$userId]);
    }
}

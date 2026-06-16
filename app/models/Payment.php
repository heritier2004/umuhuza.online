<?php

class Payment {
    public static function create($pdo, $data) {
        $stmt = $pdo->prepare('INSERT INTO payments (user_id, plan_id, transaction_id, sender_name, sender_phone, amount, status, method) VALUES (?, ?, ?, ?, ?, ?, "pending", ?)');
        return $stmt->execute([
            (int) $data['user_id'],
            (int) $data['plan_id'],
            $data['transaction_id'] ?? null,
            $data['sender_name'] ?? null,
            $data['sender_phone'] ?? null,
            (float) ($data['amount'] ?? 0),
            $data['method'] ?? 'mobile_money',
        ]);
    }

    public static function all($pdo) {
        $stmt = $pdo->query('SELECT p.*, u.full_name AS user_name, pl.name AS plan_name FROM payments p JOIN users u ON u.id = p.user_id JOIN plans pl ON pl.id = p.plan_id ORDER BY p.created_at DESC');
        return $stmt->fetchAll();
    }

    public static function find($pdo, $id) {
        $stmt = $pdo->prepare('SELECT p.*, u.full_name AS user_name, pl.name AS plan_name FROM payments p JOIN users u ON u.id = p.user_id JOIN plans pl ON pl.id = p.plan_id WHERE p.id = ?');
        $stmt->execute([(int) $id]);
        return $stmt->fetch();
    }

    public static function updateStatus($pdo, $id, $status) {
        $stmt = $pdo->prepare('UPDATE payments SET status = ?, approved_at = CASE WHEN ? = "approved" THEN NOW() ELSE approved_at END WHERE id = ?');
        return $stmt->execute([$status, $status, (int) $id]);
    }
}

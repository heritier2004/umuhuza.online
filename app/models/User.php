<?php

class User {
    public static function providers($pdo) {
        $stmt = $pdo->query('SELECT * FROM users WHERE role = "provider" ORDER BY created_at DESC LIMIT 12');
        return $stmt->fetchAll();
    }

    public static function create($pdo, $data) {
        $stmt = $pdo->prepare('INSERT INTO users (full_name, username, phone, whatsapp, email, password_hash, role, account_type, service_category, province, district, sector, cell, village, profile_image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "active")');
        $ok = $stmt->execute([
            $data['full_name'],
            $data['username'],
            $data['phone'],
            $data['whatsapp'],
            $data['email'],
            $data['password_hash'],
            $data['role'],
            $data['account_type'] ?? 'agent',
            $data['service_category'] ?? null,
            $data['province'] ?? null,
            $data['district'] ?? null,
            $data['sector'] ?? null,
            $data['cell'] ?? null,
            $data['village'] ?? null,
            $data['profile_image'] ?? null,
        ]);
        return $ok ? (int) $pdo->lastInsertId() : false;
    }

    public static function findByEmail($pdo, $email) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function findByPhone($pdo, $phone) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE phone = ?');
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }

    public static function findByUsername($pdo, $username) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function updateStatus($pdo, $id, $status) {
        $stmt = $pdo->prepare('UPDATE users SET status = ? WHERE id = ?');
        return $stmt->execute([$status, (int) $id]);
    }

    public static function updateRole($pdo, $id, $role) {
        $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
        return $stmt->execute([$role, (int) $id]);
    }
}

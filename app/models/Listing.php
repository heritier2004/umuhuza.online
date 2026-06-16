<?php

class Listing {
    public static function create($pdo, $data) {
        $stmt = $pdo->prepare('INSERT INTO listings (user_id, category_id, title, description, price, province, district, sector, cell, plan_id, status, rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "pending", 4.5)');
        $ok = $stmt->execute([
            $data['user_id'],
            $data['category_id'],
            $data['title'],
            $data['description'],
            $data['price'],
            $data['province'],
            $data['district'],
            $data['sector'],
            $data['cell'],
            $data['plan_id'],
        ]);
        return $ok ? (int) $pdo->lastInsertId() : false;
    }

    public static function all($pdo) {
        $stmt = $pdo->query('SELECT l.*, c.name AS category_name, p.name AS plan_name, u.full_name AS provider_name FROM listings l LEFT JOIN categories c ON c.id = l.category_id LEFT JOIN plans p ON p.id = l.plan_id LEFT JOIN users u ON u.id = l.user_id ORDER BY l.created_at DESC');
        return $stmt->fetchAll();
    }

    public static function byUser($pdo, $userId) {
        $stmt = $pdo->prepare('SELECT l.*, c.name AS category_name, p.name AS plan_name, u.full_name AS provider_name FROM listings l LEFT JOIN categories c ON c.id = l.category_id LEFT JOIN plans p ON p.id = l.plan_id LEFT JOIN users u ON u.id = l.user_id WHERE l.user_id = ? ORDER BY l.created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function find($pdo, $id) {
        $stmt = $pdo->prepare('SELECT l.*, c.name AS category_name, p.name AS plan_name, u.full_name AS provider_name, u.phone, u.whatsapp, u.email, u.status AS provider_status FROM listings l LEFT JOIN categories c ON c.id = l.category_id LEFT JOIN plans p ON p.id = l.plan_id LEFT JOIN users u ON u.id = l.user_id WHERE l.id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function images($pdo, $listingId) {
        $stmt = $pdo->prepare('SELECT image_path FROM listing_images WHERE listing_id = ? ORDER BY id ASC');
        $stmt->execute([$listingId]);
        return $stmt->fetchAll();
    }

    public static function updateStatus($pdo, $id, $status) {
        $stmt = $pdo->prepare('UPDATE listings SET status = ? WHERE id = ?');
        return $stmt->execute([$status, (int) $id]);
    }

    public static function toggleFeatured($pdo, $id, $planId) {
        $stmt = $pdo->prepare('UPDATE listings SET plan_id = ? WHERE id = ?');
        return $stmt->execute([(int) $planId, (int) $id]);
    }
}

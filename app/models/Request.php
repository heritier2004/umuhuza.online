<?php

class RequestModel {
    public static function create($pdo, $data) {
        $stmt = $pdo->prepare('INSERT INTO requests (name, phone, whatsapp, province, district, sector, budget, description, type, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, "new")');
        $ok = $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['whatsapp'],
            $data['province'],
            $data['district'],
            $data['sector'],
            $data['budget'],
            $data['description'],
            $data['type'],
        ]);
        return $ok ? (int) $pdo->lastInsertId() : false;
    }

    public static function all($pdo) {
        $stmt = $pdo->query('SELECT * FROM requests ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}

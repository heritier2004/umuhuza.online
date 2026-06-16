<?php

class Plan {
    public static function all($pdo) {
        $stmt = $pdo->query('SELECT * FROM plans ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    public static function currentForUser($pdo, $userId) {
        $stmt = $pdo->prepare('SELECT p.* FROM user_plans up JOIN plans p ON p.id = up.plan_id WHERE up.user_id = ? ORDER BY up.id DESC LIMIT 1');
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}

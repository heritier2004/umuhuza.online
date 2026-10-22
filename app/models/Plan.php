<?php

class Plan {
    public static function all($pdo) {
        $stmt = $pdo->query('SELECT * FROM plans ORDER BY id ASC');
        return $stmt->fetchAll();
    }

    public static function currentForUser($pdo, $userId) {
        if (!$pdo) return ['id' => 1, 'name' => 'Free', 'listing_limit' => 5];
        $stmt = $pdo->prepare('SELECT p.* FROM user_plans up JOIN plans p ON p.id = up.plan_id WHERE up.user_id = ? ORDER BY up.id DESC LIMIT 1');
        $stmt->execute([$userId]);
        $plan = $stmt->fetch();
        if (!$plan) {
            $stmtDefault = $pdo->query('SELECT * FROM plans WHERE id = 1 LIMIT 1');
            $plan = $stmtDefault ? $stmtDefault->fetch() : null;
            if (!$plan) {
                $plan = ['id' => 1, 'name' => 'Free', 'listing_limit' => 5];
            }
        }
        return $plan;
    }
}

<?php

class ServiceArea {
    public static function saveMany($pdo, $userId, array $areas) {
        if (!$userId) return 0;
        $stmt = $pdo->prepare('DELETE FROM service_areas WHERE user_id = ?');
        $stmt->execute([$userId]);
        $count = 0;
        foreach ($areas as $area) {
            $area = trim((string) $area);
            if ($area === '') continue;
            $insert = $pdo->prepare('INSERT INTO service_areas (user_id, area) VALUES (?, ?)');
            if ($insert->execute([$userId, $area])) {
                $count++;
            }
        }
        return $count;
    }
}

<?php

function expireExpiredSubscriptions($pdo) {
    $columns = $pdo->query('SHOW COLUMNS FROM user_plans')->fetchAll(PDO::FETCH_COLUMN);
    $hasStatus = in_array('status', $columns, true);

    $query = 'SELECT up.id, up.user_id, up.plan_id FROM user_plans up WHERE up.ends_at IS NOT NULL AND up.ends_at < NOW()';
    if ($hasStatus) {
        $query .= ' AND up.status = "active"';
    }

    $stmt = $pdo->query($query);
    $expired = $stmt->fetchAll();

    foreach ($expired as $entry) {
        if ($hasStatus) {
            $pdo->prepare('UPDATE user_plans SET status = "expired" WHERE id = ?')->execute([$entry['id']]);
            $pdo->prepare('INSERT INTO user_plans (user_id, plan_id, starts_at, ends_at, status) VALUES (?, 1, NOW(), NULL, "active")')->execute([$entry['user_id']]);
        } else {
            $pdo->prepare('INSERT INTO user_plans (user_id, plan_id, starts_at, ends_at) VALUES (?, 1, NOW(), NULL)')->execute([$entry['user_id']]);
        }

        $pdo->prepare('UPDATE listings SET plan_id = 1 WHERE user_id = ?')->execute([$entry['user_id']]);
        NotificationModel::create($pdo, (int) $entry['user_id'], 'Your plan expired and your listings were returned to the Free plan.');
    }

    return count($expired);
}

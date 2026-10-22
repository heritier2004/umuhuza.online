<?php

class NotificationModel {
    public static function create($pdo, $userId, $message, $requestId = null, $url = '/') {
        $stmt = $pdo->prepare('INSERT INTO notifications (user_id, request_id, message, is_read) VALUES (?, ?, ?, 0)');
        $res = $stmt->execute([$userId, $requestId, $message]);

        // Dispatch Web Push Notification
        if ($res && defined('VAPID_PUBLIC_KEY') && class_exists('\Minishlink\WebPush\WebPush')) {
            $stmtSub = $pdo->prepare('SELECT endpoint, p256dh, auth FROM push_subscriptions WHERE user_id = ?');
            $stmtSub->execute([$userId]);
            $subs = $stmtSub->fetchAll();

            if (!empty($subs)) {
                $auth = [
                    'VAPID' => [
                        'subject' => VAPID_SUBJECT,
                        'publicKey' => VAPID_PUBLIC_KEY,
                        'privateKey' => VAPID_PRIVATE_KEY,
                    ],
                ];
                $webPush = new \Minishlink\WebPush\WebPush($auth);
                $payload = json_encode(['title' => 'UMUHUZA Alert', 'body' => $message, 'url' => $url]);

                foreach ($subs as $sub) {
                    $subscription = \Minishlink\WebPush\Subscription::create([
                        'endpoint' => $sub['endpoint'],
                        'publicKey' => $sub['p256dh'],
                        'authToken' => $sub['auth'],
                    ]);
                    $webPush->queueNotification($subscription, $payload);
                }
                
                // Fire and forget
                foreach ($webPush->flush() as $report) {
                    if (!$report->isSuccess() && $report->isSubscriptionExpired()) {
                        $pdo->prepare('DELETE FROM push_subscriptions WHERE endpoint = ?')->execute([$report->getRequest()->getUri()->__toString()]);
                    }
                }
            }
        }
        return $res;
    }

    public static function all($pdo, $userId) {
        $stmt = $pdo->prepare('
            SELECT n.*, 
                   r.name AS client_name, 
                   r.phone AS client_phone, 
                   r.whatsapp AS client_whatsapp, 
                   r.province AS client_province, 
                   r.district AS client_district, 
                   r.sector AS client_sector, 
                   r.budget AS client_budget, 
                   r.description AS client_description, 
                   r.type AS client_type,
                   r.status AS request_status,
                   r.created_at AS request_created_at
            FROM notifications n
            LEFT JOIN requests r ON r.id = n.request_id
            WHERE n.user_id = ? AND (n.is_archived = 0 OR n.is_archived IS NULL)
            ORDER BY n.created_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function unreadCount($pdo, $userId) {
        $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM notifications WHERE user_id = ? AND is_read = 0 AND (is_archived = 0 OR is_archived IS NULL)');
        $stmt->execute([$userId]);
        return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public static function markAllRead($pdo, $userId) {
        $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = ?');
        return $stmt->execute([$userId]);
    }

    public static function markRead($pdo, $id, $userId = null) {
        if ($userId !== null) {
            $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?');
            return $stmt->execute([$id, $userId]);
        }
        $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function notifyAdmins($pdo, $message, $requestId = null, $url = '/?route=admin-dashboard') {
        if (!$pdo) return false;
        try {
            $stmt = $pdo->query('SELECT id FROM users WHERE role = "admin" OR account_type = "admin"');
            $admins = $stmt ? $stmt->fetchAll() : [];
            if (empty($admins)) {
                self::create($pdo, 1, $message, $requestId, $url);
                return true;
            }
            foreach ($admins as $admin) {
                self::create($pdo, (int)$admin['id'], $message, $requestId, $url);
            }
            return true;
        } catch (\Exception $e) {
            error_log("Failed to notify admins: " . $e->getMessage());
            return false;
        }
    }
}


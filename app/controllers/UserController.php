<?php

class UserController {
    public function providerDashboard($pdo) {
        requireLogin();
        $user = currentUser();
        $listings = Listing::byUser($pdo, (int) $user['id']);
        $plan = Plan::currentForUser($pdo, (int) $user['id']);
        
        // Determine weekly listing count for the user
        $stmtCount = $pdo->prepare('SELECT COUNT(*) FROM listings WHERE user_id = ? AND YEARWEEK(created_at,1) = YEARWEEK(CURDATE(),1)');
        $stmtCount->execute([(int) $user['id']]);
        $weeklyCount = (int) $stmtCount->fetchColumn();
        $remainingQuota = $plan && isset($plan['listing_limit']) ? max((int) $plan['listing_limit'] - $weeklyCount, 0) : null;
        
        $matchedRequests = [];
        $notifications = [];
        $blockedLeadsCount = 0;
        if ($pdo) {
            $stmt = $pdo->prepare('
                SELECT r.*, rm.match_level, rm.priority_score 
                FROM requests r 
                JOIN request_matches rm ON rm.request_id = r.id 
                WHERE rm.provider_id = ? AND rm.status = "delivered"
                ORDER BY r.created_at DESC
            ');
            $stmt->execute([(int) $user['id']]);
            $matchedRequests = $stmt->fetchAll();
            
            $notifications = NotificationModel::all($pdo, (int) $user['id']);

            $bStmt = $pdo->prepare('
                SELECT COUNT(*) AS total 
                FROM request_matches 
                WHERE provider_id = ? AND status = "blocked"
            ');
            $bStmt->execute([(int) $user['id']]);
            $blockedLeadsCount = (int)($bStmt->fetch()['total'] ?? 0);
        }
        
        return compact('user', 'listings', 'plan', 'matchedRequests', 'notifications', 'blockedLeadsCount', 'remainingQuota');
    }
}

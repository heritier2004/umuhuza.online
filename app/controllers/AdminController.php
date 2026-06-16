<?php

class AdminController {
    private function activatePlanForUser($pdo, $userId, $planId) {
        $planStmt = $pdo->prepare('SELECT * FROM plans WHERE id = ?');
        $planStmt->execute([(int) $planId]);
        $plan = $planStmt->fetch();

        $duration = max((int) ($plan['duration_months'] ?? 1), 1);
        $pdo->prepare('UPDATE user_plans SET status = "expired" WHERE user_id = ? AND status = "active"')->execute([$userId]);
        $pdo->prepare('INSERT INTO user_plans (user_id, plan_id, starts_at, ends_at, status) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? MONTH), "active")')->execute([$userId, (int) $planId, $duration]);
        $pdo->prepare('UPDATE listings SET plan_id = ? WHERE user_id = ?')->execute([(int) $planId, $userId]);
        NotificationModel::create($pdo, (int) $userId, 'Your plan was approved and activated successfully.');
    }

    public function dashboard($pdo) {
        requireLogin();
        $listings = Listing::all($pdo);
        $requests = RequestModel::all($pdo);
        $users = $pdo ? $pdo->query('SELECT * FROM users')->fetchAll() : [];
        $payments = Payment::all($pdo);

        $activePlans = $pdo ? $pdo->query('SELECT up.user_id, up.plan_id, pl.name AS plan_name FROM user_plans up JOIN plans pl ON pl.id = up.plan_id')->fetchAll() : [];
        $activeUsers = count(array_filter($users, fn($user) => ($user['status'] ?? '') === 'active'));
        $premiumUsers = count(array_filter($activePlans, fn($plan) => (int)($plan['plan_id'] ?? 0) === 2));
        $superUsers = count(array_filter($activePlans, fn($plan) => (int)($plan['plan_id'] ?? 0) === 3));
        $approvedRevenue = array_sum(array_map(fn($payment) => (($payment['status'] ?? '') === 'approved') ? (float)($payment['amount'] ?? 0) : 0, $payments));

        $analytics = [
            'approvedPayments' => count(array_filter($payments, fn($payment) => ($payment['status'] ?? '') === 'approved')),
            'pendingPayments' => count(array_filter($payments, fn($payment) => ($payment['status'] ?? '') === 'pending')),
            'revenue' => $approvedRevenue,
            'activeProviders' => count(array_filter($users, fn($user) => ($user['role'] ?? '') === 'provider')),
            'totalUsers' => count($users),
            'activeUsers' => $activeUsers,
            'premiumUsers' => $premiumUsers,
            'superUsers' => $superUsers,
            'totalListings' => count($listings),
            'totalRequests' => count($requests),
            'pendingListings' => count(array_filter($listings, fn($item) => ($item['status'] ?? '') === 'pending')),
        ];

        // 11 Required Admin Analytics
        $totalRequests = count($requests);
        
        $requestsByProvince = [];
        foreach ($requests as $req) {
            $prov = $req['province'] ?: 'Unknown';
            $requestsByProvince[$prov] = ($requestsByProvince[$prov] ?? 0) + 1;
        }

        $requestsByCategory = [];
        foreach ($requests as $req) {
            $cat = $req['type'] ?: 'General';
            $requestsByCategory[$cat] = ($requestsByCategory[$cat] ?? 0) + 1;
        }

        $requestsDelivered = $pdo ? (int)$pdo->query("SELECT COUNT(*) FROM request_matches WHERE status = 'delivered'")->fetchColumn() : 0;
        $requestsPending = $pdo ? (int)$pdo->query("SELECT COUNT(*) FROM request_matches WHERE status = 'pending'")->fetchColumn() : 0;
        $requestsExpired = $pdo ? (int)$pdo->query("SELECT COUNT(*) FROM requests WHERE status = 'expired' OR created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn() : 0;
        
        $freePlanLeadsSent = $pdo ? (int)$pdo->query("SELECT COUNT(*) FROM request_matches WHERE priority_score = 1 AND status = 'delivered'")->fetchColumn() : 0;
        $premiumLeadsSent = $pdo ? (int)$pdo->query("SELECT COUNT(*) FROM request_matches WHERE priority_score = 2 AND status = 'delivered'")->fetchColumn() : 0;
        $superPremiumLeadsSent = $pdo ? (int)$pdo->query("SELECT COUNT(*) FROM request_matches WHERE priority_score = 3 AND status = 'delivered'")->fetchColumn() : 0;
        
        $topDemandAreas = $pdo ? $pdo->query("SELECT CONCAT(COALESCE(province, 'Rwanda'), ' > ', COALESCE(district, 'Nationwide')) AS area, COUNT(*) AS count FROM requests GROUP BY province, district ORDER BY count DESC LIMIT 5")->fetchAll() : [];
        $topDemandServices = $pdo ? $pdo->query("SELECT type AS service, COUNT(*) AS count FROM requests GROUP BY type ORDER BY count DESC LIMIT 5")->fetchAll() : [];

        $adminAnalytics = [
            'totalRequests' => $totalRequests,
            'requestsByProvince' => $requestsByProvince,
            'requestsByCategory' => $requestsByCategory,
            'requestsDelivered' => $requestsDelivered,
            'requestsPending' => $requestsPending,
            'requestsExpired' => $requestsExpired,
            'freePlanLeadsSent' => $freePlanLeadsSent,
            'premiumLeadsSent' => $premiumLeadsSent,
            'superPremiumLeadsSent' => $superPremiumLeadsSent,
            'topDemandAreas' => $topDemandAreas,
            'topDemandServices' => $topDemandServices,
        ];

        $verifications = $pdo ? $pdo->query('SELECT vr.*, u.full_name, u.role, u.email FROM verification_requests vr JOIN users u ON u.id = vr.user_id ORDER BY vr.created_at DESC')->fetchAll() : [];
        if (empty($verifications)) {
            $verifications = [
                ['id' => 1, 'user_id' => 2, 'status' => 'pending', 'note' => 'Identity verification request', 'created_at' => '2026-06-09 12:00:00', 'full_name' => 'Marie Uwimana', 'role' => 'provider', 'email' => 'marie@example.com']
            ];
        }

        return compact('listings', 'requests', 'users', 'payments', 'analytics', 'adminAnalytics', 'verifications');
    }

    public function approvePayment($pdo, $paymentId) {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            header('Location: ?route=home');
            exit;
        }

        $payment = Payment::find($pdo, $paymentId);
        if (!$payment) {
            flash('error', 'Payment not found.');
            header('Location: ?route=admin-dashboard');
            exit;
        }

        Payment::updateStatus($pdo, $paymentId, 'approved');
        $this->activatePlanForUser($pdo, (int) $payment['user_id'], (int) $payment['plan_id']);

        flash('success', 'Payment approved and subscription activated.');
        header('Location: ?route=admin-dashboard');
        exit;
    }

    public function rejectPayment($pdo, $paymentId) {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            header('Location: ?route=home');
            exit;
        }

        $payment = Payment::find($pdo, $paymentId);
        if (!$payment) {
            flash('error', 'Payment not found.');
            header('Location: ?route=admin-dashboard');
            exit;
        }

        Payment::updateStatus($pdo, $paymentId, 'rejected');
        NotificationModel::create($pdo, (int) $payment['user_id'], 'Your plan upgrade request was rejected. Please contact support for details.');
        flash('success', 'Payment rejected.');
        header('Location: ?route=admin-dashboard');
        exit;
    }

    public function approveListing($pdo, $id) {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            header('Location: ?route=home');
            exit;
        }
        if ($pdo) {
            Listing::updateStatus($pdo, $id, 'active');
            $listing = Listing::find($pdo, $id);
            if ($listing) {
                NotificationModel::create($pdo, (int)$listing['user_id'], "Your listing '" . $listing['title'] . "' was approved and is now active.");
            }
        }
        flash('success', 'Listing approved successfully.');
        header('Location: ?route=admin-dashboard');
        exit;
    }

    public function rejectListing($pdo, $id) {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            header('Location: ?route=home');
            exit;
        }
        if ($pdo) {
            Listing::updateStatus($pdo, $id, 'rejected');
            $listing = Listing::find($pdo, $id);
            if ($listing) {
                NotificationModel::create($pdo, (int)$listing['user_id'], "Your listing '" . $listing['title'] . "' was rejected. Please review our guidelines.");
            }
        }
        flash('success', 'Listing rejected.');
        header('Location: ?route=admin-dashboard');
        exit;
    }

    public function toggleListingFeatured($pdo, $id) {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            header('Location: ?route=home');
            exit;
        }
        if ($pdo) {
            $listing = Listing::find($pdo, $id);
            if ($listing) {
                $newPlanId = ((int)$listing['plan_id'] <= 1) ? 2 : 1;
                Listing::toggleFeatured($pdo, $id, $newPlanId);
            }
        }
        flash('success', 'Listing featured status toggled.');
        header('Location: ?route=admin-dashboard');
        exit;
    }

    public function approveVerification($pdo, $id) {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            header('Location: ?route=home');
            exit;
        }
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT * FROM verification_requests WHERE id = ?');
            $stmt->execute([(int)$id]);
            $req = $stmt->fetch();
            if ($req) {
                $pdo->prepare('UPDATE verification_requests SET status = "approved" WHERE id = ?')->execute([(int)$id]);
                User::updateStatus($pdo, $req['user_id'], 'active');
                NotificationModel::create($pdo, (int)$req['user_id'], 'Your provider profile was verified successfully.');
            }
        }
        flash('success', 'Provider verified successfully.');
        header('Location: ?route=admin-dashboard');
        exit;
    }

    public function rejectVerification($pdo, $id) {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            header('Location: ?route=home');
            exit;
        }
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT * FROM verification_requests WHERE id = ?');
            $stmt->execute([(int)$id]);
            $req = $stmt->fetch();
            if ($req) {
                $pdo->prepare('UPDATE verification_requests SET status = "rejected" WHERE id = ?')->execute([(int)$id]);
                NotificationModel::create($pdo, (int)$req['user_id'], 'Your verification request was rejected. Please upload valid documents.');
            }
        }
        flash('success', 'Verification rejected.');
        header('Location: ?route=admin-dashboard');
        exit;
    }

    public function toggleUserStatus($pdo, $id) {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            header('Location: ?route=home');
            exit;
        }
        if ($pdo) {
            $user = User::findById($pdo, $id);
            if ($user) {
                $newStatus = ($user['status'] === 'active') ? 'suspended' : 'active';
                User::updateStatus($pdo, $id, $newStatus);
            }
        }
        flash('success', 'User status updated.');
        header('Location: ?route=admin-dashboard');
        exit;
    }
}

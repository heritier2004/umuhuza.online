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

        $categories = $pdo ? $pdo->query('SELECT * FROM categories ORDER BY name ASC')->fetchAll() : [];
        
        return compact('user', 'listings', 'plan', 'matchedRequests', 'notifications', 'blockedLeadsCount', 'remainingQuota', 'categories');
    }

    public function updateProfile($pdo) {
        requireLogin();
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            flash('error', 'Invalid security token. Please try submitting the profile form again.');
            header('Location: ?route=provider-dashboard#profile-settings');
            exit;
        }
        $user = currentUser();
        $userId = (int) $user['id'];

        $fullName    = sanitize($_POST['full_name'] ?? '');
        $username    = sanitize($_POST['username'] ?? '');
        $phone       = sanitize($_POST['phone'] ?? '');
        $whatsapp    = sanitize($_POST['whatsapp'] ?? '');
        $email       = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $accountType = sanitize($_POST['account_type'] ?? 'agent');
        $serviceCat  = sanitize($_POST['service_category'] ?? '');
        $province    = sanitize($_POST['province'] ?? '');
        $district    = sanitize($_POST['district'] ?? '');
        $sector      = sanitize($_POST['sector'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';

        if (empty($fullName) || empty($phone) || empty($email)) {
            flash('error', 'Full Name, Phone number, and Email are required.');
            header('Location: ?route=provider-dashboard#profile-settings');
            exit;
        }

        // Check if username/email already taken by another account
        if ($pdo) {
            if (!empty($username)) {
                $chkUser = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
                $chkUser->execute([$username, $userId]);
                if ($chkUser->fetch()) {
                    flash('error', 'Username is already taken by another account.');
                    header('Location: ?route=provider-dashboard#profile-settings');
                    exit;
                }
            }

            $chkEmail = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
            $chkEmail->execute([$email, $userId]);
            if ($chkEmail->fetch()) {
                flash('error', 'Email address is already taken by another account.');
                header('Location: ?route=provider-dashboard#profile-settings');
                exit;
            }
        }

        $updateData = [
            'full_name'        => $fullName,
            'username'         => !empty($username) ? $username : $user['username'],
            'phone'            => $phone,
            'whatsapp'         => !empty($whatsapp) ? $whatsapp : $phone,
            'email'            => $email,
            'account_type'     => $accountType,
            'service_category' => $serviceCat,
            'province'         => $province,
            'district'         => $district,
            'sector'           => $sector,
            'cell'             => sanitize($_POST['cell'] ?? ''),
        ];

        // Handle profile photo upload if provided
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadedImg = handleUpload($_FILES['profile_image']);
            if ($uploadedImg) {
                $updateData['profile_image'] = $uploadedImg;
            }
        }

        // Handle password change if provided
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                flash('error', 'New password must be at least 6 characters.');
                header('Location: ?route=provider-dashboard#profile-settings');
                exit;
            }
            $updateData['password_hash'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        if ($pdo) {
            User::updateProfile($pdo, $userId, $updateData);
        }

        // Update active session data
        $_SESSION['full_name'] = $fullName;
        $_SESSION['username']  = $updateData['username'];
        $_SESSION['phone']     = $phone;
        $_SESSION['email']     = $email;

        flash('success', 'Your profile and contact details have been updated successfully!');
        header('Location: ?route=provider-dashboard#profile-settings');
        exit;
    }
}

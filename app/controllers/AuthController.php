<?php

class AuthController {
    public function register($pdo) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                flash('error', 'Security token invalid. Please try again.');
                header('Location: ?route=register');
                exit;
            }
            
            $data = [
                'full_name' => sanitize($_POST['full_name'] ?? ''),
                'username' => sanitize($_POST['username'] ?? ''),
                'phone' => sanitize($_POST['phone'] ?? ''),
                'whatsapp' => sanitize($_POST['whatsapp'] ?? ''),
                'email' => sanitize($_POST['email'] ?? ''),
                'province' => sanitize($_POST['province'] ?? ''),
                'district' => sanitize($_POST['district'] ?? ''),
                'sector' => sanitize($_POST['sector'] ?? ''),
                'cell' => sanitize($_POST['cell'] ?? ''),
                'village' => sanitize($_POST['village'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm' => $_POST['confirm_password'] ?? '',
                'role' => 'provider',
                'account_type' => sanitize($_POST['account_type'] ?? 'agent'),
                'service_category' => sanitize($_POST['service_category'] ?? null),
                'profile_image' => null,
                'service_areas' => preg_split('/[;,]+/', sanitize($_POST['service_areas'] ?? ''), -1, PREG_SPLIT_NO_EMPTY),
            ];
            
            // Validate password length
            if (strlen($data['password']) < 8) {
                flash('error', 'Password must be at least 8 characters long.');
                header('Location: ?route=register');
                exit;
            }
            
            // Validate username length
            if (strlen($data['username']) < 3) {
                flash('error', 'Username must be at least 3 characters long.');
                header('Location: ?route=register');
                exit;
            }
            if ($data['password'] !== $data['confirm']) {
                flash('error', 'Passwords do not match.');
                header('Location: ?route=register');
                exit;
            }
            
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Please enter a valid email address.');
                header('Location: ?route=register');
                exit;
            }
            
            // Validate phone format (basic check for Rwandan format)
            if (!preg_match('/^(\+?250|0)?[0-9]{9,12}$/', $data['phone'])) {
                flash('error', 'Please enter a valid phone number.');
                header('Location: ?route=register');
                exit;
            }
            
            $profileImage = handleUpload($_FILES['profile_image'] ?? null);
            if ($profileImage) {
                $data['profile_image'] = $profileImage;
            }
            if (User::findByEmail($pdo, $data['email'])) {
                flash('error', 'Email already exists.');
                header('Location: ?route=register');
                exit;
            }
            $userId = User::create($pdo, [
                'full_name' => $data['full_name'],
                'username' => $data['username'],
                'phone' => $data['phone'],
                'whatsapp' => $data['whatsapp'],
                'email' => $data['email'],
                'password_hash' => hashPassword($data['password']),
                'role' => $data['role'],
                'account_type' => $data['account_type'],
                'service_category' => $data['service_category'],
                'province' => $data['province'],
                'district' => $data['district'],
                'sector' => $data['sector'],
                'cell' => $data['cell'],
                'village' => $data['village'],
                'profile_image' => $data['profile_image'],
            ]);
            if ($userId) {
                ServiceArea::saveMany($pdo, $userId, $data['service_areas'] ?? []);
            }
            flash('success', 'Registration successful. Please login.');
            header('Location: ?route=login');
            exit;
        }
    }

    public function login($pdo) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                flash('error', 'Security token invalid. Please try again.');
                header('Location: ?route=login');
                exit;
            }
            
            $identifier = sanitize($_POST['identifier'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = User::findByEmail($pdo, $identifier);
            if (!$user) {
                $user = User::findByPhone($pdo, $identifier);
            }
            if (!$user) {
                $user = User::findByUsername($pdo, $identifier);
            }
            if ($user && verifyPassword($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                flash('success', 'Welcome back.');
                if ($user['role'] === 'admin') {
                    header('Location: ?route=admin-dashboard');
                } else {
                    header('Location: ?route=provider-dashboard');
                }
                exit;
            }
            flash('error', 'Invalid credentials.');
            header('Location: ?route=login');
            exit;
        }
    }
}

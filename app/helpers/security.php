<?php

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function sanitize($value) {
    return trim(strip_tags((string) $value));
}

function isLoggedIn() {
    return !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        flash('error', 'Please login to continue.');
        header('Location: ?route=login');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        flash('error', 'Access denied. Administrator privileges required.');
        header('Location: ?route=home');
        exit;
    }
}

/**
 * Checks session and IP rate limits for sensitive operations.
 *
 * @param string $actionKey Unique action identifier (e.g., 'login', 'register', 'submit_request')
 * @param int $maxAttempts Maximum allowed attempts within decay window
 * @param int $decaySeconds Window duration in seconds
 * @return bool True if within limits, False if rate limit exceeded
 */
function checkRateLimit($actionKey, $maxAttempts = 5, $decaySeconds = 120) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $bucketKey = 'rate_limit_' . md5($actionKey . '_' . $ip);

    $now = time();
    $attempts = $_SESSION[$bucketKey] ?? [];

    // Filter out expired attempts outside decay window
    $attempts = array_filter($attempts, function ($timestamp) use ($now, $decaySeconds) {
        return ($now - $timestamp) < $decaySeconds;
    });

    if (count($attempts) >= $maxAttempts) {
        return false;
    }

    $attempts[] = $now;
    $_SESSION[$bucketKey] = $attempts;
    return true;
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function getCsrfToken() {
    return $_SESSION['csrf_token'] ?? '';
}

function verifyCsrfToken($token) {
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

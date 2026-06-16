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
        header('Location: ?route=login');
        exit;
    }
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

<?php

function currentUser() {
    static $user = null;
    if ($user === null && !empty($_SESSION['user_id'])) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
    return $user;
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: ?route=login');
    exit;
}

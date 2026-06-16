<?php

class NotificationController {
    public function index($pdo) {
        requireLogin();
        $notifications = NotificationModel::all($pdo, $_SESSION['user_id']);
        return $notifications;
    }
}

<?php

class PaymentController {
    public function upgrade($pdo) {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                flash('error', 'Security token invalid. Please try again.');
                header('Location: ?route=provider-dashboard');
                exit;
            }
            
            $planId = (int) ($_POST['plan_id'] ?? 1);
            $transactionId = sanitize($_POST['transaction_id'] ?? '');
            $senderName = sanitize($_POST['sender_name'] ?? '');
            $senderPhone = sanitize($_POST['sender_phone'] ?? '');
            $amount = (float) ($_POST['amount'] ?? 0);

            if ($planId < 1 || $transactionId === '' || $senderName === '' || $senderPhone === '') {
                flash('error', 'Please provide the plan, transaction ID, sender name, and sender phone.');
                header('Location: ?route=provider-dashboard');
                exit;
            }

            Payment::create($pdo, [
                'user_id' => $_SESSION['user_id'],
                'plan_id' => $planId,
                'transaction_id' => $transactionId,
                'sender_name' => $senderName,
                'sender_phone' => $senderPhone,
                'amount' => $amount,
                'method' => sanitize($_POST['method'] ?? 'manual'),
            ]);

            NotificationModel::create($pdo, (int) $_SESSION['user_id'], 'Manual payment request submitted for plan upgrade.');
            flash('success', 'Payment request submitted for review.');
            header('Location: ?route=provider-dashboard');
            exit;
        }
    }
}

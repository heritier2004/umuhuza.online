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

            // Validate transaction ID format (Fix 2)
            // Mobile Money: 10-15 digits | Bank Transfer: 2 uppercase letters + 14 digits (e.g. BK20260610001234)
            $isValidMobileMoney  = (bool) preg_match('/^\d{10,15}$/', $transactionId);
            $isValidBankTransfer = (bool) preg_match('/^[A-Z]{2}\d{14}$/', $transactionId);

            if (!$isValidMobileMoney && !$isValidBankTransfer) {
                flash('error', 'Invalid transaction ID format. Use a mobile money ID (10–15 digits) or a bank reference such as BK20260610001234.');
                header('Location: ?route=provider-dashboard');
                exit;
            }

            // Reject duplicate transaction IDs
            if ($pdo) {
                $dupStmt = $pdo->prepare('SELECT id FROM payments WHERE transaction_id = ? LIMIT 1');
                $dupStmt->execute([$transactionId]);
                if ($dupStmt->fetch()) {
                    flash('error', 'This transaction ID has already been submitted. Please use a different transaction ID.');
                    header('Location: ?route=provider-dashboard');
                    exit;
                }
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

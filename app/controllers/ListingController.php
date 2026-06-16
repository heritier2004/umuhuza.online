<?php

class ListingController {
    public function create($pdo) {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF token
            if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
                flash('error', 'Security token invalid. Please try again.');
                header('Location: ?route=provider-dashboard');
                exit;
            }
            
            $province = sanitize($_POST['province'] ?? '');
            $district = sanitize($_POST['district'] ?? '');
            $sector = sanitize($_POST['sector'] ?? '');
            $cell = sanitize($_POST['cell'] ?? '');
            if ($province === '' || $district === '' || $sector === '' || $cell === '') {
                flash('error', 'Location is required for every listing.');
                header('Location: ?route=provider-dashboard');
                exit;
            }
            // ---- Listing plan enforcement ----
            $user = currentUser();
            $plan = Plan::currentForUser($pdo, (int) $user['id']);
            // Determine weekly listing count for the user
            $stmtCount = $pdo->prepare('SELECT COUNT(*) FROM listings WHERE user_id = ? AND YEARWEEK(created_at,1) = YEARWEEK(CURDATE(),1)');
            $stmtCount->execute([$user['id']]);
            $weeklyCount = (int) $stmtCount->fetchColumn();
            // Determine limit from plan (null means unlimited)
            $limit = $plan['listing_limit'] ?? null;
            if ($limit !== null && $weeklyCount >= $limit) {
                $errorMsg = 'You have reached your weekly listing limit. Upgrade your plan for more listings.';
                flash('error', $errorMsg);
                header('Location: ?route=provider-dashboard');
                exit;
            }
            try {
                // Begin transaction
                $pdo->beginTransaction();
                // Handle image upload
                $imagePath = handleUpload($_FILES['image'] ?? null);
                // Insert listing with correct plan ID
                $listingId = Listing::create($pdo, [
                    'user_id' => $user['id'],
                    'category_id' => (int) ($_POST['category_id'] ?? 1),
                    'title' => sanitize($_POST['title'] ?? ''),
                    'description' => sanitize($_POST['description'] ?? ''),
                    'price' => (float) ($_POST['price'] ?? 0),
                    'province' => $province,
                    'district' => $district,
                    'sector' => $sector,
                    'cell' => $cell,
                    'plan_id' => $plan['id'] ?? null,
                ]);
                if ($listingId && $imagePath) {
                    $stmt = $pdo->prepare('INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)');
                    $stmt->execute([$listingId, $imagePath]);
                }
                // Commit transaction
                $pdo->commit();
                flash('success', 'Listing submitted for approval.');
                header('Location: ?route=provider-dashboard');
                exit;
            } catch (\Exception $e) {
                // Rollback on any failure
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                flash('error', $e->getMessage());
                header('Location: ?route=provider-dashboard');
                exit;
            }
        }
    }

    public function profile($pdo) {
        $listing = Listing::find($pdo, (int)($_GET['id'] ?? 0));
        $providerListings = $listing ? Listing::byUser($pdo, (int) $listing['user_id']) : [];
        return compact('listing', 'providerListings');
    }
}

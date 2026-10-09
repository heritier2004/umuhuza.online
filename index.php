<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.gc_maxlifetime', 86400); // 24-hour session lifetime
    session_start();
    // Load translation helper early
    require_once __DIR__ . '/app/helpers/i18n.php';
}

require_once __DIR__ . '/app/config/constants.php';

// Ensure public/uploads exists with write permissions on startup
$appUploadsDir = __DIR__ . '/public/uploads';
if (!is_dir($appUploadsDir)) {
    $prevUmask = @umask(0);
    @mkdir($appUploadsDir, 0777, true);
    @chmod($appUploadsDir, 0777);
    @umask($prevUmask);
} elseif (!is_writable($appUploadsDir)) {
    @chmod($appUploadsDir, 0777);
}

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/helpers/security.php';
generateCsrfToken();
require_once __DIR__ . '/app/helpers/functions.php';
require_once __DIR__ . '/app/helpers/location.php';
require_once __DIR__ . '/app/helpers/ranking.php';
require_once __DIR__ . '/app/helpers/upload.php';
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/helpers/subscriptions.php';
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/ServiceArea.php';
require_once __DIR__ . '/app/models/Listing.php';
require_once __DIR__ . '/app/models/Request.php';
require_once __DIR__ . '/app/models/Plan.php';
require_once __DIR__ . '/app/models/Payment.php';
require_once __DIR__ . '/app/models/Notification.php';
require_once __DIR__ . '/app/models/Location.php';
require_once __DIR__ . '/app/models/AdminLog.php';
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/ListingController.php';
require_once __DIR__ . '/app/controllers/RequestController.php';
require_once __DIR__ . '/app/controllers/PaymentController.php';
require_once __DIR__ . '/app/controllers/NotificationController.php';
require_once __DIR__ . '/app/controllers/AdminController.php';
require_once __DIR__ . '/app/controllers/UserController.php';

$route = $_GET['route'] ?? 'home';
if (!empty($_GET['auto_location'])) {
    $parts = array_values(array_filter(array_map('trim', explode('/', $_GET['auto_location']))));
    $_SESSION['current_province'] = $parts[0] ?? ($_SESSION['current_province'] ?? '');
    $_SESSION['current_district'] = $parts[1] ?? ($_SESSION['current_district'] ?? '');
    $_SESSION['current_sector'] = $parts[2] ?? ($_SESSION['current_sector'] ?? '');
    $_SESSION['current_cell'] = $parts[3] ?? ($_SESSION['current_cell'] ?? '');
}

if ($route === 'logout') {
    logout();
}

if ($route === 'register-submit') {
    if (!checkRateLimit('register', 3, 600)) {
        flash('error', 'Too many registration attempts. Please wait 10 minutes before trying again.');
        header('Location: ?route=register');
        exit;
    }
    (new AuthController())->register($pdo);
}
if ($route === 'login-submit') {
    if (!checkRateLimit('login', 5, 120)) {
        flash('error', 'Too many login attempts. Please wait 2 minutes before trying again.');
        header('Location: ?route=login');
        exit;
    }
    (new AuthController())->login($pdo);
}
if ($route === 'forgot-password-submit') {
    (new AuthController())->forgotPassword($pdo);
}
if ($route === 'update-profile') {
    (new UserController())->updateProfile($pdo);
}
if ($route === 'create-listing') {
    if (!checkRateLimit('create_listing', 10, 3600)) {
        flash('error', 'Listing creation rate limit reached. Please wait before submitting more listings.');
        header('Location: ?route=provider-dashboard');
        exit;
    }
    (new ListingController())->create($pdo);
}
if ($route === 'submit-request') {
    if (!checkRateLimit('submit_request', 5, 300)) {
        flash('error', 'Too many request submissions. Please wait 5 minutes before submitting another request.');
        header('Location: ?route=home');
        exit;
    }
    (new RequestController())->submit($pdo);
}
if ($route === 'upgrade-plan') {
    if (!checkRateLimit('upgrade_plan', 5, 300)) {
        flash('error', 'Too many payment requests. Please wait a few minutes.');
        header('Location: ?route=provider-dashboard');
        exit;
    }
    (new PaymentController())->upgrade($pdo);
}

// Admin Route Guards & RLS
$adminRoutes = [
    'approve-payment', 'reject-payment', 'approve-listing', 'reject-listing',
    'toggle-featured-listing', 'approve-verification', 'reject-verification', 'toggle-user-status'
];
if (in_array($route, $adminRoutes, true)) {
    requireAdmin();
}

if ($route === 'approve-payment') {
    (new AdminController())->approvePayment($pdo, (int) ($_GET['payment_id'] ?? 0));
}
if ($route === 'reject-payment') {
    (new AdminController())->rejectPayment($pdo, (int) ($_GET['payment_id'] ?? 0));
}
if ($route === 'approve-listing') {
    (new AdminController())->approveListing($pdo, (int) ($_GET['id'] ?? 0));
}
if ($route === 'reject-listing') {
    (new AdminController())->rejectListing($pdo, (int) ($_GET['id'] ?? 0));
}
if ($route === 'toggle-featured-listing') {
    (new AdminController())->toggleListingFeatured($pdo, (int) ($_GET['id'] ?? 0));
}
if ($route === 'approve-verification') {
    (new AdminController())->approveVerification($pdo, (int) ($_GET['id'] ?? 0));
}
if ($route === 'reject-verification') {
    (new AdminController())->rejectVerification($pdo, (int) ($_GET['id'] ?? 0));
}
if ($route === 'toggle-user-status') {
    (new AdminController())->toggleUserStatus($pdo, (int) ($_GET['id'] ?? 0));
}

if ($route === 'api-notifications') {
    header('Content-Type: application/json');
    if (!isLoggedIn() || !$pdo) {
        echo json_encode(['unread' => 0, 'notifications' => []]);
        exit;
    }
    $userId = (int) $_SESSION['user_id'];
    $unread = NotificationModel::unreadCount($pdo, $userId);
    
    // Fetch notifications
    $notifications = NotificationModel::all($pdo, $userId);
    echo json_encode(['unread' => $unread, 'notifications' => $notifications]);
    exit;
}

if ($route === 'api-locations') {
    header('Content-Type: application/json');
    if (!$pdo) {
        echo json_encode([]);
        exit;
    }
    require_once __DIR__ . '/app/models/Location.php';
    $type = $_GET['type'] ?? 'provinces';
    $parentId = (int)($_GET['parent_id'] ?? 0);
    $data = [];
    if ($type === 'provinces') {
        $data = LocationModel::provinces($pdo);
    } elseif ($type === 'districts') {
        $data = LocationModel::districts($pdo, $parentId);
    } elseif ($type === 'sectors') {
        $data = LocationModel::sectors($pdo, $parentId);
    } elseif ($type === 'cells') {
        $data = LocationModel::cells($pdo, $parentId);
    }
    echo json_encode($data);
    exit;
}

if ($route === 'api-mark-notifications-read') {
    header('Content-Type: application/json');
    if (isLoggedIn() && $pdo) {
        NotificationModel::markAllRead($pdo, (int) $_SESSION['user_id']);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

if ($route === 'api-mark-read') {
    header('Content-Type: application/json');
    if (isLoggedIn() && $pdo) {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, (int)$_SESSION['user_id']]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

if ($route === 'push-subscribe') {
    header('Content-Type: application/json');
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn() && $pdo) {
        $json = file_get_contents('php://input');
        $sub = json_decode($json, true);
        if ($sub && isset($sub['endpoint'], $sub['keys']['p256dh'], $sub['keys']['auth'])) {
            $stmt = $pdo->prepare("INSERT INTO push_subscriptions (user_id, endpoint, p256dh, auth) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE p256dh = VALUES(p256dh), auth = VALUES(auth), user_id = VALUES(user_id)");
            $stmt->execute([
                $_SESSION['user_id'],
                $sub['endpoint'],
                $sub['keys']['p256dh'],
                $sub['keys']['auth']
            ]);
            echo json_encode(['success' => true]);
            exit;
        }
    }
    echo json_encode(['success' => false]);
    exit;
}

if ($route === 'api-archive') {
    header('Content-Type: application/json');
    if (isLoggedIn() && $pdo) {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare('UPDATE notifications SET is_archived = 1 WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, (int)$_SESSION['user_id']]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

$allListings = $pdo ? Listing::all($pdo) : [];
if ($pdo) {
    expireExpiredSubscriptions($pdo);
}
$ranked = rankListings($allListings);
if (empty($ranked)) {
    $ranked = [
        ['id' => 1, 'title' => 'Luxury 3-bedroom house in Kigali', 'description' => 'Modern family home with premium finishes, secure compound, and fast access to Kigali city center.', 'price' => 95000000, 'province' => 'Kigali', 'district' => 'Gasabo', 'rating' => 4.9, 'plan_id' => 3, 'plan_name' => 'Super', 'category_name' => 'Real Estate', 'featured' => 1, 'provider_name' => 'Kigali Realty Hub', 'image_url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=900&q=80'],
        ['id' => 2, 'title' => 'Fast TV repair and panel fixes', 'description' => 'Verified electronics repair team for TVs, panels, and small home appliances.', 'price' => 35000, 'province' => 'Kigali', 'district' => 'Nyarugenge', 'rating' => 4.8, 'plan_id' => 2, 'plan_name' => 'Premium', 'category_name' => 'Technical Service', 'featured' => 1, 'provider_name' => 'Urban Tech Rwanda', 'image_url' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=900&q=80'],
        ['id' => 3, 'title' => 'Trusted plumbing and leak repair', 'description' => 'Reliable plumbing support for leaks, pipe fitting, and urgent household maintenance.', 'price' => 45000, 'province' => 'Rwanda', 'district' => 'Muhanga', 'rating' => 4.7, 'plan_id' => 2, 'plan_name' => 'Premium', 'category_name' => 'Plumbing', 'featured' => 1, 'provider_name' => 'HomeFix Rwanda', 'image_url' => 'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=900&q=80'],
    ];
}
$listings = $ranked;
$requests = $pdo ? RequestModel::all($pdo) : [];
$featuredListings = array_filter($ranked, function ($item) {
    return ((int)($item['plan_id'] ?? 1) >= 2) || !empty($item['featured']);
});
$featuredListings = array_values($featuredListings);
if (empty($featuredListings)) {
    $featuredListings = array_slice($ranked, 0, 6);
}
$nearbyListings = array_slice($ranked, 0, 6);
$recentListings = array_slice($ranked, 0, 8);
$topProviders = [];
$zoneAgentCount = 0;
$zoneServiceCount = 0;
if ($pdo) {
    $providers = User::providers($pdo);
    foreach ($providers as $provider) {
        $providerListings = Listing::byUser($pdo, (int) $provider['id']);
        $providerKind = 'service';
        if (!empty($providerListings)) {
            $firstCategory = $providerListings[0]['category_name'] ?? '';
            if (stripos($firstCategory, 'Real Estate') !== false || stripos($firstCategory, 'Property') !== false) {
                $providerKind = 'agent';
            }
        }
        if ($providerKind === 'agent') {
            $zoneAgentCount++;
        } else {
            $zoneServiceCount++;
        }
        $avgRating = count($providerListings) > 0 ? round(array_sum(array_column($providerListings, 'rating')) / count($providerListings), 1) : 4.6;
        $topProviders[] = [
            'id' => $provider['id'],
            'full_name' => $provider['full_name'],
            'username' => $provider['username'],
            'phone' => $provider['phone'],
            'whatsapp' => $provider['whatsapp'],
            'rating' => $avgRating,
            'service_category' => !empty($provider['service_category']) ? $provider['service_category'] : ($providerListings[0]['category_name'] ?? 'General Service'),
            'distance' => sprintf('%.1f km', 1.0 + (count($topProviders) * 0.3)),
            'provider_kind' => $providerKind,
            'province' => $providerListings[0]['province'] ?? $provider['province'] ?? '',
            'district' => $providerListings[0]['district'] ?? $provider['district'] ?? '',
            'profile_image' => $provider['profile_image'] ?? null,
        ];
    }
}
if (empty($topProviders)) {
    $topProviders = [
        ['full_name' => 'Kigali Home Fix', 'username' => 'homefix', 'phone' => '+250788367073', 'whatsapp' => '+250788367073', 'rating' => 4.9, 'service_category' => 'Plumbing & Repairs', 'distance' => '1.2 km', 'provider_kind' => 'service', 'province' => 'Kigali', 'district' => 'Gasabo'],
        ['full_name' => 'Urban Tech Rwanda', 'username' => 'urbantech', 'phone' => '+250788367073', 'whatsapp' => '+250788367073', 'rating' => 4.8, 'service_category' => 'TV & Electronics', 'distance' => '2.1 km', 'provider_kind' => 'service', 'province' => 'Kigali', 'district' => 'Nyarugenge'],
        ['full_name' => 'Smart Realty Hub', 'username' => 'smartrealty', 'phone' => '+250788367073', 'whatsapp' => '+250788367073', 'rating' => 4.7, 'service_category' => 'Real Estate', 'distance' => '3.0 km', 'provider_kind' => 'agent', 'province' => 'Kigali', 'district' => 'Kicukiro'],
    ];
    $zoneAgentCount = 1;
    $zoneServiceCount = 2;
}
$heroSlides = [];
if (!empty($featuredListings)) {
    foreach (array_slice($featuredListings, 0, 5) as $item) {
        $heroSlides[] = [
            'title' => $item['title'],
            'location' => trim(($item['province'] ?? '') . ' / ' . ($item['district'] ?? '')),
            'rating' => (float)($item['rating'] ?? 4.5),
            'badge' => $item['plan_name'] ?? 'Featured',
            'category' => $item['category_name'] ?? 'Marketplace',
            'phone' => $item['phone'] ?? '+250788367073',
            'whatsapp' => $item['whatsapp'] ?? '+250788367073',
            'image' => listingCoverUrl($item),
        ];
    }
}
if (empty($heroSlides)) {
    $heroSlides = [
        ['title' => 'Featured house in Kigali', 'location' => 'Kigali / Gasabo', 'rating' => 4.9, 'badge' => 'Premium', 'category' => 'Real Estate', 'phone' => '+250788367073', 'whatsapp' => '+250788367073', 'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=900&q=80'],
        ['title' => 'TV repair specialists', 'location' => 'Kigali / Nyarugenge', 'rating' => 4.8, 'badge' => 'Trending', 'category' => 'Technical Service', 'phone' => '+250788367073', 'whatsapp' => '+250788367073', 'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=900&q=80'],
        ['title' => 'Plumbing and maintenance', 'location' => 'Rwanda / Nationwide', 'rating' => 4.7, 'badge' => 'Popular', 'category' => 'Services', 'phone' => '+250788367073', 'whatsapp' => '+250788367073', 'image' => 'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=900&q=80'],
    ];
}
switch ($route) {
    case 'listings':
        $listings = $ranked;
        include __DIR__ . '/views/public/listings.php';
        break;
    case 'listing':
        $listing = $pdo ? Listing::find($pdo, (int)($_GET['id'] ?? 0)) : null;
        if (!$listing) { $listing = ['title' => 'Listing not found']; }
        $providerListings = $listing && $pdo ? Listing::byUser($pdo, (int) $listing['user_id']) : [];
        include __DIR__ . '/views/public/listing.php';
        break;
    case 'provider-profile':
        $profileData = (new ListingController())->profile($pdo);
        extract($profileData);
        include __DIR__ . '/views/public/provider-profile.php';
        break;
    case 'register':
        include __DIR__ . '/views/auth/register-wizard.php';
        break;
    case 'login':
        include __DIR__ . '/views/auth/login-simple.php';
        break;
    case 'forgot-password':
        include __DIR__ . '/views/auth/forgot-password.php';
        break;
    case 'provider-dashboard':
        $data = (new UserController())->providerDashboard($pdo);
        extract($data);
        include __DIR__ . '/views/provider/dashboard.php';
        break;
    case 'admin-dashboard':
        $data = (new AdminController())->dashboard($pdo);
        extract($data);
        include __DIR__ . '/views/admin/dashboard.php';
        break;
    case 'about':
        include __DIR__ . '/views/public/about.php';
        break;
    default:
        $listings = $ranked;
        include __DIR__ . '/views/public/home.php';
}

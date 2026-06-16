<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>UMUHUZA.ONLINE</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="public/assets/css/style.css?v=<?= urlencode(md5_file(__DIR__ . '/../../public/assets/css/style.css')) ?>" />
  <link rel="stylesheet" href="public/assets/css/design-system.css?v=<?= urlencode(md5_file(__DIR__ . '/../../public/assets/css/design-system.css')) ?>" />
  <link rel="stylesheet" href="public/assets/css/onboarding-premium.css?v=<?= filemtime(__DIR__ . '/../../public/assets/css/onboarding-premium.css') ?>" />
  <link rel="stylesheet" href="public/assets/css/wizard-ui.css?v=<?= filemtime(__DIR__ . '/../../public/assets/css/wizard-ui.css') ?>" />
  <link rel="stylesheet" href="public/assets/css/marketplace-ui.css?v=<?= filemtime(__DIR__ . '/../../public/assets/css/marketplace-ui.css') ?>" />
</head>
<body class="marketplace-body">
<header class="marketplace-header fixed-top shadow-sm">
  <nav class="navbar navbar-expand-lg container py-2">
    <?php $currentUser = isLoggedIn() ? currentUser() : null; ?>
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-primary me-3" href="?route=home">
      <img src="public/assets/logo.png" alt="UMUHUZA.ONLINE" style="height: 38px; width: auto; object-fit: contain;" />
      <span class="d-none d-sm-block">
        UMUHUZA.ONLINE
        <small>Local property & trusted services</small>
      </span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <form class="header-search-bar d-flex align-items-center mx-lg-4 my-3 my-lg-0" action="?route=listings" method="GET">
        <input type="hidden" name="route" value="listings" />
        <input class="form-control border-0" type="text" name="q" data-i18n-placeholder="search_placeholder" placeholder="Search homes, services, providers" aria-label="Search" />
        <button class="btn btn-primary btn-sm ms-2" type="submit" data-i18n="search_button">Search</button>
      </form>
      <div class="header-nav d-flex flex-wrap align-items-center gap-2 ms-auto">
        <div class="header-filter-group d-flex align-items-center gap-1 me-2" role="tablist" aria-label="Marketplace filters">
          <button type="button" class="header-filter-chip active" data-market-filter="all">All</button>
          <button type="button" class="header-filter-chip" data-market-filter="agent">Agents</button>
          <button type="button" class="header-filter-chip" data-market-filter="service">Service Providers</button>
        </div>
        <a class="nav-link" href="?route=listings&q=Real+Estate" data-i18n="real_estate">Real Estate</a>
        <a class="nav-link" href="?route=listings&q=Services" data-i18n="services">Services</a>
        <a class="nav-link" href="#requestModal" data-bs-toggle="modal" data-i18n="requests">Requests</a>
        <select id="langSwitcher" class="lang-switcher me-2" aria-label="Select language">
          <option value="en">English</option>
          <option value="rw">Kinyarwanda</option>
        </select>
        <a class="btn btn-outline-primary btn-sm d-none d-lg-inline-flex" href="?route=register" data-i18n="post_listing">Post Listing</a>
        <?php if (isLoggedIn()): $notificationCount = NotificationModel::unreadCount($pdo, (int) ($_SESSION['user_id'] ?? 0)); ?>
          <a id="notificationBell" class="btn btn-light btn-sm position-relative" href="?route=provider-dashboard" data-notification-count="<?= (int) $notificationCount ?>">
            <span data-i18n="alerts">Alerts</span>
            <?php if ($notificationCount > 0): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= (int) $notificationCount ?></span><?php endif; ?>
          </a>
          <a class="profile-avatar-wrapper" href="?route=provider-dashboard" title="Your profile">
            <?php if (!empty($currentUser['profile_image'])): ?>
              <img class="profile-avatar" src="<?= e($currentUser['profile_image']) ?>" alt="Profile photo" loading="lazy" />
            <?php else: ?>
              <span class="profile-avatar profile-avatar-fallback"><?= strtoupper(substr(($currentUser['full_name'] ?? $currentUser['username'] ?? 'PR'), 0, 2)) ?></span>
            <?php endif; ?>
          </a>
          <a class="btn btn-outline-secondary btn-sm" href="?route=logout" data-i18n="logout">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</header>

<?php 
$errorMsg = flash('error');
$successMsg = flash('success');
?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11; top: 80px;">
  <?php if ($errorMsg): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="min-width: 300px;">
      <?= e($errorMsg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if ($successMsg): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="min-width: 300px;">
      <?= e($successMsg) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
      (function() {
        const msg = <?= json_encode(strtolower($successMsg)) ?>;
        let sound = null;
        if (msg.includes('payment') || msg.includes('subscription') || msg.includes('activated')) {
          sound = 'success.wav';
        } else if (msg.includes('listing')) {
          sound = 'listing.wav';
        } else if (msg.includes('request') || msg.includes('lead')) {
          sound = 'request.wav';
        }
        if (sound) {
          try {
            const audio = new Audio('public/assets/audio/' + sound);
            audio.play().catch(e => console.log('Audio autoplay blocked or failed:', e));
          } catch(err) {
            console.error('Audio play error:', err);
          }
        }
      })();
    </script>
  <?php endif; ?>
</div>

<main class="pb-5">

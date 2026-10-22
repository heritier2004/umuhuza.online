<?php include __DIR__ . '/../layouts/header.php'; ?>
<script>document.body.classList.add('admin-exec-page');</script>
<link rel="stylesheet" href="public/assets/css/admin-dashboard.css?v=<?= urlencode(md5_file(__DIR__ . '/../../public/assets/css/admin-dashboard.css')) ?>" />

<?php
$unreadNotifs = array_filter($notifications ?? [], fn($n) => (int)$n['is_read'] === 0 && (int)$n['is_archived'] === 0);
$readNotifs = array_filter($notifications ?? [], fn($n) => (int)$n['is_read'] === 1 && (int)$n['is_archived'] === 0);
$archivedNotifs = array_filter($notifications ?? [], fn($n) => (int)$n['is_archived'] === 1);

$userListingsCount = count($listings ?? []);
$userMatchedCount = count($matchedRequests ?? []);
$userPlanLimit = (int)($plan['listing_limit'] ?? 5);
$quotaPercent = $userPlanLimit > 0 ? min(100, (int)round(($userListingsCount / $userPlanLimit) * 100)) : 0;
$activePlanId = (int)($plan['id'] ?? 1);
?>

<!-- ===== TOP NAVIGATION ===================================== -->
<header class="ad-topnav" role="banner">
  <div class="ad-topnav-left">
    <div class="ad-brand">
      <div class="ad-brand-icon">P</div>
      <div class="ad-brand-text">
        <strong>UMUHUZA.ONLINE</strong>
        <span><?php echo __('provider_hub'); ?></span>
      </div>
    </div>
  </div>

  <div class="ad-topnav-center">
    <div class="ad-search-wrap">
      <svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" placeholder="Search leads, listings, or payments..." aria-label="Search dashboard">
    </div>
  </div>

  <div class="ad-topnav-right">
    <button class="ad-topnav-icon-btn d-lg-none" id="adMenuToggle" aria-label="Toggle Menu">
      <svg class="ad-svg-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>
    <a href="?route=home" class="ad-topnav-icon-btn" title="View Marketplace">
      <svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
    </a>
    <div class="ad-topnav-icon-btn" data-ad-view="notifications">
      <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
      <span class="ad-notif-badge" style="<?= count($unreadNotifs) > 0 ? '' : 'display:none;' ?>"><?= count($unreadNotifs) ?></span>
    </div>
    <div class="ad-user-chip" data-ad-view="settings">
      <div class="ad-user-avatar"><?= strtoupper(substr(($user['full_name'] ?? 'PR'), 0, 2)) ?></div>
      <span class="d-none d-sm-inline"><?= e($user['full_name'] ?? 'Provider') ?></span>
    </div>
  </div>
</header>

<div class="ad-overlay" id="adOverlay"></div>

<div class="ad-layout">
  
  <!-- ===== SIDEBAR ===================================== -->
  <aside class="ad-sidebar" id="adSidebar">
    <div class="ad-sidebar-header">
      <div class="ad-sidebar-profile">
        <div class="ad-profile-avatar"><?= strtoupper(substr(($user['full_name'] ?? 'PR'), 0, 2)) ?></div>
        <div class="ad-profile-info">
          <strong><?= e($user['full_name'] ?? 'Provider') ?></strong>
          <small><?= e($user['username'] ?? '') ?></small>
          <div class="ad-profile-badge"><svg style="font-size:0.5rem;" class="ad-svg-icon" viewBox="0 0 24 24" width="8" height="8" fill="currentColor"><circle cx="12" cy="12" r="10"></circle></svg> <?= e($plan['name'] ?? 'Free') ?> Plan</div>
        </div>
      </div>
    </div>
    
    <nav class="ad-sidebar-nav">
      <span class="ad-nav-section-label">Main Workspace</span>
      
      <!-- We use 'executive' so the admin JS defaults to this view on load -->
      <a class="ad-nav-item active" data-ad-view="executive">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        <span class="ad-nav-label">Overview</span>
      </a>
      
      <a class="ad-nav-item" data-ad-view="analytics">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
        <span class="ad-nav-label">Analytics</span>
      </a>
      
      <span class="ad-nav-section-label">Listings & Leads</span>
      
      <a class="ad-nav-item" data-ad-view="create-listing">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
        <span class="ad-nav-label">Post Listing</span>
      </a>
      
      <a class="ad-nav-item" data-ad-view="recent-listings">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
        <span class="ad-nav-label">My Listings</span>
      </a>
      
      <a class="ad-nav-item" data-ad-view="matched-requests">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
        <span class="ad-nav-label">Matched Leads</span>
        <?php if ($userMatchedCount > 0): ?>
          <span class="ad-nav-badge"><?= $userMatchedCount ?></span>
        <?php endif; ?>
      </a>
      
      <span class="ad-nav-section-label">Account & Billing</span>
      
      <a class="ad-nav-item" data-ad-view="payments">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
        <span class="ad-nav-label">Plans & Upgrades</span>
      </a>
      
      <a class="ad-nav-item" data-ad-view="notifications">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        <span class="ad-nav-label">Notifications</span>
        <span class="ad-nav-badge ad-notif-badge" style="<?= count($unreadNotifs) > 0 ? '' : 'display:none;' ?>"><?= count($unreadNotifs) ?></span>
      </a>
      
      <a class="ad-nav-item" data-ad-view="settings">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        <span class="ad-nav-label">Profile Settings</span>
      </a>

      <div class="ad-divider"></div>
      
      <a href="?route=logout" class="ad-nav-item ad-nav-danger">
        <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        <span class="ad-nav-label">Log Out</span>
      </a>
    </nav>
    
    <div class="ad-sidebar-footer">
      <div class="ad-system-status-pill">
        <div class="ad-status-dot"></div>
        <span>Provider Status: Active</span>
      </div>
    </div>
  </aside>

  <!-- ===== MAIN CONTENT ===================================== -->
  <main class="ad-main" id="adMain">
    <div class="ad-content">
      
      <!-- PAGE HEADER -->
      <header class="ad-page-header">
        <div class="ad-page-title-wrap">
          <div class="ad-page-eyebrow" id="adPageEyebrow">Provider Workspace</div>
          <h1 class="ad-page-title" id="adPageTitle">Your Marketplace Dashboard</h1>
          <p class="ad-page-sub" id="adPageSub">Track your plan, publish fresh listings, and respond to new leads faster.</p>
        </div>
        <div class="ad-page-actions">
           <div class="ad-user-chip" style="cursor:default">
             <svg class="ad-svg-icon text-muted" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
             <span id="adClock" style="font-variant-numeric: tabular-nums;">--:--:--</span>
           </div>
           <a href="?route=listings" class="ad-btn ad-btn-primary">View Marketplace</a>
        </div>
      </header>

      <?php 
        $dashError = flash('error'); 
        $dashSuccess = flash('success'); 
      ?>
      <?php if ($dashError): ?>
        <div class="ad-alert ad-alert-danger" style="margin-bottom:20px;padding:14px 18px;background:rgba(220,38,38,0.1);color:#dc2626;border:1px solid rgba(220,38,38,0.25);border-radius:12px;font-weight:600;display:flex;align-items:center;gap:12px;font-size:0.95rem;">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <span><?= e($dashError) ?></span>
        </div>
      <?php endif; ?>
      <?php if ($dashSuccess): ?>
        <div class="ad-alert ad-alert-success" style="margin-bottom:20px;padding:14px 18px;background:rgba(22,163,74,0.1);color:#16a34a;border:1px solid rgba(22,163,74,0.25);border-radius:12px;font-weight:600;display:flex;align-items:center;gap:12px;font-size:0.95rem;">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
          <span><?= e($dashSuccess) ?></span>
        </div>
      <?php endif; ?>

      <!-- ==============================================
           TAB 1: OVERVIEW (Using 'executive' for JS load)
           ============================================== -->
      <section class="ad-view active" data-view="executive">
        <!-- Hero Banner -->
        <div class="ad-exec-hero">
          <div class="ad-hero-badge"><div class="dot"></div> Quota Tracking</div>
          <div class="ad-hero-eyebrow">Current Plan: <?= e(strtoupper($plan['name'] ?? 'FREE')) ?></div>
          <h2 class="ad-hero-title">Listing Limit: <?= $userPlanLimit ?></h2>
          <p class="ad-hero-sub">You have used <?= $userListingsCount ?> of your <?= $userPlanLimit ?> allowed listings this cycle.</p>
          <div class="ad-hero-stats">
             <div class="ad-hero-stat">
               <strong data-count="<?= (int)($remainingQuota ?? 0) ?>"><?= (int)($remainingQuota ?? 0) ?></strong>
               <small>Remaining Quota</small>
             </div>
             <div class="ad-hero-stat">
               <strong data-count="<?= $userMatchedCount ?>"><?= $userMatchedCount ?></strong>
               <small>Matched Leads</small>
             </div>
          </div>
        </div>

        <?php if (count($unreadNotifs) > 0): ?>
          <div class="ad-critical-bar">
            <svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <div>
              <strong>You have <?= count($unreadNotifs) ?> unread notification<?= count($unreadNotifs) > 1 ? 's' : '' ?></strong>
              <p>Client requests are waiting for your response.</p>
            </div>
            <div class="ad-critical-actions">
              <button class="ad-critical-chip" data-ad-view="notifications">View Notifications</button>
            </div>
          </div>
        <?php endif; ?>

        <!-- KPI Grid -->
        <div class="ad-kpi-grid">
           <article class="ad-kpi-card ad-kpi-blue">
             <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg></div>
             <div class="ad-kpi-label">Total Listings</div>
             <div class="ad-kpi-value" data-count="<?= $userListingsCount ?>"><?= $userListingsCount ?></div>
             <div class="ad-kpi-trend neutral"><span>Approved + pending</span></div>
           </article>
           
           <article class="ad-kpi-card ad-kpi-gold">
             <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg></div>
             <div class="ad-kpi-label">Matched Requests</div>
             <div class="ad-kpi-value" data-count="<?= $userMatchedCount ?>"><?= $userMatchedCount ?></div>
             <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> Inquiries in your area</div>
           </article>

           <article class="ad-kpi-card ad-kpi-green">
             <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
             <div class="ad-kpi-label">Account Health</div>
             <div class="ad-kpi-value">100%</div>
             <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <span>Verified Profile</span></div>
           </article>

           <article class="ad-kpi-card ad-kpi-purple">
             <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg></div>
             <div class="ad-kpi-label">Quota Usage</div>
             <div class="ad-kpi-value"><?= $quotaPercent ?>%</div>
             <div class="ad-kpi-trend neutral"><span><?= $userListingsCount ?>/<?= $userPlanLimit ?> Used</span></div>
           </article>
        </div>

        <div class="ad-grid-2-1">
          <!-- Recent Leads -->
          <?php if (!empty($matchedRequests)): ?>
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg> Latest Incoming Leads</h3>
                <div class="ad-panel-actions">
                  <button class="ad-btn ad-btn-xs ad-btn-outline" data-ad-view="matched-requests">View All</button>
                </div>
              </div>
              <div class="ad-panel-body p-0">
                <div class="ad-list">
                  <?php foreach (array_slice($matchedRequests, 0, 3) as $req):
                    $waPhone = preg_replace('/[^0-9]/', '', $req['whatsapp'] ?? $req['phone'] ?? '');
                    $waMsg = rawurlencode("Hello " . ($req['name'] ?? 'Client') . ", I saw your request on UMUHUZA.ONLINE: " . ($req['description'] ?? ''));
                    $matchLevel = (int)($req['match_level'] ?? 4);
                    $matchLabel = $matchLevel === 0 ? 'Cell Match' : ($matchLevel === 1 ? 'Sector Match' : ($matchLevel === 2 ? 'District Match' : 'Province Match'));
                  ?>
                  <div class="ad-list-item px-4">
                     <div class="ad-list-icon bg-success-subtle text-success"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></div>
                     <div class="ad-list-body">
                       <strong><?= e($req['name']) ?> <span class="ad-badge ad-badge-success ms-2"><?= $matchLabel ?></span></strong>
                       <small>📍 <?= e($req['province']) ?> / <?= e($req['district']) ?> <?= !empty($req['budget']) ? '· '.number_format((float)$req['budget']).' RWF' : '' ?></small>
                       <p class="small text-muted mt-1 mb-0 text-truncate" style="max-width: 400px;"><?= e($req['description']) ?></p>
                     </div>
                     <div class="ad-list-end d-flex gap-2">
                       <a href="tel:<?= e($req['phone']) ?>" class="ad-btn ad-btn-sm ad-btn-primary"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg></a>
                       <?php if (!empty($waPhone)): ?>
                         <a href="https://wa.me/<?= $waPhone ?>?text=<?= $waMsg ?>" class="ad-btn ad-btn-sm ad-btn-success" target="_blank"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg></a>
                       <?php endif; ?>
                     </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <!-- Quick Actions -->
          <div class="ad-panel">
            <div class="ad-panel-header"><h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 2.5l19 19"></path><path d="M2 12h2"></path><path d="M12 2v2"></path><path d="M20 12h2"></path><path d="M12 20v2"></path><path d="M4.9 4.9l1.4 1.4"></path><path d="M17.7 17.7l1.4 1.4"></path><path d="M17.7 6.3l1.4-1.4"></path><path d="M4.9 19.1l1.4-1.4"></path></svg> Quick Actions</h3></div>
            <div class="ad-panel-body">
              <div class="d-flex flex-column gap-2">
                <button class="ad-btn ad-btn-primary w-100 justify-content-center" data-ad-view="create-listing">
                  <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Post New Listing
                </button>
                <button class="ad-btn ad-btn-ghost w-100 justify-content-center" data-ad-view="matched-requests">
                  <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg> View Matched Leads (<?= $userMatchedCount ?>)
                </button>
                <button class="ad-btn ad-btn-outline w-100 justify-content-center" data-ad-view="payments">
                  <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg> Upgrade Subscription
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ==============================================
           TAB 2: ANALYTICS
           ============================================== -->
      <section class="ad-view" data-view="analytics">
        <div class="ad-panel mb-4">
          <div class="ad-panel-header">
             <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg> Monthly Performance (<?= date('F Y') ?>)</h3>
          </div>
          <div class="ad-panel-body">
             
<div class="ad-grid-3">
  <!-- Free Plan -->
  <div class="ad-panel plan-card <?= $activePlanId === 1 ? 'border-primary' : '' ?>" style="<?= $activePlanId === 1 ? 'box-shadow: 0 0 0 2px var(--ad-primary);' : '' ?> cursor:pointer; transition:transform .2s, box-shadow .2s;" onclick="openPlanModal('free')">
    <div class="ad-panel-body text-center" style="display:flex;flex-direction:column;align-items:center;">
      <div style="font-size:2rem; margin-bottom:6px;">🆓</div>
      <h4 style="font-weight:700;margin:0;">Free Plan</h4>
      <div style="font-size:2rem; font-weight:800; margin:14px 0; color:#334155;">0 <small style="font-size:0.8rem; color:var(--ad-muted);">RWF/mo</small></div>
      <ul style="list-style:none; padding:0; font-size:0.82rem; color:var(--ad-muted); text-align:left; width:100%; margin:0 0 14px 0;">
        <li style="margin-bottom:6px;">✅ 5 Listings per week</li>
        <li style="margin-bottom:6px;">✅ Standard search ranking</li>
        <li style="margin-bottom:6px;">✅ Public profile page</li>
        <li style="margin-bottom:6px;">❌ No priority placement</li>
        <li>❌ No verified badge</li>
      </ul>
      <span style="font-size:0.78rem; color:#0284c7; font-weight:600;">👆 Click to see full details</span>
      <?php if ($activePlanId === 1): ?><div class="ad-badge ad-badge-primary" style="margin-top:12px;">Active Plan</div><?php endif; ?>
    </div>
  </div>
  <!-- Premium Plan -->
  <div class="ad-panel plan-card <?= $activePlanId === 2 ? 'border-warning' : '' ?>" style="<?= $activePlanId === 2 ? 'box-shadow: 0 0 0 2px var(--ad-warning);' : '' ?> cursor:pointer; transition:transform .2s, box-shadow .2s; position:relative;" onclick="openPlanModal('premium')">
    <div style="position:absolute; top:-10px; left:50%; transform:translateX(-50%); background:#f59e0b; color:#fff; font-size:0.72rem; font-weight:700; padding:3px 12px; border-radius:99px; white-space:nowrap;">⭐ MOST POPULAR</div>
    <div class="ad-panel-body text-center" style="display:flex;flex-direction:column;align-items:center; padding-top:22px;">
      <div style="font-size:2rem; margin-bottom:6px;">🥇</div>
      <h4 style="font-weight:700;margin:0;color:#d97706;">Premium Plan</h4>
      <div style="font-size:2rem; font-weight:800; margin:14px 0; color:#d97706;">3,000 <small style="font-size:0.8rem; color:var(--ad-muted);">RWF/mo</small></div>
      <ul style="list-style:none; padding:0; font-size:0.82rem; color:var(--ad-muted); text-align:left; width:100%; margin:0 0 14px 0;">
        <li style="margin-bottom:6px;">✅ 20 Listings per week</li>
        <li style="margin-bottom:6px;">✅ Priority search placement</li>
        <li style="margin-bottom:6px;">✅ Verified badge on profile</li>
        <li style="margin-bottom:6px;">✅ Lead notifications</li>
        <li>✅ Featured in search results</li>
      </ul>
      <span style="font-size:0.78rem; color:#0284c7; font-weight:600;">👆 Click to see full details</span>
      <?php if ($activePlanId === 2): ?><div class="ad-badge ad-badge-warning" style="margin-top:12px;">Active Plan</div><?php endif; ?>
    </div>
  </div>
  <!-- Super Plan -->
  <div class="ad-panel plan-card <?= $activePlanId === 3 ? 'border-success' : '' ?>" style="<?= $activePlanId === 3 ? 'box-shadow: 0 0 0 2px var(--ad-success);' : '' ?> cursor:pointer; transition:transform .2s, box-shadow .2s;" onclick="openPlanModal('super')">
    <div class="ad-panel-body text-center" style="display:flex;flex-direction:column;align-items:center;">
      <div style="font-size:2rem; margin-bottom:6px;">🚀</div>
      <h4 style="font-weight:700;margin:0;color:#16a34a;">Super Plan</h4>
      <div style="font-size:2rem; font-weight:800; margin:14px 0; color:#16a34a;">5,000 <small style="font-size:0.8rem; color:var(--ad-muted);">RWF/mo</small></div>
      <ul style="list-style:none; padding:0; font-size:0.82rem; color:var(--ad-muted); text-align:left; width:100%; margin:0 0 14px 0;">
        <li style="margin-bottom:6px;">✅ Unlimited listings</li>
        <li style="margin-bottom:6px;">✅ #1 Top ranking in search</li>
        <li style="margin-bottom:6px;">✅ Verified + Super badge</li>
        <li style="margin-bottom:6px;">✅ Unlimited leads</li>
        <li style="margin-bottom:6px;">✅ Featured on Home page</li>
        <li>✅ Priority support</li>
      </ul>
      <span style="font-size:0.78rem; color:#0284c7; font-weight:600;">👆 Click to see full details</span>
      <?php if ($activePlanId === 3): ?><div class="ad-badge ad-badge-success" style="margin-top:12px;">Active Plan</div><?php endif; ?>
    </div>
  </div>
</div>

<!-- Plan Details Modal Overlay -->
<div id="plan-modal-overlay" onclick="closePlanModal()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999;"></div>
<!-- Plan Details Modal -->
<div id="plan-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:18px; padding:32px; max-width:460px; width:90%; z-index:10000; box-shadow:0 20px 60px rgba(0,0,0,0.25); max-height:85vh; overflow-y:auto;">
  <button onclick="closePlanModal()" style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:1.4rem; cursor:pointer; color:#64748b; line-height:1;">&#10005;</button>
  <div id="plan-modal-content"></div>
</div>

<style>.plan-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,0.13) !important; }</style>

<script>
var planDetails = {
  free: {
    emoji: "🆓", title: "Free Plan", price: "0 RWF / month", color: "#334155",
    features: [
      { ok: true,  text: "5 listings published per week" },
      { ok: true,  text: "Standard ranking in search results" },
      { ok: true,  text: "Public provider profile page" },
      { ok: true,  text: "Client request form visible to clients" },
      { ok: false, text: "No priority placement in search" },
      { ok: false, text: "No Verified badge on profile" },
      { ok: false, text: "No featured listing on Home page" },
      { ok: false, text: "Leads are limited (capped weekly)" },
      { ok: false, text: "No dedicated customer support" }
    ],
    note: "Great for getting started. Upgrade anytime to grow faster.",
    planId: 1
  },
  premium: {
    emoji: "🥇", title: "Premium Plan", price: "3,000 RWF / month", color: "#d97706",
    features: [
      { ok: true, text: "20 listings published per week" },
      { ok: true, text: "Priority placement in search results" },
      { ok: true, text: "Verified badge displayed on your profile" },
      { ok: true, text: "Lead notifications via SMS & in-app" },
      { ok: true, text: "Featured in search results carousel" },
      { ok: true, text: "Public profile with contact button" },
      { ok: true, text: "Client requests sent directly to you" },
      { ok: false, text: "Not featured on the Home page hero" },
      { ok: false, text: "No #1 top ranking guarantee" }
    ],
    note: "Best value for growing agents and service providers.",
    planId: 2
  },
  super: {
    emoji: "🚀", title: "Super Plan", price: "5,000 RWF / month", color: "#16a34a",
    features: [
      { ok: true, text: "Unlimited listings — no weekly cap" },
      { ok: true, text: "#1 Top ranking in all search results" },
      { ok: true, text: "Verified badge + Super Provider tag" },
      { ok: true, text: "Unlimited leads and client contacts" },
      { ok: true, text: "Featured on the Home page hero section" },
      { ok: true, text: "Priority placement above all other plans" },
      { ok: true, text: "Direct WhatsApp & phone button on profile" },
      { ok: true, text: "Dedicated priority customer support" },
      { ok: true, text: "Analytics: views, clicks and lead stats" }
    ],
    note: "Maximum visibility. Dominate the marketplace and grow fast.",
    planId: 3
  }
};

function openPlanModal(planKey) {
  var plan = planDetails[planKey];
  var rows = plan.features.map(function(f) {
    return "<li style=\"margin-bottom:9px;display:flex;align-items:flex-start;gap:8px;\">" +
      "<span style=\"font-size:1rem;flex-shrink:0;\">" + (f.ok ? "✅" : "❌") + "</span>" +
      "<span style=\"color:" + (f.ok ? "#0f172a" : "#94a3b8") + ";font-size:0.9rem;\">" + f.text + "</span></li>";
  }).join("");

  document.getElementById("plan-modal-content").innerHTML =
    "<div style=\"text-align:center;margin-bottom:20px;\">" +
      "<div style=\"font-size:3rem;\">" + plan.emoji + "</div>" +
      "<h3 style=\"margin:8px 0 4px;font-weight:800;color:" + plan.color + ";\">" + plan.title + "</h3>" +
      "<div style=\"font-size:1.5rem;font-weight:800;color:" + plan.color + ";\">" + plan.price + "</div>" +
    "</div>" +
    "<ul style=\"list-style:none;padding:0;margin:0 0 18px 0;\">" + rows + "</ul>" +
    "<div style=\"background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;color:#166534;font-size:0.88rem;margin-bottom:20px;\">💡 " + plan.note + "</div>" +
    "<button onclick=\"closePlanModal();var sel=document.getElementById('plan-select-upgrade');sel.value=" + plan.planId + ";sel.dispatchEvent(new Event('change'));setTimeout(function(){var u=document.getElementById('ussd-box');if(u)u.scrollIntoView({behavior:'smooth'});},200);\" " +
      "style=\"width:100%;padding:13px;background:linear-gradient(135deg,#ffcc00,#ffa500);color:#1a1a1a;font-weight:700;font-size:1rem;border:none;border-radius:10px;cursor:pointer;\">" +
      "Choose This Plan & Pay 📲" +
    "</button>";

  document.getElementById("plan-modal-overlay").style.display = "block";
  document.getElementById("plan-modal").style.display = "block";
}

function closePlanModal() {
  document.getElementById("plan-modal-overlay").style.display = "none";
  document.getElementById("plan-modal").style.display = "none";
}
</script>

          </div>
        </div>
      </section>

      <!-- ==============================================
           TAB 3: CREATE LISTING
           ============================================== -->
      <section class="ad-view" data-view="create-listing">
        <div class="ad-panel mb-4">
          <div class="ad-panel-header">
             <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Create a new listing</h3>
          </div>
          <div class="ad-panel-body">
            <form class="ad-form-row" style="display:flex; flex-wrap:wrap; gap:16px;" method="POST" action="?route=create-listing" enctype="multipart/form-data" onsubmit="var b=this.querySelector('button[type=submit]'); if(b){ b.disabled=true; b.textContent='Uploading & Publishing...'; } return true;">
              <input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>">
              
              <div class="ad-form-group" style="width:100%;">
                <label>Listing title</label>
                <input class="ad-form-control" name="title" placeholder="Enter a clear listing title" required />
              </div>
              <div class="ad-form-group" style="flex:1; min-width: 250px;">
                <label>Service Category</label>
                <select class="ad-form-control" name="category_id" required>
                  <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                      <option value="<?= (int)$cat['id'] ?>"><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option value="1">Real Estate</option>
                    <option value="2">Technical Service</option>
                    <option value="4">Plumbing</option>
                    <option value="5">Electrical</option>
                  <?php endif; ?>
                </select>
              </div>
              <div class="ad-form-group" style="flex:1; min-width: 250px;">
                <label>Price</label>
                <input class="ad-form-control" name="price" placeholder="Amount in RWF" required />
              </div>
              <div class="ad-form-group" style="flex:1; min-width: 250px;">
                <label>Province</label>
                <input class="ad-form-control" name="province" value="<?= e($user['province'] ?? 'Kigali') ?>" placeholder="Kigali" />
              </div>
              <div class="ad-form-group" style="flex:1; min-width: 250px;">
                <label>District</label>
                <input class="ad-form-control" name="district" value="<?= e($user['district'] ?? 'Gasabo') ?>" placeholder="Gasabo" />
              </div>
              <div class="ad-form-group" style="flex:1; min-width: 250px;">
                <label>Sector</label>
                <input class="ad-form-control" name="sector" value="<?= e($user['sector'] ?? '') ?>" placeholder="e.g. Kacyiru" />
              </div>
              <div class="ad-form-group" style="flex:1; min-width: 250px;">
                <label>Cell</label>
                <input class="ad-form-control" name="cell" value="<?= e($user['cell'] ?? '') ?>" placeholder="e.g. Kamutwa" />
              </div>
              <div class="ad-form-group" style="width:100%;">
                <label>Description</label>
                <textarea class="ad-form-control" name="description" rows="4" placeholder="Describe what buyers or clients will get"></textarea>
              </div>
              <div class="ad-form-group" style="width:100%;">
                <label>Listing photos (1 to 5 photos)</label>
                <input class="ad-form-control" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp,image/gif" style="padding-top:12px;" />
                <small class="text-muted mt-1">Supported: JPG, PNG, WEBP, GIF. Photos per post: 1 to 5. Size: 5 KB – 5 MB per photo.</small>
              </div>
              <div style="width:100%; margin-top:16px;">
                <button class="ad-btn ad-btn-primary" type="submit">Publish listing</button>
              </div>
            </form>
          </div>
        </div>
      </section>

      <!-- ==============================================
           TAB 4: RECENT LISTINGS
           ============================================== -->
      <section class="ad-view" data-view="recent-listings">
        <div class="ad-panel mb-4">
          <div class="ad-panel-header">
             <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg> My Recent Listings</h3>
          </div>
          <div class="ad-panel-body p-0">
             <?php if (!empty($listings)): ?>
               <div class="ad-table-wrap">
                 <table class="ad-table">
                   <thead>
                     <tr>
                       <th>Title</th>
                       <th>Location</th>
                       <th>Price</th>
                       <th>Status</th>
                     </tr>
                   </thead>
                   <tbody>
                     <?php foreach ($listings as $item): ?>
                       <tr>
                         <td class="ad-table-name"><?= e($item['title']) ?></td>
                         <td class="ad-table-muted"><?= e($item['province']) ?> / <?= e($item['district']) ?></td>
                         <td><?= formatPrice($item['price'] ?? 0) ?></td>
                         <td>
                           <?php $status = $item['status'] ?? 'pending'; ?>
                           <span class="ad-badge <?= $status === 'approved' || $status === 'active' ? 'ad-badge-success' : 'ad-badge-warning' ?>">
                             <div class="ad-badge-dot"></div> <?= ucfirst(e($status)) ?>
                           </span>
                         </td>
                       </tr>
                     <?php endforeach; ?>
                   </tbody>
                 </table>
               </div>
             <?php else: ?>
               <div class="ad-empty">
                 <svg class="ad-svg-icon mb-3 text-muted" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                 <p>You have no listings yet.</p>
               </div>
             <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- ==============================================
           TAB 5: MATCHED LEADS
           ============================================== -->
      <section class="ad-view" data-view="matched-requests">
        <div class="ad-panel mb-4">
          <div class="ad-panel-header">
             <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg> Matched Client Leads & Inquiries</h3>
             <span class="ad-badge ad-badge-info"><?= count($matchedRequests) ?> Active Leads</span>
          </div>
          <div class="ad-panel-body" style="background:var(--ad-surface-2);">
             <?php if (($blockedLeadsCount ?? 0) > 0): ?>
               <div class="ad-critical-bar mb-4">
                 <svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                 <div>
                   <strong>Delivery Limit Reached</strong>
                   <p>You have <?= (int)$blockedLeadsCount ?> blocked leads. Upgrade plan to receive unlimited leads.</p>
                 </div>
                 <div class="ad-critical-actions">
                   <button class="ad-critical-chip" data-ad-view="payments">Upgrade Plan</button>
                 </div>
               </div>
             <?php endif; ?>

             <?php if (!empty($matchedRequests)): ?>
               <div class="d-flex flex-column" style="gap:16px;">
                 <?php foreach ($matchedRequests as $req): 
                    $matchLevel = (int)($req['match_level'] ?? 4);
                    $matchLabel = $matchLevel === 0 ? 'Exact Cell Match' : ($matchLevel === 1 ? 'Sector Match' : ($matchLevel === 2 ? 'District Match' : 'Province Match'));
                    $waPhone = preg_replace('/[^0-9]/', '', $req['whatsapp'] ?? $req['phone'] ?? '');
                    $waMessage = rawurlencode("Hello " . ($req['name'] ?? 'Client') . ", I saw your request on UMUHUZA.ONLINE: " . ($req['description'] ?? ''));
                 ?>
                 <div class="ad-panel shadow-sm" style="border: 1px solid var(--ad-border);">
                   <div class="ad-panel-body">
                      <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                         <div>
                            <span class="ad-badge ad-badge-muted me-2"><?= e(strtoupper($req['type'] ?? 'SERVICE REQUEST')) ?></span>
                            <span class="ad-badge ad-badge-success me-2"><?= $matchLabel ?></span>
                            <strong style="font-size: 1.1rem; display:block; margin-top:6px;"><?= e($req['name']) ?></strong>
                         </div>
                         <small style="color:var(--ad-muted-lt);"><?= e($req['created_at']) ?></small>
                      </div>
                      <div style="display:flex; gap:16px; margin:16px 0; background:var(--ad-surface-2); padding:16px; border-radius:6px; border:1px solid var(--ad-border);">
                         <div style="flex:1;">
                           <label style="font-size:0.75rem; color:var(--ad-muted); font-weight:700; display:block;">📍 Location:</label>
                           <span style="font-size:0.85rem;"><?= e($req['province']) ?> / <?= e($req['district']) ?> <?= !empty($req['sector']) ? '/ '.e($req['sector']) : '' ?></span>
                         </div>
                         <div style="flex:1;">
                           <label style="font-size:0.75rem; color:var(--ad-muted); font-weight:700; display:block;">💰 Budget:</label>
                           <?php if (!empty($req['budget'])): ?>
                             <span class="ad-badge ad-badge-success"><?= number_format((float)$req['budget']) ?> RWF</span>
                           <?php else: ?>
                             <span class="ad-badge ad-badge-muted">Negotiable</span>
                           <?php endif; ?>
                         </div>
                      </div>
                      <div style="margin-bottom:16px;">
                         <label style="font-size:0.75rem; color:var(--ad-muted); font-weight:700; display:block; margin-bottom:4px;">CLIENT REQUIREMENT DETAILS:</label>
                         <p style="font-size:0.85rem; margin:0; white-space: pre-line; line-height:1.6;"><?= e($req['description']) ?></p>
                      </div>
                      <div style="display:flex; gap:10px; padding-top:16px; border-top:1px solid var(--ad-border);">
                         <a href="tel:<?= e($req['phone']) ?>" class="ad-btn ad-btn-primary ad-btn-sm"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> Call Client (<?= e($req['phone']) ?>)</a>
                         <?php if (!empty($waPhone)): ?>
                           <a href="https://wa.me/<?= $waPhone ?>?text=<?= $waMessage ?>" class="ad-btn ad-btn-success ad-btn-sm" target="_blank"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg> WhatsApp Client</a>
                         <?php endif; ?>
                      </div>
                   </div>
                 </div>
                 <?php endforeach; ?>
               </div>
             <?php else: ?>
               <div class="ad-empty">
                 <svg class="ad-svg-icon mb-3 text-muted" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                 <p>No matching requests found yet.</p>
               </div>
             <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- ==============================================
           TAB 6: PAYMENTS & PLANS
           ============================================== -->
      <section class="ad-view" data-view="payments">
        <div class="ad-panel mb-4">
          <div class="ad-panel-header"><h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Upgrade Plan & Payments</h3></div>
          <div class="ad-panel-body">
             <div class="ad-grid-3">
               <!-- Free Plan -->
               <div class="ad-panel <?= $activePlanId === 1 ? 'border-primary' : '' ?>" style="<?= $activePlanId === 1 ? 'box-shadow: 0 0 0 2px var(--ad-primary);' : '' ?>">
                 <div class="ad-panel-body text-center" style="display:flex;flex-direction:column;align-items:center;">
                   <h4 style="font-weight:700;margin:0;">Free Plan</h4>
                   <div style="font-size:1.8rem; font-weight:800; margin:16px 0;">0 <small style="font-size:0.8rem; color:var(--ad-muted);">RWF/mo</small></div>
                   <ul style="list-style:none; padding:0; font-size:0.8rem; color:var(--ad-muted); text-align:left; width:100%; margin:0;">
                     <li style="margin-bottom:6px;"><svg class="ad-svg-icon text-success" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> 5 Listings quota</li>
                     <li><svg class="ad-svg-icon text-success" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Standard ranking</li>
                   </ul>
                   <?php if ($activePlanId === 1): ?><div class="ad-badge ad-badge-primary" style="margin-top:16px;">Active Plan</div><?php endif; ?>
                 </div>
               </div>
               <!-- Premium Plan -->
               <div class="ad-panel <?= $activePlanId === 2 ? 'border-warning' : '' ?>" style="<?= $activePlanId === 2 ? 'box-shadow: 0 0 0 2px var(--ad-warning);' : '' ?>">
                 <div class="ad-panel-body text-center" style="display:flex;flex-direction:column;align-items:center;">
                   <h4 style="font-weight:700;margin:0;color:var(--ad-warning);">Premium Plan</h4>
                   <div style="font-size:1.8rem; font-weight:800; margin:16px 0;">3,000 <small style="font-size:0.8rem; color:var(--ad-muted);">RWF/mo</small></div>
                   <ul style="list-style:none; padding:0; font-size:0.8rem; color:var(--ad-muted); text-align:left; width:100%; margin:0;">
                     <li style="margin-bottom:6px;"><svg class="ad-svg-icon text-success" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> 20 Listings quota</li>
                     <li><svg class="ad-svg-icon text-success" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Priority placement</li>
                   </ul>
                   <?php if ($activePlanId === 2): ?><div class="ad-badge ad-badge-warning" style="margin-top:16px;">Active Plan</div><?php endif; ?>
                 </div>
               </div>
               <!-- Super Plan -->
               <div class="ad-panel <?= $activePlanId === 3 ? 'border-success' : '' ?>" style="<?= $activePlanId === 3 ? 'box-shadow: 0 0 0 2px var(--ad-success);' : '' ?>">
                 <div class="ad-panel-body text-center" style="display:flex;flex-direction:column;align-items:center;">
                   <h4 style="font-weight:700;margin:0;color:var(--ad-success);">Super Plan</h4>
                   <div style="font-size:1.8rem; font-weight:800; margin:16px 0;">5,000 <small style="font-size:0.8rem; color:var(--ad-muted);">RWF/mo</small></div>
                   <ul style="list-style:none; padding:0; font-size:0.8rem; color:var(--ad-muted); text-align:left; width:100%; margin:0;">
                     <li style="margin-bottom:6px;"><svg class="ad-svg-icon text-success" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Unlimited listings</li>
                     <li><svg class="ad-svg-icon text-success" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Top #1 Ranking</li>
                   </ul>
                   <?php if ($activePlanId === 3): ?><div class="ad-badge ad-badge-success" style="margin-top:16px;">Active Plan</div><?php endif; ?>
                 </div>
               </div>
             </div>

             <!-- ===== MTN MoMo Payment Instructions (dynamic) ===== -->
             <div style="background-color: #f0f9ff; border: 1px solid #bae6fd; border-radius: 14px; padding: 22px; margin-bottom: 24px; width: 100%;">
               <h5 style="margin-top:0; margin-bottom:14px; font-weight:700; font-size:1.05rem; color:#0369a1; display:flex; align-items:center; gap:8px;">
                 <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#0369a1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                 MTN MoMo Payment Instructions
               </h5>

               <!-- Step-by-step guide -->
               <ol style="margin:0 0 18px 0; padding-left:20px; color:#0c4a6e; font-size:0.93rem; line-height:1.85;">
                 <li><strong>Select your plan</strong> below (Premium or Super).</li>
                 <li>Open your phone <strong>Dialer</strong> and dial the USSD code shown below.</li>
                 <li>Follow the MTN MoMo prompts and confirm the payment.</li>
                 <li>You will receive an <strong>SMS confirmation</strong> with a Transaction ID.</li>
                 <li>Fill in the form below with your <strong>Transaction ID, name, phone number, and amount</strong>.</li>
                 <li>Click <strong>"Submit Payment for Verification"</strong> — our team will activate your plan within 24 hours.</li>
               </ol>

               <!-- Dynamic USSD box (hidden until a paid plan is selected) -->
               <div id="ussd-box" style="display:none; background:#fff; border-radius:10px; padding:16px 20px; border:2px solid #bae6fd; margin-bottom:4px;">
                 <div style="margin-bottom:10px;">
                   <span style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.6px; color:#64748b; font-weight:600;">Receiver Name</span><br>
                   <span style="font-size:1rem; font-weight:700; color:#0f172a;">inshutiangeheritier</span>
                 </div>
                 <div style="margin-bottom:10px;">
                   <span style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.6px; color:#64748b; font-weight:600;">Dial this USSD code on your phone</span><br>
                   <span id="ussd-code" style="font-family:monospace; font-size:1.35rem; color:#0284c7; font-weight:800; letter-spacing:1px;">*182*1*1*0795032098*3000#</span>
                 </div>
                  <!-- Pay Now Button — opens phone dialer automatically -->
                  <a id="pay-now-btn" href="tel:*182*1*1*0795032098*3000%23"
                     style="display:inline-flex; align-items:center; gap:12px; background:linear-gradient(135deg,#ffcc00,#ffa500); color:#1a1a1a; font-weight:700; font-size:1rem; padding:13px 24px; border-radius:10px; text-decoration:none; margin-bottom:14px; width:100%; justify-content:center; box-shadow:0 4px 14px rgba(255,165,0,0.35);">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/New-mtn-logo.jpg/120px-New-mtn-logo.jpg"
                         alt="MTN" style="height:28px; width:auto; border-radius:4px; background:#fff; padding:2px 4px;" />
                    Pay Now via MTN MoMo
                  </a>
                  <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px 14px; color:#166534; font-size:0.88rem;">
                   💡 <strong>Tip:</strong> After dialing, select option 1 → confirm with your PIN. You'll get an SMS with your Transaction ID — copy it and paste it below.
                 </div>
               </div>

               <!-- Shown when Free plan is selected -->
               <div id="free-plan-notice" style="background:#fef9c3; border:1px solid #fde047; border-radius:10px; padding:14px 18px; color:#713f12; font-size:0.9rem;">
                 ℹ️ <strong>Free Plan selected.</strong> No payment needed. Choose Premium or Super plan to see payment details.
               </div>
             </div>

             <!-- ===== Upgrade Form ===== -->
             <form style="display:flex; flex-wrap:wrap; gap:16px;" method="POST" action="?route=upgrade-plan">
               <input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>">
               <div class="ad-form-group" style="width:100%;">
                 <label>Select Plan to Upgrade</label>
                 <select class="ad-form-control" name="plan_id" id="plan-select-upgrade">
                   <option value="1" <?= $activePlanId === 1 ? 'selected' : '' ?>>Free Plan (5 listings)</option>
                   <option value="2" <?= $activePlanId === 2 ? 'selected' : '' ?>>Premium Plan - 3,000 RWF (20 listings)</option>
                   <option value="3" <?= $activePlanId === 3 ? 'selected' : '' ?>>Super Plan - 5,000 RWF (Unlimited listings)</option>
                 </select>
               </div>
               <div class="ad-form-group" style="width:100%;">
                 <label>Transaction / Reference ID</label>
                 <input class="ad-form-control" name="transaction_id" placeholder="Copy from MTN SMS confirmation" />
               </div>
               <div class="ad-form-group" style="width:100%;">
                 <label>Sender Name</label>
                 <input class="ad-form-control" name="sender_name" placeholder="Your name as shown on MoMo" />
               </div>
               <div class="ad-form-group" style="width:100%;">
                 <label>Sender Phone</label>
                 <input class="ad-form-control" name="sender_phone" placeholder="MTN number you paid from" />
               </div>
               <div class="ad-form-group" style="width:100%;">
                 <label>Amount (RWF)</label>
                 <input class="ad-form-control" name="amount" id="amount-input-upgrade" placeholder="3000 or 5000" readonly style="background:#f8fafc; color:#0f172a; font-weight:600;" />
               </div>
               <div style="width:100%; margin-top:16px;">
                 <button class="ad-btn ad-btn-primary w-100 justify-content-center" type="submit">Submit Payment for Verification</button>
               </div>
             </form>

             <script>
             (function () {
               var select = document.getElementById('plan-select-upgrade');
               var ussdBox = document.getElementById('ussd-box');
               var freePlanNotice = document.getElementById('free-plan-notice');
               var ussdCode = document.getElementById('ussd-code');
               var amountInput = document.getElementById('amount-input-upgrade');

               var payBtn = document.getElementById('pay-now-btn');

               var planData = {
                 '1': { ussd: null, tel: '', amount: '' },
                 '2': { ussd: '*182*1*1*0795032098*3000#', tel: 'tel:*182*1*1*0795032098*3000%23', amount: '3000' },
                 '3': { ussd: '*182*1*1*0795032098*5000#', tel: 'tel:*182*1*1*0795032098*5000%23', amount: '5000' }
               };

               function updatePaymentUI() {
                 var val = select.value;
                 var data = planData[val] || planData['1'];
                 if (data.ussd) {
                   ussdCode.textContent = data.ussd;
                   if (payBtn) payBtn.href = data.tel;
                   amountInput.value = data.amount;
                   ussdBox.style.display = 'block';
                   freePlanNotice.style.display = 'none';
                 } else {
                   ussdBox.style.display = 'none';
                   freePlanNotice.style.display = 'block';
                   amountInput.value = '';
                 }
               }

               select.addEventListener('change', updatePaymentUI);
               updatePaymentUI(); // run on page load
             })();
             </script>

          </div>
        </div>
      </section>

      <!-- ==============================================
           TAB 7: NOTIFICATIONS
           ============================================== -->
      <section class="ad-view" data-view="notifications">
        <div class="ad-panel mb-4">
          <div class="ad-panel-header">
             <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg> Notifications Center</h3>
          </div>
          <div class="ad-panel-body p-0">
             <!-- Unread Notifications -->
             <?php if (!empty($unreadNotifs)): ?>
               <div class="ad-list">
                 <?php foreach ($unreadNotifs as $notif): ?>
                   <div class="ad-list-item px-4" style="cursor:pointer;" onclick="markNotifRead(<?= (int)$notif['id'] ?>)">
                      <div class="ad-list-icon bg-danger-subtle text-danger"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg></div>
                      <div class="ad-list-body">
                        <strong><?= e($notif['client_name'] ?? 'System Alert') ?></strong>
                        <p class="small text-dark mb-0"><?= e($notif['message']) ?></p>
                      </div>
                      <div class="ad-list-end" style="display:flex; flex-direction:column; align-items:flex-end;">
                        <small class="text-muted mb-2" style="font-size:0.7rem;"><?= e($notif['created_at']) ?></small>
                        <button class="ad-btn ad-btn-xs ad-btn-outline" onclick="archiveNotif(<?= (int)$notif['id'] ?>); event.stopPropagation();">Archive</button>
                      </div>
                   </div>
                 <?php endforeach; ?>
               </div>
             <?php endif; ?>
             
             <!-- Read Notifications -->
             <?php if (!empty($readNotifs)): ?>
               <div class="ad-list <?= !empty($unreadNotifs) ? 'border-top' : '' ?>">
                 <?php foreach ($readNotifs as $notif): ?>
                   <div class="ad-list-item px-4">
                      <div class="ad-list-icon" style="background:var(--ad-surface-2); color:var(--ad-muted);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><polyline points="3 9 12 15 21 9"></polyline></svg></div>
                      <div class="ad-list-body">
                        <strong><?= e($notif['client_name'] ?? 'System Alert') ?></strong>
                        <p class="small text-muted mb-0"><?= e($notif['message']) ?></p>
                      </div>
                      <div class="ad-list-end" style="display:flex; flex-direction:column; align-items:flex-end;">
                        <small class="text-muted mb-2" style="font-size:0.7rem;"><?= e($notif['created_at']) ?></small>
                        <button class="ad-btn ad-btn-xs ad-btn-ghost" onclick="archiveNotif(<?= (int)$notif['id'] ?>)">Archive</button>
                      </div>
                   </div>
                 <?php endforeach; ?>
               </div>
             <?php endif; ?>

             <?php if (empty($unreadNotifs) && empty($readNotifs)): ?>
               <div class="ad-empty">
                 <svg class="ad-svg-icon mb-3 text-muted" viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.73 21a2 2 0 0 1-3.46 0"></path><path d="M18.63 13A17.89 17.89 0 0 1 18 8"></path><path d="M6.26 6.26A5.86 5.86 0 0 0 6 8c0 7-3 9-3 9h14"></path><path d="M18 8a6 6 0 0 0-9.33-5"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                 <p>No notifications available.</p>
               </div>
             <?php endif; ?>
          </div>
        </div>
      </section>

      <!-- ==============================================
           TAB 8: PROFILE SETTINGS
           ============================================== -->
      <section class="ad-view" data-view="settings">
        <div class="ad-panel mb-4">
          <div class="ad-panel-header"><h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> Account & Profile Settings</h3></div>
          <div class="ad-panel-body">
             <form method="POST" action="?route=update-profile" enctype="multipart/form-data" style="display:flex; flex-wrap:wrap; gap:16px;">
               <input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>">
               
               <div class="ad-form-group" style="flex:1; min-width:300px;">
                 <label>Full Name / Business Name</label>
                 <input type="text" class="ad-form-control" name="full_name" value="<?= e($user['full_name'] ?? '') ?>" required />
               </div>
               <div class="ad-form-group" style="flex:1; min-width:300px;">
                 <label>Username</label>
                 <input type="text" class="ad-form-control" name="username" value="<?= e($user['username'] ?? '') ?>" required />
               </div>
               <div class="ad-form-group" style="flex:1; min-width:300px;">
                 <label>Phone Number</label>
                 <input type="tel" class="ad-form-control" name="phone" value="<?= e($user['phone'] ?? '') ?>" required />
               </div>
               <div class="ad-form-group" style="flex:1; min-width:300px;">
                 <label>WhatsApp Number</label>
                 <input type="tel" class="ad-form-control" name="whatsapp" value="<?= e($user['whatsapp'] ?? ($user['phone'] ?? '')) ?>" />
               </div>
               <div class="ad-form-group" style="flex:1; min-width:300px;">
                 <label>Email Address</label>
                 <input type="email" class="ad-form-control" name="email" value="<?= e($user['email'] ?? '') ?>" required />
               </div>
               <div class="ad-form-group" style="flex:1; min-width:300px;">
                 <label>Account Type</label>
                 <select class="ad-form-control" name="account_type">
                   <option value="agent" <?= ($user['account_type'] ?? 'agent') === 'agent' ? 'selected' : '' ?>>Real Estate Agent</option>
                   <option value="service" <?= ($user['account_type'] ?? '') === 'service' || ($user['account_type'] ?? '') === 'provider' ? 'selected' : '' ?>>Professional Service Provider</option>
                 </select>
               </div>
               
               <div class="ad-form-group" style="width:100%;">
                 <label style="color:var(--ad-primary);">Full Location Details</label>
                 <small style="display:block; color:var(--ad-muted); margin-bottom:8px;">Clients are matched with you based on your location. The more precise you are, the better the leads.</small>
                 <div style="display:flex; flex-wrap:wrap; gap:16px;">
                   <div style="flex:1; min-width:200px;">
                     <label style="font-size:0.75rem;">Province</label>
                     <input type="text" class="ad-form-control" name="province" value="<?= e($user['province'] ?? 'Kigali') ?>" />
                   </div>
                   <div style="flex:1; min-width:200px;">
                     <label style="font-size:0.75rem;">District</label>
                     <input type="text" class="ad-form-control" name="district" value="<?= e($user['district'] ?? 'Gasabo') ?>" />
                   </div>
                   <div style="flex:1; min-width:200px;">
                     <label style="font-size:0.75rem;">Sector</label>
                     <input type="text" class="ad-form-control" name="sector" value="<?= e($user['sector'] ?? '') ?>" placeholder="e.g. Kacyiru" />
                   </div>
                   <div style="flex:1; min-width:200px;">
                     <label style="font-size:0.75rem;">Cell (Akagari)</label>
                     <input type="text" class="ad-form-control" name="cell" value="<?= e($user['cell'] ?? '') ?>" placeholder="e.g. Kamutwa" />
                   </div>
                 </div>
               </div>

               <div style="width:100%; margin-top:16px;">
                 <button class="ad-btn ad-btn-primary" type="submit">Save Profile Changes</button>
               </div>
             </form>
          </div>
        </div>
      </section>

    </div><!-- /.ad-content -->
  </main><!-- /.ad-main -->
</div><!-- /.ad-layout -->

<script src="public/assets/js/admin-dashboard.js?v=<?= urlencode(md5_file(__DIR__ . '/../../public/assets/js/admin-dashboard.js')) ?>"></script>
<script>
// Notification Action Logic
function markNotifRead(id) {
    fetch('?route=api-mark-read&id=' + id).then(res => res.json()).then(data => { if(data.success) window.location.reload(); });
}
function archiveNotif(id) {
    fetch('?route=api-archive&id=' + id).then(res => res.json()).then(data => { if(data.success) window.location.reload(); });
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<script>document.body.classList.add('admin-exec-page');</script>
<link rel="stylesheet" href="public/assets/css/admin-dashboard.css?v=<?= urlencode(md5_file(__DIR__ . '/../../public/assets/css/admin-dashboard.css')) ?>" />
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- ===== MOBILE OVERLAY ===================================== -->
<div class="ad-overlay" id="adOverlay"></div>

<!-- ===== TOP NAVIGATION ===================================== -->
<header class="ad-topnav" role="banner">
  <div class="ad-topnav-left">
    <button class="ad-menu-toggle" id="adMenuToggle" aria-label="Toggle sidebar" type="button">
      <svg class="ad-svg-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>
    <a href="?route=home" class="ad-brand" aria-label="Rwanda Marketplace Home">
      <div class="ad-brand-mark">RM</div>
      <div class="ad-brand-text">
        <strong>Rwanda Marketplace</strong>
        <span>Admin Command Center</span>
      </div>
    </a>
  </div>

  <div class="ad-topnav-center">
    <div class="ad-search-wrap">
      <svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" placeholder="Search users, payments, logs…" aria-label="Global search" />
    </div>
  </div>

  <div class="ad-topnav-right">
    <span id="adClock" style="color:rgba(255,255,255,0.55);font-size:0.78rem;font-weight:600;font-variant-numeric:tabular-nums;letter-spacing:0.04em;"></span>

    <button class="ad-topnav-icon-btn" type="button" aria-label="Notifications" data-ad-view="notifications" onclick="document.querySelector('[data-ad-view=notifications]').click()">
      <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
      <span class="ad-notif-badge">5</span>
    </button>

    <a href="?route=home" class="ad-topnav-icon-btn" aria-label="View public site" title="View public site">
      <svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
    </a>

    <div class="ad-user-chip">
      <div class="ad-user-avatar"><?= strtoupper(substr(($_SESSION['full_name'] ?? 'AD'), 0, 2)) ?></div>
      <span><?= e($_SESSION['full_name'] ?? 'Administrator') ?></span>
    </div>
  </div>
</header>

<!-- ===== LAYOUT WRAPPER ===================================== -->
<div class="ad-layout">

  <!-- ===== SIDEBAR ========================================= -->
  <aside class="ad-sidebar" id="adSidebar" role="navigation" aria-label="Dashboard navigation">

    <div class="ad-sidebar-header">
      <div class="ad-sidebar-profile">
        <div class="ad-profile-avatar"><?= strtoupper(substr(($_SESSION['full_name'] ?? 'AD'), 0, 2)) ?></div>
        <div class="ad-profile-info">
          <strong><?= e($_SESSION['full_name'] ?? 'Administrator') ?></strong>
          <small>CEO / IT Executive</small>
          <div class="ad-profile-badge"><svg style="font-size:0.5rem;" class="ad-svg-icon" viewBox="0 0 24 24" width="8" height="8" fill="currentColor"><circle cx="12" cy="12" r="10"></circle></svg> Active</div>
        </div>
      </div>
    </div>

    <nav class="ad-sidebar-nav">

      <div class="ad-nav-section">
        <span class="ad-nav-section-label">Main</span>
        <button type="button" class="ad-nav-item active" data-ad-view="executive" id="nav-executive">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
          <span class="ad-nav-label">Executive Overview</span>
        </button>

        <button type="button" class="ad-nav-item ad-nav-expandable" data-ad-view="operations" id="nav-operations">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
          <span class="ad-nav-label">Operations Center</span>
          <svg class="ad-chevron" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
        <div class="ad-submenu">
          <button type="button" class="ad-submenu-item" data-ad-view="operations" data-sub-tab="users"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> Users</button>
          <button type="button" class="ad-submenu-item" data-ad-view="operations" data-sub-tab="listings"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1z"></path><path d="M3 7h18"></path><path d="M8 7v4"></path><path d="M16 7v4"></path></svg> Marketplace</button>
          <button type="button" class="ad-submenu-item" data-ad-view="operations" data-sub-tab="requests"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> Requests</button>
        </div>

        <button type="button" class="ad-nav-item ad-nav-expandable" data-ad-view="finance" id="nav-finance">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h14v4"></path><path d="M4 6v12a2 2 0 0 0 2 2h14v-4"></path><path d="M18 12a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h4v-6z"></path></svg>
          <span class="ad-nav-label">Finance &amp; Trust Center</span>
          <svg class="ad-chevron" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
        <div class="ad-submenu">
          <button type="button" class="ad-submenu-item" data-ad-view="finance" data-sub-tab="payments"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Payments</button>
          <button type="button" class="ad-submenu-item" data-ad-view="finance" data-sub-tab="subscriptions"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9z"></path><path d="M11 3 8 9l4 13 4-13-3-6"></path><path d="M2 9h20"></path></svg> Subscriptions</button>
          <button type="button" class="ad-submenu-item" data-ad-view="finance" data-sub-tab="trust"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> Trust &amp; Safety</button>
        </div>

        <button type="button" class="ad-nav-item ad-nav-expandable" data-ad-view="technology" id="nav-technology">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
          <span class="ad-nav-label">Technology &amp; System Center</span>
          <svg class="ad-chevron" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
        <div class="ad-submenu">
          <button type="button" class="ad-submenu-item" data-ad-view="technology" data-sub-tab="sys-health"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg> Infrastructure</button>
          <button type="button" class="ad-submenu-item" data-ad-view="technology" data-sub-tab="sys-security"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Security &amp; Activity</button>
          <button type="button" class="ad-submenu-item" data-ad-view="technology" data-sub-tab="sys-audit"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><line x1="9" y1="9" x2="15" y2="9"></line><line x1="9" y1="13" x2="15" y2="13"></line><line x1="9" y1="17" x2="14" y2="17"></line></svg> Audit Logs</button>
        </div>

        <button type="button" class="ad-nav-item" data-ad-view="notifications" id="nav-notifications">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
          <span class="ad-nav-label">Notifications</span>
          <span class="ad-nav-badge ad-notif-badge">5</span>
        </button>

        <button type="button" class="ad-nav-item" data-ad-view="settings" id="nav-settings">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
          <span class="ad-nav-label">Settings</span>
        </button>

      </div>

      <div class="ad-sidebar-footer">
        <a class="ad-nav-item ad-nav-danger" href="?route=logout">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
          <span class="ad-nav-label">Logout</span>
        </a>
      </div>
    </nav>
  </aside>
  <main class="ad-main" id="adMain">
    <div class="ad-content">

      <!-- PAGE HEADER -->
      <div class="ad-page-header">
        <div class="ad-page-title-wrap">
          <div class="ad-page-eyebrow" id="adPageEyebrow">Command Center</div>
          <h1 class="ad-page-title" id="adPageTitle">Executive Overview</h1>
          <p class="ad-page-sub" id="adPageSub">High-level business performance, growth indicators, and critical actions for the CEO.</p>
        </div>
        <div class="ad-page-actions">
          <a href="?route=home" class="ad-btn ad-btn-ghost ad-btn-sm"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg> Public Site</a>
          <button type="button" class="ad-btn ad-btn-primary ad-btn-sm"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg> Export Report</button>
        </div>
      </div>

      <!-- ==================================================
           VIEW 1 — EXECUTIVE OVERVIEW
           ================================================== -->
      <section class="ad-view active" data-view="executive" aria-label="Executive Overview">

        <!-- Hero Banner -->
        <div class="ad-exec-hero">
          <div class="ad-hero-badge"><div class="dot"></div> Live Platform Status</div>
          <div class="ad-hero-eyebrow">RWANDA MARKETPLACE · CEO DASHBOARD</div>
          <h2 class="ad-hero-title">Good evening, <?= e(explode(' ', $_SESSION['full_name'] ?? 'Administrator')[0]) ?> </h2>
          <p class="ad-hero-sub">Here is your complete business snapshot. Revenue is on track and platform health is excellent.</p>
          <div class="ad-hero-stats">
            <div class="ad-hero-stat">
              <strong><?= formatPrice($analytics['revenue'] ?? 0) ?></strong>
              <small>Total Revenue</small>
            </div>
            <div class="ad-hero-stat">
              <strong><?= (int)($analytics['totalUsers'] ?? count($users ?? [])) ?></strong>
              <small>Total Users</small>
            </div>
            <div class="ad-hero-stat">
              <strong><?= count($listings ?? []) ?></strong>
              <small>Active Listings</small>
            </div>
            <div class="ad-hero-stat">
              <strong><?= count($requests ?? []) ?></strong>
              <small>Service Requests</small>
            </div>
            <div class="ad-hero-stat">
              <strong>92%</strong>
              <small>Platform Health</small>
            </div>
          </div>
        </div>

        <!-- Critical Actions -->
        <?php
          $pendingPaymentsList = array_filter($payments ?? [], fn($p) => ($p['status'] ?? '') === 'pending');
          $pendingUsersList    = array_filter($users ?? [], fn($u) => ($u['status'] ?? '') === 'pending');
          $pendingListingsList = array_filter($listings ?? [], fn($l) => ($l['status'] ?? '') === 'pending');
          $pendingVerifList    = array_filter($verifications ?? [], fn($v) => ($v['status'] ?? 'pending') === 'pending');
          $criticalCount   = count($pendingPaymentsList) + count($pendingUsersList) + count($pendingListingsList);
        ?>
        <?php if ($criticalCount > 0): ?>
        <div class="ad-critical-bar">
          <svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <div>
            <strong><?= $criticalCount ?> Pending Actions Require Attention</strong>
            <p><?= count($pendingPaymentsList) ?> payments · <?= count($pendingUsersList) ?> verifications · <?= count($pendingListingsList) ?> listings awaiting approval</p>
          </div>
          <div class="ad-critical-actions">
            <?php if (count($pendingPaymentsList) > 0): ?><span class="ad-critical-chip" onclick="document.querySelector('[data-ad-view=finance]').click(); document.querySelector('[data-tab=fin-payments]').click();"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> <?= count($pendingPaymentsList) ?> Payments</span><?php endif; ?>
            <?php if (count($pendingUsersList) > 0 || count($pendingVerifList) > 0): ?><span class="ad-critical-chip" onclick="document.querySelector('[data-ad-view=finance]').click(); document.querySelector('[data-tab=fin-trust]').click();"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle><circle cx="18" cy="18" r="4"></circle></svg> <?= count($pendingUsersList) + count($pendingVerifList) ?> Verifications</span><?php endif; ?>
            <?php if (count($pendingListingsList) > 0): ?><span class="ad-critical-chip" onclick="document.querySelector('[data-ad-view=operations]').click(); document.querySelector('[data-tab=ops-listings]').click();"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1z"></path><path d="M3 7h18"></path><path d="M8 7v4"></path><path d="M16 7v4"></path></svg> <?= count($pendingListingsList) ?> Listings</span><?php endif; ?>
          </div>
        </div>

        <!-- CEO Quick Decision Panel -->
        <div class="ad-panel ad-spacer" style="border:1px solid rgba(220,38,38,0.22);background:linear-gradient(to bottom, #fff, var(--ad-surface-2));">
          <div class="ad-panel-header">
            <h3 class="ad-panel-title" style="color:var(--ad-danger);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2L22 9.5m-5-5L9.5 12m2.5-2.5L4.5 17m1.5 1.5L2.5 22"></path></svg> CEO Quick Approval Panel</h3>
            <span class="ad-badge ad-badge-danger">Requires Action</span>
          </div>
          <div class="ad-panel-body">
            <div class="ad-grid-2" style="margin-bottom:0;">
              <div>
                <h4 style="font-size:0.82rem;font-weight:700;color:var(--ad-navy);margin-bottom:10px;"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Pending Subscriptions (<?= count($pendingPaymentsList) ?>)</h4>
                <div class="ad-list">
                  <?php if (empty($pendingPaymentsList)): ?>
                    <div class="ad-empty" style="padding:15px;"><p style="font-size:0.78rem;">No pending payments.</p></div>
                  <?php else: ?>
                    <?php foreach (array_slice($pendingPaymentsList, 0, 3) as $p): ?>
                      <div class="ad-list-item" style="padding:8px 0;">
                        <div class="ad-list-body">
                          <strong style="font-size:0.8rem;"><?= e($p['user_name']) ?> (<?= e($p['plan_name']) ?>)</strong>
                          <small><?= formatPrice($p['amount']) ?> · Ref: <?= e($p['transaction_id'] ?? '—') ?></small>
                        </div>
                        <div style="display:flex;gap:4px;">
                          <a class="ad-btn ad-btn-success ad-btn-xs" href="?route=approve-payment&payment_id=<?= (int)$p['id'] ?>">Approve</a>
                          <a class="ad-btn ad-btn-danger ad-btn-xs" href="?route=reject-payment&payment_id=<?= (int)$p['id'] ?>">Reject</a>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
              <div>
                <h4 style="font-size:0.82rem;font-weight:700;color:var(--ad-navy);margin-bottom:10px;"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1z"></path><path d="M3 7h18"></path><path d="M8 7v4"></path><path d="M16 7v4"></path></svg> Pending Listings (<?= count($pendingListingsList) ?>)</h4>
                <div class="ad-list">
                  <?php if (empty($pendingListingsList)): ?>
                    <div class="ad-empty" style="padding:15px;"><p style="font-size:0.78rem;">No pending listings.</p></div>
                  <?php else: ?>
                    <?php foreach (array_slice($pendingListingsList, 0, 3) as $l): ?>
                      <div class="ad-list-item" style="padding:8px 0;">
                        <div class="ad-list-body">
                          <strong style="font-size:0.8rem;"><?= e($l['title']) ?></strong>
                          <small><?= e($l['provider_name'] ?? '—') ?> · <?= e($l['province']) ?></small>
                        </div>
                        <div style="display:flex;gap:4px;">
                          <a class="ad-btn ad-btn-success ad-btn-xs" href="?route=approve-listing&id=<?= (int)$l['id'] ?>">Approve</a>
                          <a class="ad-btn ad-btn-danger ad-btn-xs" href="?route=reject-listing&id=<?= (int)$l['id'] ?>">Reject</a>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- KPI Cards Row 1 -->
        <div class="ad-kpi-grid">
          <article class="ad-kpi-card ad-kpi-blue">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
            <div class="ad-kpi-label">Total Users</div>
            <div class="ad-kpi-value" data-count="<?= (int)($analytics['totalUsers'] ?? count($users ?? [])) ?>"><?= (int)($analytics['totalUsers'] ?? count($users ?? [])) ?></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> +18% <span>vs last month</span></div>
          </article>
          <article class="ad-kpi-card ad-kpi-gold">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="8" r="6"></circle><circle cx="16" cy="16" r="6"></circle><path d="M2 8a6 6 0 0 0 12 0"></path><path d="M10 16a6 6 0 0 0 12 0"></path></svg></div>
            <div class="ad-kpi-label">Total Revenue</div>
            <div class="ad-kpi-value"><?= formatPrice($analytics['revenue'] ?? 0) ?></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> +24% <span>vs last month</span></div>
          </article>
          <article class="ad-kpi-card ad-kpi-green">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="9"></line><line x1="9" y1="13" x2="15" y2="13"></line></svg></div>
            <div class="ad-kpi-label">Active Listings</div>
            <div class="ad-kpi-value" data-count="<?= count($listings ?? []) ?>"><?= count($listings ?? []) ?></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> +8% <span>new this week</span></div>
          </article>
          <article class="ad-kpi-card ad-kpi-purple">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg></div>
            <div class="ad-kpi-label">Service Requests</div>
            <div class="ad-kpi-value" data-count="<?= count($requests ?? []) ?>"><?= count($requests ?? []) ?></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> +12% <span>this week</span></div>
          </article>
        </div>

        <!-- Second KPI row -->
        <div class="ad-kpi-grid" style="margin-bottom:22px;">
          <article class="ad-kpi-card ad-kpi-orange">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 2h14M5 22h14M19 2l-7 7-7-7M5 22l7-7 7 7"></path></svg></div>
            <div class="ad-kpi-label">Pending Approvals</div>
            <div class="ad-kpi-value" data-count="<?= $criticalCount ?>"><?= $criticalCount ?></div>
            <div class="ad-kpi-trend <?= $criticalCount > 5 ? 'down' : 'neutral' ?>"><?php if ($criticalCount > 5): ?>
<svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
<?php else: ?>
<svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
<?php endif; ?> <?= $criticalCount > 5 ? 'Needs attention' : 'Within normal range' ?></div>
          </article>
          <article class="ad-kpi-card ad-kpi-sky">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle><path d="M12 11v4"></path></svg></div>
            <div class="ad-kpi-label">Verified Providers</div>
            <div class="ad-kpi-value" data-count="<?= count(array_filter($users ?? [], fn($u) => ($u['role'] ?? '') === 'provider')) ?>"><?= count(array_filter($users ?? [], fn($u) => ($u['role'] ?? '') === 'provider')) ?></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> +6% <span>active agents</span></div>
          </article>
          <article class="ad-kpi-card ad-kpi-green">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="7 13 12 18 20 10"></polyline><polyline points="2 13 7 18 13 12"></polyline></svg></div>
            <div class="ad-kpi-label">Completed Requests</div>
            <div class="ad-kpi-value" data-count="<?= count(array_filter($requests ?? [], fn($r) => ($r['status'] ?? '') === 'completed')) ?>"><?= count(array_filter($requests ?? [], fn($r) => ($r['status'] ?? '') === 'completed')) ?></div>
            <div class="ad-kpi-trend neutral"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="9" x2="19" y2="9"></line><line x1="5" y1="15" x2="19" y2="15"></line></svg> 82% <span>completion rate</span></div>
          </article>
          <article class="ad-kpi-card ad-kpi-blue">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg></div>
            <div class="ad-kpi-label">Platform Health Score</div>
            <div class="ad-kpi-value">92<small style="font-size:1rem;">/100</small></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> Excellent <span>all systems</span></div>
          </article>
        </div>

        <!-- Revenue Chart + Health Ring -->
        <div class="ad-grid-2-1" style="margin-bottom:18px;">
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg> Revenue Trend (Annual)</h3>
              <span class="ad-badge ad-badge-success">+24% YTD</span>
            </div>
            <div class="ad-panel-body">
              <div class="ad-chart-wrap ad-chart-lg"><canvas id="adRevenueChart"></canvas></div>
            </div>
          </div>
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 0 0-10 10c0 4.15 2.5 7.72 6.13 9.24"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg> Platform Health</h3>
            </div>
            <div class="ad-panel-body">
              <div class="ad-health-ring-wrap" style="flex-direction:column;align-items:center;gap:16px;margin-bottom:16px;">
                <div class="ad-health-ring">
                  <svg width="100" height="100" viewBox="0 0 100 100">
                    <circle class="ad-health-ring-bg" cx="50" cy="50" r="42"/>
                    <circle class="ad-health-ring-fill" cx="50" cy="50" r="42" data-score="92"/>
                  </svg>
                  <div class="ad-health-label">
                    <span class="ad-health-score">92</span>
                    <span class="ad-health-unit">/ 100</span>
                  </div>
                </div>
                <div class="ad-health-info" style="text-align:center;">
                  <strong>Excellent</strong>
                  <p>All core services operating within normal parameters</p>
                </div>
              </div>
              <div class="ad-growth-list">
                <div class="ad-growth-item">
                  <div class="ad-growth-item-icon" style="background:rgba(30,64,175,0.08);color:var(--ad-blue);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"></path></svg></div>
                  <div class="ad-growth-item-info">
                    <label>Database</label>
                    <div class="ad-progress-bar"><div class="ad-progress-fill" data-width="95%" style="--bar-color:var(--ad-success)"></div></div>
                  </div>
                  <span class="ad-text-success ad-fw-7" style="font-size:0.8rem;">95%</span>
                </div>
                <div class="ad-growth-item">
                  <div class="ad-growth-item-icon" style="background:rgba(217,119,6,0.08);color:var(--ad-warning);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect><line x1="2" y1="12" x2="22" y2="12"></line><line x1="6" y1="17" x2="6.01" y2="17"></line></svg></div>
                  <div class="ad-growth-item-info">
                    <label>Storage</label>
                    <div class="ad-progress-bar"><div class="ad-progress-fill" data-width="68%" style="--bar-color:var(--ad-warning)"></div></div>
                  </div>
                  <span class="ad-text-warning ad-fw-7" style="font-size:0.8rem;">68%</span>
                </div>
                <div class="ad-growth-item">
                  <div class="ad-growth-item-icon" style="background:rgba(22,163,74,0.08);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"></path><path d="M1.42 9a16 16 0 0 1 21.16 0"></path><path d="M8.53 16.1a6 6 0 0 1 6.95 0"></path><line x1="12" y1="20" x2="12.01" y2="20"></line></svg></div>
                  <div class="ad-growth-item-info">
                    <label>Uptime</label>
                    <div class="ad-progress-bar"><div class="ad-progress-fill" data-width="99.8%" style="--bar-color:var(--ad-success)"></div></div>
                  </div>
                  <span class="ad-text-success ad-fw-7" style="font-size:0.8rem;">99.8%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Growth Indicators + Recent Activity -->
        <div class="ad-grid-2" style="margin-bottom:18px;">
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22s8-12 8-15a4 4 0 0 0-8 0c0 3 8 15 8 15z"></path><path d="M22 2s-8 12-8 15a4 4 0 0 0 8 0c0-3-8-15-8-15z"></path></svg> Growth Indicators</h3>
              <span class="ad-badge ad-badge-info">This Month</span>
            </div>
            <div class="ad-panel-body">
              <div class="ad-sub-stat-row" style="grid-template-columns:repeat(2,1fr);">
                <div class="ad-sub-stat"><label>User Growth</label><strong>+18%</strong><small>vs last month</small></div>
                <div class="ad-sub-stat"><label>Revenue Growth</label><strong>+24%</strong><small>vs last month</small></div>
                <div class="ad-sub-stat"><label>Listing Rate</label><strong>+8%</strong><small>new listings/week</small></div>
                <div class="ad-sub-stat"><label>Completion Rate</label><strong>82%</strong><small>request success</small></div>
              </div>
              <div class="ad-growth-list" style="margin-top:8px;">
                <div class="ad-growth-item">
                  <div class="ad-growth-item-icon" style="background:rgba(30,64,175,0.08);color:var(--ad-blue);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
                  <div class="ad-growth-item-info"><label>User Acquisition</label><div class="ad-progress-bar"><div class="ad-progress-fill" data-width="72%" style="--bar-color:var(--ad-blue)"></div></div></div>
                  <span style="font-size:0.78rem;font-weight:700;color:var(--ad-blue);">72%</span>
                </div>
                <div class="ad-growth-item">
                  <div class="ad-growth-item-icon" style="background:rgba(212,160,23,0.10);color:var(--ad-gold);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="8" r="6"></circle><circle cx="16" cy="16" r="6"></circle><path d="M2 8a6 6 0 0 0 12 0"></path><path d="M10 16a6 6 0 0 0 12 0"></path></svg></div>
                  <div class="ad-growth-item-info"><label>Revenue Target</label><div class="ad-progress-bar"><div class="ad-progress-fill" data-width="85%" style="--bar-color:var(--ad-gold)"></div></div></div>
                  <span style="font-size:0.78rem;font-weight:700;color:var(--ad-gold);">85%</span>
                </div>
                <div class="ad-growth-item">
                  <div class="ad-growth-item-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></div>
                  <div class="ad-growth-item-info"><label>Provider Retention</label><div class="ad-progress-bar"><div class="ad-progress-fill" data-width="91%" style="--bar-color:var(--ad-success)"></div></div></div>
                  <span style="font-size:0.78rem;font-weight:700;color:var(--ad-success);">91%</span>
                </div>
              </div>
            </div>
          </div>

          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polyline></svg> Recent Activity</h3>
              <span class="ad-badge ad-badge-muted">Live Feed</span>
            </div>
            <div class="ad-panel-body">
              <div class="ad-list">
                <div class="ad-list-item">
                  <div class="ad-list-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="17" y1="11" x2="23" y2="11"></line></svg></div>
                  <div class="ad-list-body"><strong>New provider registered</strong><small>2 minutes ago</small></div>
                  <span class="ad-badge ad-badge-success">New</span>
                </div>
                <div class="ad-list-item">
                  <div class="ad-list-icon" style="background:var(--ad-warning-bg);color:var(--ad-warning);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></div>
                  <div class="ad-list-body"><strong>Payment submitted for approval</strong><small>14 minutes ago</small></div>
                  <span class="ad-badge ad-badge-warning">Pending</span>
                </div>
                <div class="ad-list-item">
                  <div class="ad-list-icon" style="background:var(--ad-info-bg);color:var(--ad-info);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></div>
                  <div class="ad-list-body"><strong>Property listing published</strong><small>32 minutes ago</small></div>
                  <span class="ad-badge ad-badge-info">Listed</span>
                </div>
                <div class="ad-list-item">
                  <div class="ad-list-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                  <div class="ad-list-body"><strong>Service request completed</strong><small>1 hour ago</small></div>
                  <span class="ad-badge ad-badge-success">Done</span>
                </div>
                <div class="ad-list-item">
                  <div class="ad-list-icon" style="background:var(--ad-danger-bg);color:var(--ad-danger);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                  <div class="ad-list-body"><strong>Fraud report submitted</strong><small>2 hours ago</small></div>
                  <span class="ad-badge ad-badge-danger">Alert</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

      <!-- ==================================================
           VIEW 2 — OPERATIONS CENTER
           ================================================== -->
      <section class="ad-view" data-view="operations" aria-label="Operations Center">

        <!-- Tab Controls -->
        <div class="ad-tabs ad-tab-group">
          <button type="button" class="ad-tab active" data-tab="ops-users"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> Users</button>
          <button type="button" class="ad-tab" data-tab="ops-listings"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1z"></path><path d="M3 7h18"></path><path d="M8 7v4"></path><path d="M16 7v4"></path></svg> Marketplace Listings</button>
          <button type="button" class="ad-tab" data-tab="ops-requests"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> Requests</button>
        </div>

        <!-- KPIs -->
        <div class="ad-kpi-grid" style="grid-template-columns:repeat(3,1fr); margin-bottom: 20px;">
          <article class="ad-kpi-card ad-kpi-blue">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></div>
            <div class="ad-kpi-label">Total Users</div>
            <div class="ad-kpi-value" data-count="<?= (int)($analytics['totalUsers'] ?? count($users ?? [])) ?>"><?= (int)($analytics['totalUsers'] ?? count($users ?? [])) ?></div>
          </article>
          <article class="ad-kpi-card ad-kpi-orange">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.2 8.4l-9.6 6.4-9.6-6.4"></path><path d="M3 16.2V21h18v-4.8"></path><path d="M12 2L2 8.7v10.3l10 5 10-5V8.7L12 2z"></path></svg></div>
            <div class="ad-kpi-label">Open Requests</div>
            <div class="ad-kpi-value" data-count="<?= count(array_filter($requests ?? [], fn($r) => ($r['status'] ?? '') === 'new')) ?>"><?= count(array_filter($requests ?? [], fn($r) => ($r['status'] ?? '') === 'new')) ?></div>
          </article>
          <article class="ad-kpi-card ad-kpi-green">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="9"></line><line x1="9" y1="13" x2="15" y2="13"></line></svg></div>
            <div class="ad-kpi-label">Active Listings</div>
            <div class="ad-kpi-value" data-count="<?= count($listings ?? []) ?>"><?= count($listings ?? []) ?></div>
          </article>
        </div>

        <!-- TAB PANEL: USERS -->
        <div class="ad-tab-panel" data-tab-panel="ops-users">
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> User Management</h3>
              <div class="ad-panel-actions">
                <span class="ad-badge ad-badge-muted">Customers · Providers · Admins</span>
              </div>
            </div>
            <div class="ad-panel-body">
              <div class="ad-sub-stat-row">
                <div class="ad-sub-stat">
                  <label>Customers</label>
                  <strong><?= count(array_filter($users ?? [], fn($u) => ($u['role'] ?? '') === 'client' || ($u['role'] ?? '') === 'user')) ?></strong>
                  <small>Registered buyers</small>
                </div>
                <div class="ad-sub-stat">
                  <label>Service Providers</label>
                  <strong><?= count(array_filter($users ?? [], fn($u) => ($u['role'] ?? '') === 'provider')) ?></strong>
                  <small>Active agents &amp; specialists</small>
                </div>
                <div class="ad-sub-stat">
                  <label>Administrators</label>
                  <strong><?= count(array_filter($users ?? [], fn($u) => ($u['role'] ?? '') === 'admin')) ?></strong>
                  <small>Platform administrators</small>
                </div>
              </div>
              <div class="ad-table-wrap" style="margin-top:12px;">
                <table class="ad-table">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Role</th>
                      <th>Email / Phone</th>
                      <th>Status</th>
                      <th>Joined</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($users)): ?>
                    <tr><td colspan="6"><div class="ad-empty"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><p>No user records found.</p></div></td></tr>
                    <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                      <td class="ad-table-name" data-label="Name"><?= e($user['full_name'] ?? $user['username'] ?? 'Unknown') ?></td>
                      <td data-label="Role">
                        <?php $role = $user['role'] ?? 'user'; ?>
                        <span class="ad-badge <?= $role === 'admin' ? 'ad-badge-purple' : ($role === 'provider' ? 'ad-badge-info' : 'ad-badge-muted') ?>"><?= ucfirst($role) ?></span>
                      </td>
                      <td class="ad-table-muted" data-label="Contact"><?= e($user['email'] ?? $user['phone'] ?? '—') ?></td>
                      <td data-label="Status">
                        <?php $st = $user['status'] ?? 'active'; ?>
                        <span class="ad-badge <?= $st === 'active' ? 'ad-badge-success' : ($st === 'pending' ? 'ad-badge-warning' : 'ad-badge-danger') ?>"><?= ucfirst($st) ?></span>
                      </td>
                      <td class="ad-table-muted" data-label="Joined"><?= isset($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '—' ?></td>
                      <td data-label="Action">
                        <a class="ad-btn <?= $st === 'active' ? 'ad-btn-danger' : 'ad-btn-success' ?> ad-btn-xs" href="?route=toggle-user-status&id=<?= (int)$user['id'] ?>">
                          <?= $st === 'active' ? 'Suspend' : 'Activate' ?>
                        </a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB PANEL: LISTINGS -->
        <div class="ad-tab-panel" data-tab-panel="ops-listings" style="display:none;">
          <div class="ad-panel ad-spacer">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1z"></path><path d="M3 7h18"></path><path d="M8 7v4"></path><path d="M16 7v4"></path></svg> Listing Management</h3>
              <span class="ad-badge ad-badge-muted"><?= count($listings ?? []) ?> Listings Total</span>
            </div>
            <div class="ad-panel-body">
              <div class="ad-table-wrap">
                <table class="ad-table">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Category</th>
                      <th>Location</th>
                      <th>Provider</th>
                      <th>Status</th>
                      <th>Plan</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($listings)): ?>
                    <tr><td colspan="7"><div class="ad-empty"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1z"></path><path d="M3 7h18"></path><path d="M8 7v4"></path><path d="M16 7v4"></path></svg><p>No listings found.</p></div></td></tr>
                    <?php else: ?>
                    <?php foreach ($listings as $item): ?>
                    <tr>
                      <td class="ad-table-name" data-label="Title"><?= e($item['title'] ?? 'Untitled Listing') ?></td>
                      <td data-label="Category"><span class="ad-badge ad-badge-muted"><?= e($item['category_name'] ?? 'General') ?></span></td>
                      <td class="ad-table-muted" data-label="Location"><?= e($item['district'] ?? 'Rwanda') ?></td>
                      <td class="ad-table-muted" data-label="Provider"><?= e($item['provider_name'] ?? '—') ?></td>
                      <td data-label="Status">
                        <?php $itemSt = $item['status'] ?? 'pending'; ?>
                        <span class="ad-badge <?= $itemSt === 'active' ? 'ad-badge-success' : ($itemSt === 'pending' ? 'ad-badge-warning' : 'ad-badge-danger') ?>"><?= ucfirst($itemSt) ?></span>
                      </td>
                      <td data-label="Plan">
                        <span class="ad-badge <?= (int)($item['plan_id'] ?? 1) >= 2 ? 'ad-badge-gold' : 'ad-badge-muted' ?>"><?= e($item['plan_name'] ?? 'Free') ?></span>
                      </td>
                      <td data-label="Actions">
                        <div style="display:flex;gap:4px;">
                          <?php if ($itemSt === 'pending'): ?>
                            <a class="ad-btn ad-btn-success ad-btn-xs" href="?route=approve-listing&id=<?= (int)$item['id'] ?>">Approve</a>
                            <a class="ad-btn ad-btn-danger ad-btn-xs" href="?route=reject-listing&id=<?= (int)$item['id'] ?>">Reject</a>
                          <?php else: ?>
                            <a class="ad-btn ad-btn-ghost ad-btn-xs" style="color:var(--ad-danger);" href="?route=reject-listing&id=<?= (int)$item['id'] ?>">Unapprove</a>
                          <?php endif; ?>
                          <a class="ad-btn ad-btn-outline ad-btn-xs" href="?route=toggle-featured-listing&id=<?= (int)$item['id'] ?>">
                            <?= (int)($item['plan_id'] ?? 1) >= 2 ? 'Free Plan' : 'Make Featured' ?>
                          </a>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB PANEL: REQUESTS -->
        <div class="ad-tab-panel" data-tab-panel="ops-requests" style="display:none;">
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> Request Routing &amp; Lead Analytics</h3>
            </div>
            <div class="ad-panel-body">
              <div class="ad-sub-stat-row">
                <div class="ad-sub-stat">
                  <label>Total Requests</label>
                  <strong><?= (int)($adminAnalytics['totalRequests'] ?? 0) ?></strong>
                  <small>Platform lifetime requests</small>
                </div>
                <div class="ad-sub-stat">
                  <label>Requests Delivered</label>
                  <strong><?= (int)($adminAnalytics['requestsDelivered'] ?? 0) ?></strong>
                  <small>Successfully matched &amp; sent</small>
                </div>
                <div class="ad-sub-stat">
                  <label>Requests Pending</label>
                  <strong><?= (int)($adminAnalytics['requestsPending'] ?? 0) ?></strong>
                  <small>Delayed free leads</small>
                </div>
                <div class="ad-sub-stat">
                  <label>Requests Expired</label>
                  <strong><?= (int)($adminAnalytics['requestsExpired'] ?? 0) ?></strong>
                  <small>Over 30 days old</small>
                </div>
              </div>
              
              <!-- Lead Delivery by Plan Tier -->
              <div style="margin-top:20px; padding:15px; background:var(--ad-surface-2); border-radius:8px;">
                <h4 style="font-size:0.88rem; font-weight:700; margin-bottom:12px; color:var(--ad-navy);">Leads Delivered by Subscription Tier</h4>
                <div class="ad-sub-stat-row" style="grid-template-columns: repeat(3, 1fr); gap:12px;">
                  <div class="ad-sub-stat">
                    <label>Free Plan Leads Sent</label>
                    <strong><?= (int)($adminAnalytics['freePlanLeadsSent'] ?? 0) ?></strong>
                    <small>Delivered immediately/delayed</small>
                  </div>
                  <div class="ad-sub-stat">
                    <label>Premium Leads Sent</label>
                    <strong><?= (int)($adminAnalytics['premiumLeadsSent'] ?? 0) ?></strong>
                    <small>Delivered immediately</small>
                  </div>
                  <div class="ad-sub-stat">
                    <label>Super Premium Leads Sent</label>
                    <strong><?= (int)($adminAnalytics['superPremiumLeadsSent'] ?? 0) ?></strong>
                    <small>Delivered first &amp; immediately</small>
                  </div>
                </div>
              </div>

              <!-- Proximity and Demand Areas -->
              <div class="ad-grid-2" style="margin-top:20px;">
                <div>
                  <h4 style="font-size:0.88rem; font-weight:700; margin-bottom:12px; color:var(--ad-navy);">Top Demand Areas (Province > District)</h4>
                  <div class="ad-list">
                    <?php if (empty($adminAnalytics['topDemandAreas'])): ?>
                      <div class="ad-empty"><p style="font-size:0.78rem;">No location records yet.</p></div>
                    <?php else: ?>
                      <?php foreach ($adminAnalytics['topDemandAreas'] as $area): ?>
                        <div class="ad-list-item" style="padding: 8px 0; border-bottom: 1px solid var(--ad-border-color);">
                          <div class="ad-list-body">
                            <strong><?= e($area['area']) ?></strong>
                          </div>
                          <strong><?= (int)$area['count'] ?> reqs</strong>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>

                <div>
                  <h4 style="font-size:0.88rem; font-weight:700; margin-bottom:12px; color:var(--ad-navy);">Top Demand Services (Category)</h4>
                  <div class="ad-list">
                    <?php if (empty($adminAnalytics['topDemandServices'])): ?>
                      <div class="ad-empty"><p style="font-size:0.78rem;">No service records yet.</p></div>
                    <?php else: ?>
                      <?php foreach ($adminAnalytics['topDemandServices'] as $service): ?>
                        <div class="ad-list-item" style="padding: 8px 0; border-bottom: 1px solid var(--ad-border-color);">
                          <div class="ad-list-body">
                            <strong><?= e(ucfirst($service['service'])) ?></strong>
                          </div>
                          <strong><?= (int)$service['count'] ?> reqs</strong>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <!-- Requests breakdown by category and province -->
              <div class="ad-grid-2" style="margin-top:20px;">
                <div>
                  <h4 style="font-size:0.88rem; font-weight:700; margin-bottom:12px; color:var(--ad-navy);">Requests by Province</h4>
                  <div class="ad-list">
                    <?php if (empty($adminAnalytics['requestsByProvince'])): ?>
                      <div class="ad-empty"><p style="font-size:0.78rem;">No province stats available.</p></div>
                    <?php else: ?>
                      <?php foreach ($adminAnalytics['requestsByProvince'] as $province => $count): ?>
                        <div class="ad-list-item" style="padding: 8px 0; border-bottom: 1px solid var(--ad-border-color);">
                          <div class="ad-list-body">
                            <strong><?= e($province) ?></strong>
                          </div>
                          <strong><?= (int)$count ?> reqs</strong>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>

                <div>
                  <h4 style="font-size:0.88rem; font-weight:700; margin-bottom:12px; color:var(--ad-navy);">Requests by Category</h4>
                  <div class="ad-list">
                    <?php if (empty($adminAnalytics['requestsByCategory'])): ?>
                      <div class="ad-empty"><p style="font-size:0.78rem;">No category stats available.</p></div>
                    <?php else: ?>
                      <?php foreach ($adminAnalytics['requestsByCategory'] as $category => $count): ?>
                        <div class="ad-list-item" style="padding: 8px 0; border-bottom: 1px solid var(--ad-border-color);">
                          <div class="ad-list-body">
                            <strong><?= e(ucfirst($category)) ?></strong>
                          </div>
                          <strong><?= (int)$count ?> reqs</strong>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <!-- Detailed Requests List -->
              <div style="margin-top:25px;">
                <h4 style="font-size:0.88rem; font-weight:700; margin-bottom:12px; color:var(--ad-navy);">All Private Leads / Requests</h4>
                <div class="ad-table-wrap">
                  <table class="ad-table">
                    <thead>
                      <tr>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Budget</th>
                        <th>Description</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($requests)): ?>
                        <tr><td colspan="6"><div class="ad-empty"><p>No requests found.</p></div></td></tr>
                      <?php else: ?>
                        <?php foreach ($requests as $req): ?>
                          <tr>
                            <td class="ad-table-name"><?= e($req['name'] ?? 'Unknown') ?></td>
                            <td><span class="ad-badge ad-badge-info"><?= e($req['type'] ?? 'General') ?></span></td>
                            <td class="ad-table-muted"><?= e($req['province']) ?> &gt; <?= e($req['district']) ?> &gt; <?= e($req['sector']) ?></td>
                            <td><strong><?= $req['budget'] ? formatPrice($req['budget']) : '—' ?></strong></td>
                            <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= e($req['description']) ?>"><?= e($req['description']) ?></td>
                            <td class="ad-table-muted"><?= isset($req['created_at']) ? date('d M Y H:i', strtotime($req['created_at'])) : '—' ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

      <!-- ==================================================
           VIEW 3 — FINANCE & TRUST CENTER
           ================================================== -->
      <section class="ad-view" data-view="finance" aria-label="Finance and Trust Center">

        <!-- Tab Controls -->
        <div class="ad-tabs ad-tab-group">
          <button type="button" class="ad-tab active" data-tab="fin-payments"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Payments</button>
          <button type="button" class="ad-tab" data-tab="fin-subscriptions"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9z"></path><path d="M11 3 8 9l4 13 4-13-3-6"></path><path d="M2 9h20"></path></svg> Subscriptions</button>
          <button type="button" class="ad-tab" data-tab="fin-trust"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> Trust &amp; Safety</button>
        </div>

        <!-- Finance KPIs -->
        <div class="ad-kpi-grid">
          <article class="ad-kpi-card ad-kpi-gold">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
            <div class="ad-kpi-label">Total Revenue</div>
            <div class="ad-kpi-value"><?= formatPrice($analytics['revenue'] ?? 0) ?></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> +24% <span>this month</span></div>
          </article>
          <article class="ad-kpi-card ad-kpi-orange">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></div>
            <div class="ad-kpi-label">Pending Payments</div>
            <div class="ad-kpi-value" data-count="<?= count(array_filter($payments ?? [], fn($p) => ($p['status'] ?? '') === 'pending')) ?>"><?= count(array_filter($payments ?? [], fn($p) => ($p['status'] ?? '') === 'pending')) ?></div>
            <div class="ad-kpi-trend <?= count(array_filter($payments ?? [], fn($p) => ($p['status'] ?? '') === 'pending')) > 3 ? 'down' : 'neutral' ?>"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> Awaiting approval</div>
          </article>
          <article class="ad-kpi-card ad-kpi-green">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
            <div class="ad-kpi-label">Approved Payments</div>
            <div class="ad-kpi-value" data-count="<?= count(array_filter($payments ?? [], fn($p) => ($p['status'] ?? '') === 'approved')) ?>"><?= count(array_filter($payments ?? [], fn($p) => ($p['status'] ?? '') === 'approved')) ?></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> Processing complete</div>
          </article>
          <article class="ad-kpi-card ad-kpi-blue">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9z"></path><path d="M11 3 8 9l4 13 4-13-3-6"></path><path d="M2 9h20"></path></svg></div>
            <div class="ad-kpi-label">Subscriptions</div>
            <div class="ad-kpi-value" data-count="<?= (int)($analytics['premiumUsers'] ?? 0) + (int)($analytics['superUsers'] ?? 0) ?>"><?= (int)($analytics['premiumUsers'] ?? 0) + (int)($analytics['superUsers'] ?? 0) ?></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> Premium + Super</div>
          </article>
        </div>

        <!-- TAB PANEL: PAYMENTS -->
        <div class="ad-tab-panel" data-tab-panel="fin-payments">
          <div class="ad-panel ad-spacer">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg> Payment Management</h3>
              <div class="ad-panel-actions">
                <span class="ad-badge ad-badge-warning"><?= count(array_filter($payments ?? [], fn($p) => ($p['status'] ?? '') === 'pending')) ?> Pending</span>
              </div>
            </div>
            <div class="ad-panel-body">
              <div class="ad-table-wrap">
                <table class="ad-table">
                  <thead>
                    <tr>
                      <th>User</th>
                      <th>Plan</th>
                      <th>Reference</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($payments)): ?>
                    <tr><td colspan="7"><div class="ad-empty"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg><p>No payment records available.</p></div></td></tr>
                    <?php else: ?>
                    <?php foreach ($payments as $payment): ?>
                    <tr>
                      <td class="ad-table-name" data-label="User"><?= e($payment['user_name'] ?? 'Unknown') ?></td>
                      <td data-label="Plan"><?= e($payment['plan_name'] ?? 'Plan') ?></td>
                      <td class="ad-table-muted" data-label="Reference"><code style="font-size:0.75rem;"><?= e($payment['transaction_id'] ?? '—') ?></code></td>
                      <td data-label="Amount"><strong><?= formatPrice($payment['amount'] ?? 0) ?></strong></td>
                      <td data-label="Status">
                        <?php $ps = $payment['status'] ?? 'pending'; ?>
                        <span class="ad-badge <?= $ps === 'approved' ? 'ad-badge-success' : ($ps === 'pending' ? 'ad-badge-warning' : 'ad-badge-danger') ?>"><?= ucfirst($ps) ?></span>
                      </td>
                      <td class="ad-table-muted" data-label="Date"><?= isset($payment['created_at']) ? date('d M Y', strtotime($payment['created_at'])) : '—' ?></td>
                      <td data-label="Action">
                        <?php if (($payment['status'] ?? '') === 'pending'): ?>
                          <a class="ad-btn ad-btn-success ad-btn-xs" href="?route=approve-payment&payment_id=<?= (int)$payment['id'] ?>">Approve</a>
                          <a class="ad-btn ad-btn-danger ad-btn-xs" href="?route=reject-payment&payment_id=<?= (int)$payment['id'] ?>">Reject</a>
                        <?php else: ?><span class="ad-text-muted" style="font-size:0.78rem;">Processed</span><?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB PANEL: SUBSCRIPTIONS -->
        <div class="ad-tab-panel" data-tab-panel="fin-subscriptions" style="display:none;">
          <div class="ad-grid-2" style="margin-bottom:18px;">
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line><line x1="3" y1="20" x2="21" y2="20"></line></svg> Monthly Revenue</h3>
              </div>
              <div class="ad-panel-body">
                <div class="ad-chart-wrap"><canvas id="adFinanceBar"></canvas></div>
              </div>
            </div>
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9z"></path><path d="M11 3 8 9l4 13 4-13-3-6"></path><path d="M2 9h20"></path></svg> Subscription Overview</h3>
              </div>
              <div class="ad-panel-body">
                <div class="ad-list">
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:var(--ad-info-bg);color:var(--ad-info);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></div>
                    <div class="ad-list-body"><strong>Premium Plan</strong><small>Active subscribers</small></div>
                    <strong><?= (int)($analytics['premiumUsers'] ?? 0) ?></strong>
                  </div>
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:rgba(212,160,23,0.12);color:var(--ad-gold);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7z"></path><path d="M5 20h14"></path></svg></div>
                    <div class="ad-list-body"><strong>Super Premium Plan</strong><small>Active subscribers</small></div>
                    <strong><?= (int)($analytics['superUsers'] ?? 0) ?></strong>
                  </div>
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:rgba(15,23,42,0.06);color:var(--ad-muted);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
                    <div class="ad-list-body"><strong>Free Plan</strong><small>Unsubscribed users</small></div>
                    <strong><?= max(0, (int)($analytics['totalUsers'] ?? count($users ?? [])) - (int)($analytics['premiumUsers'] ?? 0) - (int)($analytics['superUsers'] ?? 0)) ?></strong>
                  </div>
                </div>
                <hr class="ad-divider">
                <div class="ad-sub-stat-row" style="grid-template-columns:1fr 1fr;">
                  <div class="ad-sub-stat"><label>Avg. Revenue/User</label><strong><?= formatPrice(($analytics['revenue'] ?? 0) / max(1, count($payments ?? []))) ?></strong></div>
                  <div class="ad-sub-stat"><label>Conversion Rate</label><strong><?= number_format(((int)($analytics['premiumUsers'] ?? 0) + (int)($analytics['superUsers'] ?? 0)) / max(1, (int)($analytics['totalUsers'] ?? 1)) * 100, 1) ?>%</strong></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB PANEL: TRUST & SAFETY -->
        <div class="ad-tab-panel" data-tab-panel="fin-trust" style="display:none;">
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> Trust &amp; Verification Center</h3>
              <span class="ad-badge ad-badge-info">Platform Integrity</span>
            </div>
            <div class="ad-panel-body">
              <div class="ad-sub-stat-row">
                <div class="ad-sub-stat">
                  <label>Verification Queue</label>
                  <strong><?= count(array_filter($verifications ?? [], fn($v) => ($v['status'] ?? 'pending') === 'pending')) ?></strong>
                  <small>Pending identity checks</small>
                </div>
                <div class="ad-sub-stat">
                  <label>Avg. Trust Score</label>
                  <strong>4.8 / 5</strong>
                  <small>Platform-wide average</small>
                </div>
                <div class="ad-sub-stat">
                  <label>Fraud Reports</label>
                  <strong>0</strong>
                  <small>Open investigation cases</small>
                </div>
              </div>
              <div class="ad-grid-2" style="margin-top:14px;">
                <div>
                  <h4 style="font-size:0.88rem;font-weight:700;margin-bottom:12px;">Recent Verification Requests</h4>
                  <div class="ad-list">
                    <?php if (empty($verifications)): ?>
                    <div class="ad-empty"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect><line x1="16" y1="8" x2="16.01" y2="8"></line><line x1="16" y1="12" x2="16.01" y2="12"></line><line x1="16" y1="16" x2="16.01" y2="16"></line></svg><p>No pending verifications.</p></div>
                    <?php else: ?>
                    <?php foreach ($verifications as $v): ?>
                    <div class="ad-list-item">
                      <div class="ad-list-icon" style="background:var(--ad-warning-bg);color:var(--ad-warning);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect><line x1="16" y1="8" x2="16.01" y2="8"></line><line x1="16" y1="12" x2="16.01" y2="12"></line><line x1="16" y1="16" x2="16.01" y2="16"></line></svg></div>
                      <div class="ad-list-body">
                        <strong><?= e($v['full_name'] ?? 'Unknown') ?></strong>
                        <small><?= e($v['email'] ?? '') ?> · Role: <?= ucfirst($v['role'] ?? 'provider') ?></small>
                      </div>
                      <div style="display:flex;gap:4px;align-items:center;">
                        <?php if (($v['status'] ?? 'pending') === 'pending'): ?>
                          <a class="ad-btn ad-btn-success ad-btn-xs" href="?route=approve-verification&id=<?= (int)$v['id'] ?>">Verify</a>
                          <a class="ad-btn ad-btn-danger ad-btn-xs" href="?route=reject-verification&id=<?= (int)$v['id'] ?>">Reject</a>
                        <?php else: ?>
                          <span class="ad-badge <?= ($v['status'] ?? '') === 'approved' ? 'ad-badge-success' : 'ad-badge-danger' ?>"><?= ucfirst($v['status'] ?? '') ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </div>
                <div>
                  <h4 style="font-size:0.88rem;font-weight:700;margin-bottom:12px;">Fraud Monitoring</h4>
                  <div class="ad-sys-item" style="margin-bottom:10px;">
                    <div class="ad-sys-item-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><polyline points="9 11 11 13 15 9"></polyline></svg></div>
                    <div class="ad-sys-item-body"><label>Fraud Detection</label><strong>No Alerts</strong><small>System scan clean</small></div>
                    <span class="ad-badge ad-badge-success">Clear</span>
                  </div>
                  <div class="ad-sys-item" style="margin-bottom:10px;">
                    <div class="ad-sys-item-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg></div>
                    <div class="ad-sys-item-body"><label>Account Anomalies</label><strong>0 Suspicious</strong><small>Last 24 hours</small></div>
                    <span class="ad-badge ad-badge-success">Clear</span>
                  </div>
                  <div class="ad-sys-item">
                    <div class="ad-sys-item-icon" style="background:var(--ad-info-bg);color:var(--ad-info);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></div>
                    <div class="ad-sys-item-body"><label>User Reports</label><strong>0 Open</strong><small>Under review</small></div>
                    <span class="ad-badge ad-badge-info">Monitored</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

      <!-- ==================================================
           VIEW 4 — TECHNOLOGY & SYSTEM CENTER
           ================================================== -->
      <section class="ad-view" data-view="technology" aria-label="Technology and System Center">

        <!-- Tab Controls -->
        <div class="ad-tabs ad-tab-group">
          <button type="button" class="ad-tab active" data-tab="tech-health"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg> Infrastructure</button>
          <button type="button" class="ad-tab" data-tab="tech-security"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Security &amp; Activity</button>
          <button type="button" class="ad-tab" data-tab="tech-audit"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><line x1="9" y1="9" x2="15" y2="9"></line><line x1="9" y1="13" x2="15" y2="13"></line><line x1="9" y1="17" x2="14" y2="17"></line></svg> Audit Logs</button>
        </div>

        <!-- System KPIs -->
        <div class="ad-kpi-grid" style="margin-bottom: 20px;">
          <article class="ad-kpi-card ad-kpi-green">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg></div>
            <div class="ad-kpi-label">Server Uptime</div>
            <div class="ad-kpi-value">99.8%</div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> Last 30 days</div>
          </article>
          <article class="ad-kpi-card ad-kpi-blue">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"></path></svg></div>
            <div class="ad-kpi-label">DB Response Time</div>
            <div class="ad-kpi-value">12<small style="font-size:1rem;">ms</small></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> Optimal</div>
          </article>
          <article class="ad-kpi-card ad-kpi-orange">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect><line x1="2" y1="12" x2="22" y2="12"></line><line x1="6" y1="17" x2="6.01" y2="17"></line></svg></div>
            <div class="ad-kpi-label">Storage Used</div>
            <div class="ad-kpi-value">68%</div>
            <div class="ad-kpi-trend neutral"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg> 32% free</div>
          </article>
          <article class="ad-kpi-card ad-kpi-purple">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 0 0-10 10c0 4.15 2.5 7.72 6.13 9.24"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg></div>
            <div class="ad-kpi-label">API Response</div>
            <div class="ad-kpi-value">98<small style="font-size:1rem;">ms</small></div>
            <div class="ad-kpi-trend up"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg> Fast</div>
          </article>
        </div>

        <!-- TAB PANEL: INFRASTRUCTURE HEALTH -->
        <div class="ad-tab-panel" data-tab-panel="tech-health">
          <div class="ad-grid-2" style="margin-bottom:18px;">
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg> System Monitoring</h3>
                <span class="ad-badge ad-badge-success">All Systems Normal</span>
              </div>
              <div class="ad-panel-body">
                <div class="ad-sys-grid">
                  <div class="ad-sys-item">
                    <div class="ad-sys-item-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg></div>
                    <div class="ad-sys-item-body">
                      <label>Web Server</label>
                      <strong>Apache / PHP</strong>
                      <div class="ad-metric-bar-wrap"><div class="ad-metric-bar"><div class="ad-metric-bar-fill success" data-width="45%"></div></div></div>
                    </div>
                    <div class="ad-sys-metric"><div class="value ad-text-success">45%</div><small>CPU</small></div>
                  </div>
                  <div class="ad-sys-item">
                    <div class="ad-sys-item-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"></path></svg></div>
                    <div class="ad-sys-item-body">
                      <label>Database</label>
                      <strong>MySQL / MariaDB</strong>
                      <div class="ad-metric-bar-wrap"><div class="ad-metric-bar"><div class="ad-metric-bar-fill success" data-width="38%"></div></div></div>
                    </div>
                    <div class="ad-sys-metric"><div class="value ad-text-success">38%</div><small>Load</small></div>
                  </div>
                  <div class="ad-sys-item">
                    <div class="ad-sys-item-icon" style="background:var(--ad-warning-bg);color:var(--ad-warning);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect><line x1="2" y1="12" x2="22" y2="12"></line><line x1="6" y1="17" x2="6.01" y2="17"></line></svg></div>
                    <div class="ad-sys-item-body">
                      <label>Storage Volume</label>
                      <strong>68% of 50 GB</strong>
                      <div class="ad-metric-bar-wrap"><div class="ad-metric-bar"><div class="ad-metric-bar-fill warn" data-width="68%"></div></div></div>
                    </div>
                    <div class="ad-sys-metric"><div class="value ad-text-warning">68%</div><small>Used</small></div>
                  </div>
                  <div class="ad-sys-item">
                    <div class="ad-sys-item-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="18"></line><line x1="10" y1="6" x2="10" y2="18"></line><line x1="14" y1="6" x2="14" y2="18"></line><line x1="18" y1="6" x2="18" y2="18"></line></svg></div>
                    <div class="ad-sys-item-body">
                      <label>Memory (RAM)</label>
                      <strong>4.2 GB / 8 GB</strong>
                      <div class="ad-metric-bar-wrap"><div class="ad-metric-bar"><div class="ad-metric-bar-fill success" data-width="52%"></div></div></div>
                    </div>
                    <div class="ad-sys-metric"><div class="value ad-text-success">52%</div><small>Used</small></div>
                  </div>
                  <div class="ad-sys-item">
                    <div class="ad-sys-item-icon" style="background:var(--ad-info-bg);color:var(--ad-info);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></div>
                    <div class="ad-sys-item-body">
                      <label>Background Jobs</label>
                      <strong>3 queued / 0 failed</strong>
                      <small>Last run: 2 min ago</small>
                    </div>
                    <span class="ad-badge ad-badge-info">Running</span>
                  </div>
                  <div class="ad-sys-item">
                    <div class="ad-sys-item-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 12V8h-2V6a4 4 0 0 0-8 0v2H6v4a4 4 0 0 0 4 4h4a4 4 0 0 0 4-4z"></path><line x1="12" y1="16" x2="12" y2="22"></line></svg></div>
                    <div class="ad-sys-item-body">
                      <label>API Status</label>
                      <strong>All endpoints active</strong>
                      <small>Avg. 98ms response</small>
                    </div>
                    <span class="ad-badge ad-badge-success">Online</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg> System Load (24h)</h3>
              </div>
              <div class="ad-panel-body">
                <div class="ad-chart-wrap ad-chart-lg"><canvas id="adSysLoadChart"></canvas></div>
              </div>
            </div>
          </div>

          <div class="ad-grid-2" style="margin-bottom:18px;">
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 12V8h-2V6a4 4 0 0 0-8 0v2H6v4a4 4 0 0 0 4 4h4a4 4 0 0 0 4-4z"></path><line x1="12" y1="16" x2="12" y2="22"></line></svg> API Response Time</h3>
              </div>
              <div class="ad-panel-body">
                <div class="ad-chart-wrap"><canvas id="adApiChart"></canvas></div>
              </div>
            </div>
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg> Maintenance &amp; Actions</h3>
              </div>
              <div class="ad-panel-body">
                <div class="ad-list">
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></div>
                    <div class="ad-list-body"><strong>Database Backup</strong><small>Last: Today at 03:00 AM</small></div>
                    <button class="ad-btn ad-btn-primary ad-btn-xs" onclick="triggerSysAction('backup')">Run Backup</button>
                  </div>
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:var(--ad-info-bg);color:var(--ad-info);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg></div>
                    <div class="ad-list-body"><strong>System Updates</strong><small>Status: Fully Updated</small></div>
                    <button class="ad-btn ad-btn-outline ad-btn-xs" onclick="triggerSysAction('updates')">Check Updates</button>
                  </div>
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:rgba(220,38,38,0.1);color:var(--ad-danger);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                    <div class="ad-list-body"><strong>Verify Integrity</strong><small>Security check</small></div>
                    <button class="ad-btn ad-btn-ghost ad-btn-xs text-danger" style="border:1px solid rgba(220,38,38,0.2);" onclick="triggerSysAction('integrity')">Verify</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB PANEL: SECURITY OVERVIEW -->
        <div class="ad-tab-panel" data-tab-panel="tech-security" style="display:none;">
          <div class="ad-grid-2" style="margin-bottom:18px;">
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Security Overview</h3>
                <span class="ad-badge ad-badge-success">No Active Threats</span>
              </div>
              <div class="ad-panel-body">
                <div class="ad-list">
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg></div>
                    <div class="ad-list-body"><strong>Login Activities</strong><small>Last 24 hours</small></div>
                    <strong>47 sessions</strong>
                  </div>
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:var(--ad-warning-bg);color:var(--ad-warning);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></div>
                    <div class="ad-list-body"><strong>Failed Logins</strong><small>Last 24 hours</small></div>
                    <span class="ad-badge ad-badge-warning">3 attempts</span>
                  </div>
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></div>
                    <div class="ad-list-body"><strong>Suspicious Activities</strong><small>Anomaly detection</small></div>
                    <span class="ad-badge ad-badge-success">Clear</span>
                  </div>
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:var(--ad-info-bg);color:var(--ad-info);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
                    <div class="ad-list-body"><strong>Access Control</strong><small>Role-based permissions</small></div>
                    <span class="ad-badge ad-badge-info">Enforced</span>
                  </div>
                  <div class="ad-list-item">
                    <div class="ad-list-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.778-7.778zM12 2l-4 4 1.5 1.5L11 6l2 2-1.5 1.5L14 11l2-2-1.5-1.5L17 7z"></path></svg></div>
                    <div class="ad-list-body"><strong>SSL Certificate</strong><small>HTTPS enforced</small></div>
                    <span class="ad-badge ad-badge-success">Valid</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="ad-panel">
              <div class="ad-panel-header">
                <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> Active Sessions</h3>
              </div>
              <div class="ad-panel-body">
                <div class="ad-table-wrap">
                  <table class="ad-table">
                    <thead><tr><th>User</th><th>IP Address</th><th>Location</th><th>Device</th></tr></thead>
                    <tbody>
                      <tr><td>Admin</td><td>197.243.32.11</td><td>Kigali, Gasabo</td><td>Chrome/Win11</td></tr>
                      <tr><td>ProviderMarie</td><td>197.243.12.87</td><td>Kigali, Kicukiro</td><td>Safari/iOS17</td></tr>
                      <tr><td>ClientJohn</td><td>41.216.96.4</td><td>Musanze, Northern</td><td>Firefox/Linux</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB PANEL: AUDIT LOGS -->
        <div class="ad-tab-panel" data-tab-panel="tech-audit" style="display:none;">
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><line x1="9" y1="9" x2="15" y2="9"></line><line x1="9" y1="13" x2="15" y2="13"></line><line x1="9" y1="17" x2="14" y2="17"></line></svg> Audit Logs</h3>
              <span class="ad-badge ad-badge-muted">Recent Events</span>
            </div>
            <div class="ad-panel-body">
              <div class="ad-table-wrap">
                <table class="ad-table">
                  <thead>
                    <tr><th>Time</th><th>Action</th><th>Actor</th><th>Type</th></tr>
                  </thead>
                  <tbody>
                    <tr class="ad-log-row">
                      <td data-label="Time">21:12:04</td>
                      <td data-label="Action">Payment approved</td>
                      <td data-label="Actor">Admin</td>
                      <td data-label="Type"><span class="ad-audit-badge">Finance</span></td>
                    </tr>
                    <tr class="ad-log-row">
                      <td data-label="Time">20:58:31</td>
                      <td data-label="Action">User account created</td>
                      <td data-label="Actor">System</td>
                      <td data-label="Type"><span class="ad-audit-badge">Users</span></td>
                    </tr>
                    <tr class="ad-log-row">
                      <td data-label="Time">20:44:17</td>
                      <td data-label="Action">Listing published</td>
                      <td data-label="Actor">Provider</td>
                      <td data-label="Type"><span class="ad-audit-badge">Marketplace</span></td>
                    </tr>
                    <tr class="ad-log-row">
                      <td data-label="Time">20:30:02</td>
                      <td data-label="Action">Login attempt failed</td>
                      <td data-label="Actor">Unknown IP</td>
                      <td data-label="Type"><span class="ad-audit-badge" style="background:var(--ad-danger-bg);color:var(--ad-danger);border-color:rgba(220,38,38,0.15);">Security</span></td>
                    </tr>
                    <tr class="ad-log-row">
                      <td data-label="Time">20:15:44</td>
                      <td data-label="Action">Backup completed</td>
                      <td data-label="Actor">Cron</td>
                      <td data-label="Type"><span class="ad-audit-badge">System</span></td>
                    </tr>
                    <tr class="ad-log-row">
                      <td data-label="Time">19:44:02</td>
                      <td data-label="Action">User profile verified</td>
                      <td data-label="Actor">Admin</td>
                      <td data-label="Type"><span class="ad-audit-badge">Users</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </section>

      <!-- ==================================================
           VIEW 5 — NOTIFICATIONS
           ================================================== -->
      <section class="ad-view" data-view="notifications" aria-label="Notifications">

        <div class="ad-kpi-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:22px;">
          <article class="ad-kpi-card ad-kpi-orange">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg></div>
            <div class="ad-kpi-label">Unread Notifications</div>
            <div class="ad-kpi-value">5</div>
          </article>
          <article class="ad-kpi-card ad-kpi-blue">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg></div>
            <div class="ad-kpi-label">Read Today</div>
            <div class="ad-kpi-value">12</div>
          </article>
          <article class="ad-kpi-card ad-kpi-green">
            <div class="ad-kpi-icon"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.73 21a2 2 0 0 1-3.46 0"></path><path d="M18.63 13A17.89 17.89 0 0 1 18 8"></path><path d="M6.26 6.26A5.86 5.86 0 0 0 6 8c0 7-3 9-3 9h14"></path><path d="M18 8a6 6 0 0 0-9.33-5"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg></div>
            <div class="ad-kpi-label">Total This Week</div>
            <div class="ad-kpi-value">34</div>
          </article>
        </div>

        <div class="ad-panel">
          <div class="ad-panel-header">
            <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg> Notification Inbox</h3>
            <div class="ad-panel-actions">
              <button type="button" class="ad-btn ad-btn-ghost ad-btn-sm">Mark All Read</button>
            </div>
          </div>
          <div class="ad-panel-body">
            <div class="ad-notif-list">
              <div class="ad-notif-item unread">
                <div class="ad-notif-icon" style="background:var(--ad-warning-bg);color:var(--ad-warning);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></div>
                <div class="ad-notif-body">
                  <strong>Payment Requires Approval</strong>
                  <p>John Mutabazi submitted a Premium subscription payment of RWF 25,000. Reference: TXN-20240609-001.</p>
                </div>
                <div class="ad-notif-time">2m ago</div>
              </div>
              <div class="ad-notif-item unread">
                <div class="ad-notif-icon" style="background:var(--ad-info-bg);color:var(--ad-info);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="17" y1="11" x2="23" y2="11"></line></svg></div>
                <div class="ad-notif-body">
                  <strong>New Provider Registration</strong>
                  <p>Marie Uwimana registered as a new service provider (Plumbing). Identity verification pending.</p>
                </div>
                <div class="ad-notif-time">14m ago</div>
              </div>
              <div class="ad-notif-item unread">
                <div class="ad-notif-icon" style="background:var(--ad-danger-bg);color:var(--ad-danger);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></div>
                <div class="ad-notif-body">
                  <strong>Security Alert: Failed Login Attempts</strong>
                  <p>3 consecutive failed login attempts detected from IP 197.243.xx.xx. Account not locked.</p>
                </div>
                <div class="ad-notif-time">31m ago</div>
              </div>
              <div class="ad-notif-item unread">
                <div class="ad-notif-icon" style="background:var(--ad-success-bg);color:var(--ad-success);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></div>
                <div class="ad-notif-body">
                  <strong>Database Backup Completed</strong>
                  <p>Automated daily backup ran successfully. Size: 2.3 GB. Storage: 32% free.</p>
                </div>
                <div class="ad-notif-time">3h ago</div>
              </div>
              <div class="ad-notif-item unread">
                <div class="ad-notif-icon" style="background:var(--ad-purple-bg);color:var(--ad-purple);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></div>
                <div class="ad-notif-body">
                  <strong>Featured Listing Request</strong>
                  <p>Provider David Kamanzi requested featured placement for listing "3BR House in Kicukiro". Needs review.</p>
                </div>
                <div class="ad-notif-time">5h ago</div>
              </div>
              <div class="ad-notif-item">
                <div class="ad-notif-icon" style="background:var(--ad-info-bg);color:var(--ad-info);"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path></svg></div>
                <div class="ad-notif-body">
                  <strong>Weekly Performance Report Ready</strong>
                  <p>Platform performance summary for the week ending June 7, 2026 is now available.</p>
                </div>
                <div class="ad-notif-time">Yesterday</div>
              </div>
            </div>
          </div>
        </div>

      </section>

      <!-- ==================================================
           VIEW 6 — SETTINGS
           ================================================== -->
      <section class="ad-view" data-view="settings" aria-label="Settings">

        <div class="ad-grid-2" style="margin-bottom:18px;">
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path><circle cx="12" cy="7" r="4"></circle></svg> Administrator Profile</h3>
            </div>
            <div class="ad-panel-body">
              <div class="ad-form-row">
                <div class="ad-form-group">
                  <label for="settingsFullName">Full Name</label>
                  <input type="text" id="settingsFullName" class="ad-form-control" value="<?= e($_SESSION['full_name'] ?? 'Administrator') ?>" />
                </div>
                <div class="ad-form-group">
                  <label for="settingsRole">Role</label>
                  <input type="text" id="settingsRole" class="ad-form-control" value="CEO / IT Administrator" readonly style="background:var(--ad-surface-2);cursor:not-allowed;" />
                </div>
              </div>
              <div class="ad-form-row">
                <div class="ad-form-group">
                  <label for="settingsEmail">Email Address</label>
                  <input type="email" id="settingsEmail" class="ad-form-control" placeholder="admin@rwandamarketplace.rw" />
                </div>
                <div class="ad-form-group">
                  <label for="settingsPhone">Phone Number</label>
                  <input type="tel" id="settingsPhone" class="ad-form-control" placeholder="+250 7XX XXX XXX" />
                </div>
              </div>
              <button type="button" class="ad-btn ad-btn-primary"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Save Profile</button>
            </div>
          </div>
          <div class="ad-panel">
            <div class="ad-panel-header">
              <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> Security &amp; Access</h3>
            </div>
            <div class="ad-panel-body">
              <div class="ad-form-group" style="margin-bottom:14px;">
                <label for="settingsPwdCurrent">Current Password</label>
                <input type="password" id="settingsPwdCurrent" class="ad-form-control" placeholder="••••••••••" />
              </div>
              <div class="ad-form-row">
                <div class="ad-form-group">
                  <label for="settingsPwdNew">New Password</label>
                  <input type="password" id="settingsPwdNew" class="ad-form-control" placeholder="Min. 8 characters" />
                </div>
                <div class="ad-form-group">
                  <label for="settingsPwdConfirm">Confirm Password</label>
                  <input type="password" id="settingsPwdConfirm" class="ad-form-control" placeholder="Repeat password" />
                </div>
              </div>
              <button type="button" class="ad-btn ad-btn-outline"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.778-7.778zM12 2l-4 4 1.5 1.5L11 6l2 2-1.5 1.5L14 11l2-2-1.5-1.5L17 7z"></path></svg> Update Password</button>
            </div>
          </div>
        </div>

        <div class="ad-panel">
          <div class="ad-panel-header">
            <h3 class="ad-panel-title"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg> Platform Configuration</h3>
          </div>
          <div class="ad-panel-body">
            <div class="ad-form-row">
              <div class="ad-form-group">
                <label>Platform Name</label>
                <input type="text" class="ad-form-control" value="Rwanda Marketplace" />
              </div>
              <div class="ad-form-group">
                <label>Support Email</label>
                <input type="email" class="ad-form-control" placeholder="support@rwandamarketplace.rw" />
              </div>
            </div>
            <div class="ad-form-row">
              <div class="ad-form-group">
                <label>Default Currency</label>
                <input type="text" class="ad-form-control" value="RWF — Rwandan Franc" readonly style="background:var(--ad-surface-2);" />
              </div>
              <div class="ad-form-group">
                <label>Default Language</label>
                <input type="text" class="ad-form-control" value="English / Kinyarwanda" readonly style="background:var(--ad-surface-2);" />
              </div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
              <button type="button" class="ad-btn ad-btn-primary"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Save Configuration</button>
              <a href="?route=home" class="ad-btn ad-btn-ghost"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg> View Public Site</a>
              <a href="?route=logout" class="ad-btn ad-btn-danger"><svg class="ad-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Logout</a>
            </div>
          </div>
        </div>

      </section>

    </div><!-- /.ad-content -->
  </main><!-- /.ad-main -->

</div><!-- /.ad-layout -->

<script src="public/assets/js/admin-dashboard.js?v=<?= urlencode(md5_file(__DIR__ . '/../../public/assets/js/admin-dashboard.js')) ?>"></script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
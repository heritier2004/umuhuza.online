<?php include __DIR__ . '/../layouts/header.php'; ?>
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

<!-- Mobile Backdrop Overlay -->
<div class="provider-mobile-overlay" id="providerMobileOverlay" onclick="closeMobileDrawer()"></div>

<section class="dashboard-shell container py-4">
  <div class="row g-4 align-items-start">
    <!-- Sidebar Navigation -->
    <aside class="col-lg-3">
      <div class="dashboard-sidebar panel p-4">
        <div class="d-flex align-items-center gap-3 mb-4 provider-card-inline">
          <?php if (!empty($user['profile_image'])): ?>
            <img src="<?= e($user['profile_image']) ?>" alt="Profile photo" />
          <?php else: ?>
            <div class="provider-avatar-lg"><?= strtoupper(substr(($user['full_name'] ?? 'PR'), 0, 2)) ?></div>
          <?php endif; ?>
          <div>
            <p class="small text-muted-custom mb-1" data-i18n="welcome_back">Welcome back</p>
            <h4 class="fw-bold mb-0" style="font-size:1.05rem;"><?= e($user['full_name'] ?? 'Provider') ?></h4>
            <span class="badge badge-premium mt-1"><?= e($plan['name'] ?? 'Free') ?> plan</span>
          </div>
        </div>

        <nav class="nav flex-column gap-2 provider-nav-menu">
          <a class="dash-nav-link active" href="#overview" data-provider-tab="overview">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            <span>Overview</span>
          </a>
          <a class="dash-nav-link" href="#analytics" data-provider-tab="analytics">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            <span>Monthly Analytics</span>
          </a>
          <a class="dash-nav-link" href="#create-listing" data-provider-tab="create-listing">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            <span>Post Listing</span>
          </a>
          <a class="dash-nav-link" href="#matched-requests" data-provider-tab="matched-requests">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
            <span>Matched Leads</span>
            <?php if ($userMatchedCount > 0): ?>
              <span class="badge bg-warning text-dark ms-auto"><?= $userMatchedCount ?></span>
            <?php endif; ?>
          </a>
          <a class="dash-nav-link" href="#payments" data-provider-tab="payments">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            <span>Plans & Upgrades</span>
          </a>
          <a class="dash-nav-link" href="#notifications-center" data-provider-tab="notifications-center">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            <span>Notifications</span>
            <?php if (count($unreadNotifs) > 0): ?>
              <span class="badge bg-danger ms-auto"><?= count($unreadNotifs) ?></span>
            <?php endif; ?>
          </a>
          <a class="dash-nav-link" href="#recent-listings" data-provider-tab="recent-listings">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            <span>My Listings</span>
          </a>
          <a class="dash-nav-link" href="#profile-settings" data-provider-tab="profile-settings">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Profile Settings</span>
          </a>
          <a class="dash-nav-link text-muted" href="?route=listings">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
            <span>Marketplace</span>
          </a>
        </nav>

        <div class="mt-4 p-3 rounded-4 bg-soft border border-warning-subtle">
          <div class="small text-muted-custom">Pro Tip</div>
          <div class="fw-semibold small mt-1">Upgrade to Premium or Super plan to get priority lead matching & top ranking.</div>
        </div>
      </div>
    </aside>

    <!-- Main Workspace Content -->
    <main class="col-lg-9">
      <!-- Mobile Horizontal Scrollable Tab Bar (Visible on mobile < 992px) -->
      <div class="d-lg-none mb-3 overflow-auto">
        <div class="d-flex gap-2 pb-2 provider-nav-menu" style="white-space: nowrap;">
          <a class="dash-nav-link active px-3 py-2 small rounded-pill bg-white border" href="#overview" data-provider-tab="overview">
            <span>Overview</span>
          </a>
          <a class="dash-nav-link px-3 py-2 small rounded-pill bg-white border" href="#analytics" data-provider-tab="analytics">
            <span>Monthly Analytics</span>
          </a>
          <a class="dash-nav-link px-3 py-2 small rounded-pill bg-white border" href="#create-listing" data-provider-tab="create-listing">
            <span>Post Listing</span>
          </a>
          <a class="dash-nav-link px-3 py-2 small rounded-pill bg-white border" href="#matched-requests" data-provider-tab="matched-requests">
            <span>Matched Leads</span>
            <?php if ($userMatchedCount > 0): ?>
              <span class="badge bg-warning text-dark ms-1"><?= $userMatchedCount ?></span>
            <?php endif; ?>
          </a>
          <a class="dash-nav-link px-3 py-2 small rounded-pill bg-white border" href="#payments" data-provider-tab="payments">
            <span>Plans & Upgrades</span>
          </a>
          <a class="dash-nav-link px-3 py-2 small rounded-pill bg-white border" href="#notifications-center" data-provider-tab="notifications-center">
            <span>Notifications</span>
            <?php if (count($unreadNotifs) > 0): ?>
              <span class="badge bg-danger ms-1"><?= count($unreadNotifs) ?></span>
            <?php endif; ?>
          </a>
          <a class="dash-nav-link px-3 py-2 small rounded-pill bg-white border" href="#recent-listings" data-provider-tab="recent-listings">
            <span>My Listings</span>
          </a>
          <a class="dash-nav-link px-3 py-2 small rounded-pill bg-white border" href="#profile-settings" data-provider-tab="profile-settings">
            <span>Profile Settings</span>
          </a>
        </div>
      </div>

      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
          <p class="eyebrow mb-1" data-i18n="provider_workspace">Provider workspace</p>
          <h2 class="section-title mb-1" data-i18n="dashboard_title">Your marketplace dashboard</h2>
          <p class="text-muted-custom mb-0" data-i18n="dashboard_sub">Track your plan, publish fresh listings, and respond to new leads faster.</p>
        </div>
        <a class="btn btn-primary" href="?route=listings" data-i18n="view_marketplace">View marketplace</a>
      </div>

      <!-- TAB 1: OVERVIEW -->
      <div id="tab-overview" class="provider-tab-panel active">
        <!-- Quick Stats Grid -->
        <div class="row g-4 mb-4">
          <article class="col-md-6 col-xl-3 stat-card p-4">
            <div class="text-muted-custom small">Current plan</div>
            <div class="number text-orange"><?= e(strtoupper($plan['name'] ?? 'FREE')) ?></div>
            <div class="text-muted-custom small"><?= e($plan['listing_limit'] ?? 5) ?> listings allowed</div>
          </article>
          <article class="col-md-6 col-xl-3 stat-card p-4">
            <div class="text-muted-custom small">Remaining quota</div>
            <div class="number text-success"><?= (int)($remainingQuota ?? 0) ?></div>
            <div class="text-muted-custom small">Listings left this cycle</div>
          </article>
          <article class="col-md-6 col-xl-3 stat-card p-4">
            <div class="text-muted-custom small">Total listings</div>
            <div class="number text-primary"><?= $userListingsCount ?></div>
            <div class="text-muted-custom small">Approved + pending</div>
          </article>
          <article class="col-md-6 col-xl-3 stat-card p-4">
            <div class="text-muted-custom small">Matched requests</div>
            <div class="number text-warning"><?= $userMatchedCount ?></div>
            <div class="text-muted-custom small">Inquiries in your area</div>
          </article>
        </div>

        <div class="panel p-4 mb-4 bg-white rounded-4 border">
          <h5 class="fw-bold mb-2">Welcome to your Provider Dashboard</h5>
          <p class="text-muted-custom small mb-3">Manage your property and service listings, respond to client leads, and track your plan performance.</p>
          <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-primary btn-sm" onclick="switchToTab('create-listing')">Post New Listing</button>
            <button class="btn btn-outline-primary btn-sm" onclick="switchToTab('payments')">Upgrade Subscription</button>
            <button class="btn btn-outline-secondary btn-sm" onclick="switchToTab('analytics')">View Monthly Statistics</button>
          </div>
        </div>
      </div>

      <!-- TAB 2: MONTHLY ANALYTICS & STATISTICS -->
      <div id="tab-analytics" class="provider-tab-panel">
        <article class="panel p-4 mb-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h5 class="fw-bold mb-1">Monthly Performance & Statistics</h5>
              <p class="text-muted-custom small mb-0">Overview of your activity and lead performance for the current month.</p>
            </div>
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill"><?= date('F Y') ?></span>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <div class="p-3 rounded-4 bg-light border">
                <div class="small text-muted-custom">Monthly Listings Posted</div>
                <div class="h4 fw-bold text-dark my-1"><?= $userListingsCount ?></div>
                <div class="small text-success">Active on marketplace</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-3 rounded-4 bg-light border">
                <div class="small text-muted-custom">Monthly Client Matches</div>
                <div class="h4 fw-bold text-primary my-1"><?= $userMatchedCount ?></div>
                <div class="small text-primary">Leads in your service zone</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-3 rounded-4 bg-light border">
                <div class="small text-muted-custom">Account Health & Status</div>
                <div class="h4 fw-bold text-success my-1">100% Active</div>
                <div class="small text-muted-custom">Verified provider profile</div>
              </div>
            </div>
          </div>

          <div class="p-3 rounded-4 bg-light border">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-semibold small">Listing Quota Usage</span>
              <span class="small fw-bold text-primary"><?= $userListingsCount ?> / <?= $userPlanLimit ?> (<?= $quotaPercent ?>%)</span>
            </div>
            <div class="progress" style="height: 10px; border-radius: 999px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $quotaPercent ?>%; border-radius: 999px;" aria-valuenow="<?= $quotaPercent ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </article>
      </div>

      <!-- TAB 3: CREATE LISTING -->
      <div id="tab-create-listing" class="provider-tab-panel">
        <article id="create-listing" class="panel p-4 mb-4">
          <h5 class="fw-bold mb-3">Create a new listing</h5>
          <p class="text-muted-custom small mb-3">Add a property or service and make it visible to buyers right away.</p>
          <form class="row g-3" method="POST" action="?route=create-listing" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>">
            <div class="col-12">
              <label class="form-label small text-muted-custom">Listing title</label>
              <input class="form-control" name="title" placeholder="Enter a clear listing title" required />
            </div>
            <div class="col-md-6">
              <label class="form-label small text-muted-custom">Price</label>
              <input class="form-control" name="price" placeholder="Amount in RWF" required />
            </div>
            <div class="col-md-6">
              <label class="form-label small text-muted-custom">Province</label>
              <input class="form-control" name="province" placeholder="Kigali" />
            </div>
            <div class="col-md-6">
              <label class="form-label small text-muted-custom">District</label>
              <input class="form-control" name="district" placeholder="Gasabo" />
            </div>
            <div class="col-md-6">
              <label class="form-label small text-muted-custom">Sector</label>
              <input class="form-control" name="sector" placeholder="Kacyiru" />
            </div>
            <div class="col-12">
              <label class="form-label small text-muted-custom">Description</label>
              <textarea class="form-control" name="description" rows="3" placeholder="Describe what buyers or clients will get"></textarea>
            </div>
            <div class="col-12">
              <label class="form-label small text-muted-custom">Listing photos (1 to 5 photos)</label>
              <input class="form-control" type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp,image/gif" data-max-files="5" data-min-files="1" data-max-bytes="5242880" data-min-bytes="5120" />
              <small class="text-muted d-block mt-1" style="font-size:0.8rem;">Supported: JPG, PNG, WEBP, GIF. Photos per post: 1 to 5. Size: 5 KB – 5 MB per photo (auto-compressed for fast loading).</small>
            </div>
            <div class="col-12">
              <button class="btn btn-primary" type="submit">Publish listing</button>
            </div>
          </form>
        </article>
      </div>

      <!-- TAB 4: PLANS & UPGRADES (WITH PLAN SHOWCASE CARDS) -->
      <div id="tab-payments" class="provider-tab-panel">
        <!-- PLAN SHOWCASE SLIDER CARDS -->
        <article class="panel p-4 mb-4">
          <h5 class="fw-bold mb-1">Subscription Plans & Marketplace Visibility</h5>
          <p class="text-muted-custom small mb-4">Compare available tiers and choose the right plan for your business growth.</p>
          
          <div class="row g-3 mb-4">
            <!-- Free Plan -->
            <div class="col-md-4">
              <div class="card h-100 p-3 rounded-4 border <?= $activePlanId === 1 ? 'border-primary shadow-sm bg-primary-subtle bg-opacity-10' : 'bg-light' ?>">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-bold text-dark">Free Plan</span>
                  <?php if ($activePlanId === 1): ?>
                    <span class="badge bg-primary text-white">Active Plan</span>
                  <?php endif; ?>
                </div>
                <div class="h4 fw-bold text-dark mb-3">0 <small class="fs-6 text-muted">RWF / mo</small></div>
                <ul class="list-unstyled small text-muted-custom mb-3 gap-2 d-flex flex-column">
                  <li>✔ <strong>5 Listings</strong> quota</li>
                  <li>✔ Standard search ranking</li>
                  <li>✔ Basic customer lead access</li>
                  <li>✔ Community profile badge</li>
                </ul>
              </div>
            </div>

            <!-- Premium Plan -->
            <div class="col-md-4">
              <div class="card h-100 p-3 rounded-4 border <?= $activePlanId === 2 ? 'border-warning shadow bg-warning-subtle bg-opacity-10' : 'border-warning-subtle bg-white' ?>">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-bold text-warning-emphasis">Premium Plan</span>
                  <?php if ($activePlanId === 2): ?>
                    <span class="badge bg-warning text-dark">Active Plan</span>
                  <?php else: ?>
                    <span class="badge bg-warning-subtle text-warning">Popular</span>
                  <?php endif; ?>
                </div>
                <div class="h4 fw-bold text-dark mb-3">15,000 <small class="fs-6 text-muted">RWF / mo</small></div>
                <ul class="list-unstyled small text-muted-custom mb-3 gap-2 d-flex flex-column">
                  <li>✔ <strong>15 Listings</strong> quota</li>
                  <li>✔ <strong>2x Priority</strong> search placement</li>
                  <li>✔ Unlimited matched client leads</li>
                  <li>✔ Direct WhatsApp contact button</li>
                  <li>✔ Verified Provider Badge</li>
                </ul>
              </div>
            </div>

            <!-- Super Plan -->
            <div class="col-md-4">
              <div class="card h-100 p-3 rounded-4 border <?= $activePlanId === 3 ? 'border-success shadow bg-success-subtle bg-opacity-10' : 'border-success-subtle bg-white' ?>">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-bold text-success">Super Plan</span>
                  <?php if ($activePlanId === 3): ?>
                    <span class="badge bg-success text-white">Active Plan</span>
                  <?php else: ?>
                    <span class="badge bg-success-subtle text-success">Maximum Growth</span>
                  <?php endif; ?>
                </div>
                <div class="h4 fw-bold text-dark mb-3">30,000 <small class="fs-6 text-muted">RWF / mo</small></div>
                <ul class="list-unstyled small text-muted-custom mb-3 gap-2 d-flex flex-column">
                  <li>✔ <strong>Unlimited</strong> listings quota</li>
                  <li>✔ <strong>Top #1 Featured</strong> marketplace ranking</li>
                  <li>✔ Instant priority lead notification</li>
                  <li>✔ Gold Verified Agent Badge</li>
                  <li>✔ VIP Admin Support</li>
                </ul>
              </div>
            </div>
          </div>
        </article>

        <!-- PAYMENT FORM -->
        <article id="payments" class="panel p-4 mb-4">
          <h5 class="fw-bold mb-3">Upgrade plan & payments</h5>
          <p class="text-muted-custom small mb-3">Choose a plan, pay manually, and get your listing boosted after admin approval.</p>
          <form class="row g-3" method="POST" action="?route=upgrade-plan">
            <input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>">
            <div class="col-12">
              <label class="form-label small text-muted-custom">Plan</label>
              <select class="form-select" name="plan_id">
                <option value="1" <?= $activePlanId === 1 ? 'selected' : '' ?>>Free Plan (5 listings)</option>
                <option value="2" <?= $activePlanId === 2 ? 'selected' : '' ?>>Premium Plan - 15,000 RWF (15 listings)</option>
                <option value="3" <?= $activePlanId === 3 ? 'selected' : '' ?>>Super Plan - 30,000 RWF (Unlimited listings)</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small text-muted-custom">Transaction / reference ID</label>
              <input class="form-control" name="transaction_id" placeholder="Reference from your payment slip" />
            </div>
            <div class="col-12">
              <label class="form-label small text-muted-custom">Sender name</label>
              <input class="form-control" name="sender_name" placeholder="Name shown on the payment" />
            </div>
            <div class="col-12">
              <label class="form-label small text-muted-custom">Sender phone</label>
              <input class="form-control" name="sender_phone" placeholder="Phone used for payment" />
            </div>
            <div class="col-12">
              <label class="form-label small text-muted-custom">Amount</label>
              <input class="form-control" name="amount" placeholder="Amount sent in RWF" />
            </div>
            <div class="col-12">
              <button class="btn btn-primary w-100" type="submit">Submit payment for verification</button>
            </div>
          </form>
          <div class="alert alert-warning mt-3 mb-0">Admin approval is required for payments and plan upgrades.</div>
        </article>
      </div>

      <!-- TAB 5: MATCHED LEADS -->
      <div id="tab-matched-requests" class="provider-tab-panel">
        <article id="matched-requests" class="panel p-4 mb-4">
          <h5 class="fw-bold mb-3">Matched Requests / Inquiries</h5>
          <?php if (($blockedLeadsCount ?? 0) > 0): ?>
            <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 rounded-4 border-warning bg-warning-subtle mb-3">
              <div>
                <strong>⚠️ Delivery Limit Reached</strong>
                <p class="small mb-0 text-muted-custom">You have <?= (int)$blockedLeadsCount ?> blocked leads. Upgrade plan to receive unlimited leads.</p>
              </div>
              <button onclick="switchToTab('payments')" class="btn btn-warning btn-sm">Upgrade Plan</button>
            </div>
          <?php endif; ?>
          <p class="text-muted-custom small mb-3">These are clients looking for services or properties matching your location and service area.</p>
          <?php if (!empty($matchedRequests)): ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr>
                    <th>Client</th>
                    <th>Location</th>
                    <th>Request / Need</th>
                    <th>Contact</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($matchedRequests as $req): ?>
                    <tr>
                      <td>
                        <div class="fw-semibold"><?= e($req['name']) ?></div>
                        <div class="small text-muted-custom"><?= e(ucfirst($req['type'] ?? 'service')) ?></div>
                      </td>
                      <td>
                        <div class="small fw-semibold"><?= e($req['province']) ?></div>
                        <div class="small text-muted-custom"><?= e($req['district']) ?> / <?= e($req['sector']) ?></div>
                      </td>
                      <td>
                        <div class="fw-normal mb-1"><?= e($req['description']) ?></div>
                        <?php if (!empty($req['budget'])): ?>
                          <span class="badge bg-success-subtle text-success">Budget: <?= e($req['budget']) ?> RWF</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <div class="d-flex gap-2">
                          <a href="tel:<?= e($req['phone']) ?>" class="btn btn-sm btn-outline-primary">Call</a>
                          <?php if (!empty($req['whatsapp'])): ?>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $req['whatsapp']) ?>" class="btn btn-sm btn-success" target="_blank">WhatsApp</a>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="text-muted-custom mb-0">No matching requests found yet. Requests from clients in your service areas will appear here.</p>
          <?php endif; ?>
        </article>
      </div>

      <!-- TAB 6: NOTIFICATIONS CENTER -->
      <div id="tab-notifications-center" class="provider-tab-panel">
        <article id="notifications-center" class="panel p-4 mb-4">
          <h5 class="fw-bold mb-3">Notification Center</h5>
          <ul class="nav nav-tabs mb-3" id="notifTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active fw-semibold" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread-pane" type="button" role="tab" aria-controls="unread-pane" aria-selected="true">
                Unread <span class="badge bg-danger ms-1"><?= count($unreadNotifs) ?></span>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-semibold" id="read-tab" data-bs-toggle="tab" data-bs-target="#read-pane" type="button" role="tab" aria-controls="read-pane" aria-selected="false">
                Read
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-semibold" id="archived-tab" data-bs-toggle="tab" data-bs-target="#archived-pane" type="button" role="tab" aria-controls="archived-pane" aria-selected="false">
                Archived
              </button>
            </li>
          </ul>
          <div class="tab-content" id="notifTabsContent">
            <!-- Unread Pane -->
            <div class="tab-pane fade show active" id="unread-pane" role="tabpanel" aria-labelledby="unread-tab" tabindex="0">
              <?php if (!empty($unreadNotifs)): ?>
                <div class="list-group list-group-flush">
                  <?php foreach ($unreadNotifs as $notif): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-bottom px-0 bg-transparent">
                      <div>
                        <div class="fw-semibold text-dark mb-1"><?= e($notif['message']) ?></div>
                        <small class="text-muted-custom"><?= e($notif['created_at']) ?></small>
                      </div>
                      <div class="d-flex gap-2">
                        <button onclick="markNotifRead(<?= (int)$notif['id'] ?>)" class="btn btn-sm btn-outline-primary">Mark Read</button>
                        <button onclick="archiveNotif(<?= (int)$notif['id'] ?>)" class="btn btn-sm btn-outline-secondary">Archive</button>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p class="text-muted-custom py-2 mb-0">No unread notifications.</p>
              <?php endif; ?>
            </div>
            
            <!-- Read Pane -->
            <div class="tab-pane fade" id="read-pane" role="tabpanel" aria-labelledby="read-tab" tabindex="0">
              <?php if (!empty($readNotifs)): ?>
                <div class="list-group list-group-flush">
                  <?php foreach ($readNotifs as $notif): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-bottom px-0 bg-transparent">
                      <div>
                        <div class="text-dark mb-1"><?= e($notif['message']) ?></div>
                        <small class="text-muted-custom"><?= e($notif['created_at']) ?></small>
                      </div>
                      <div>
                        <button onclick="archiveNotif(<?= (int)$notif['id'] ?>)" class="btn btn-sm btn-outline-secondary">Archive</button>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p class="text-muted-custom py-2 mb-0">No read notifications.</p>
              <?php endif; ?>
            </div>
            
            <!-- Archived Pane -->
            <div class="tab-pane fade" id="archived-pane" role="tabpanel" aria-labelledby="archived-tab" tabindex="0">
              <?php if (!empty($archivedNotifs)): ?>
                <div class="list-group list-group-flush">
                  <?php foreach ($archivedNotifs as $notif): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-bottom px-0 bg-transparent">
                      <div>
                        <div class="text-muted mb-1"><?= e($notif['message']) ?></div>
                        <small class="text-muted-custom"><?= e($notif['created_at']) ?></small>
                      </div>
                      <span class="badge bg-secondary text-secondary-custom">Archived</span>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p class="text-muted-custom py-2 mb-0">No archived notifications.</p>
              <?php endif; ?>
            </div>
          </div>
        </article>
      </div>

      <!-- TAB 7: MY LISTINGS -->
      <div id="tab-recent-listings" class="provider-tab-panel">
        <article id="recent-listings" class="panel p-4 mb-4">
          <h5 class="fw-bold mb-3">My Recent Listings</h5>
          <?php if (!empty($listings)): ?>
            <div class="recent-listings-grid">
              <?php foreach (($listings ?? []) as $item): ?>
                <div class="recent-list-item d-flex justify-content-between align-items-center p-3 border rounded-3 mb-2 bg-light">
                  <div>
                    <h6 class="fw-semibold mb-1"><?= e($item['title'] ?? '') ?></h6>
                    <p class="small text-muted-custom mb-0"><?= formatPrice($item['price'] ?? 0) ?> • <?= e($item['province'] ?? '') ?> / <?= e($item['district'] ?? '') ?></p>
                  </div>
                  <span class="badge <?= ($item['status'] ?? '') === 'approved' ? 'bg-success' : 'bg-warning text-dark' ?>">
                    <?= ucfirst(e($item['status'] ?? 'pending')) ?>
                  </span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="text-muted-custom mb-0">You have no listings yet. Publish one to start getting matched with buyers.</p>
          <?php endif; ?>
        </article>
      </div>

      <!-- TAB 8: PROFILE SETTINGS -->
      <div id="tab-profile-settings" class="provider-tab-panel">
        <article id="profile-settings" class="panel p-4 mb-4">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
              <h5 class="fw-bold mb-1">Account & Profile Settings</h5>
              <p class="text-muted-custom small mb-0">Your registered provider profile details and account status.</p>
            </div>
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Verified Account</span>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-4 border">
                <small class="text-muted-custom d-block">Full Name</small>
                <div class="fw-bold text-dark fs-6 mt-1"><?= e($user['full_name'] ?? 'Provider') ?></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-4 border">
                <small class="text-muted-custom d-block">Email Address</small>
                <div class="fw-bold text-dark fs-6 mt-1"><?= e($user['email'] ?? 'Not specified') ?></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-4 border">
                <small class="text-muted-custom d-block">Phone Number</small>
                <div class="fw-bold text-dark fs-6 mt-1"><?= e($user['phone'] ?? 'Not specified') ?></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-4 border">
                <small class="text-muted-custom d-block">WhatsApp Contact</small>
                <div class="fw-bold text-dark fs-6 mt-1"><?= e($user['whatsapp'] ?? ($user['phone'] ?? 'Not specified')) ?></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-4 border">
                <small class="text-muted-custom d-block">Role & Account Type</small>
                <div class="fw-bold text-dark fs-6 mt-1"><?= e(ucfirst($user['role'] ?? 'provider')) ?> (<?= e(ucfirst($user['account_type'] ?? 'agent')) ?>)</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="p-3 bg-light rounded-4 border">
                <small class="text-muted-custom d-block">Service Location</small>
                <div class="fw-bold text-dark fs-6 mt-1"><?= e($user['province'] ?? 'Kigali') ?> / <?= e($user['district'] ?? 'Gasabo') ?></div>
              </div>
            </div>
          </div>

          <div class="mt-4 p-3 rounded-4 bg-primary-subtle text-primary border border-primary-subtle d-flex align-items-center justify-content-between">
            <div>
              <strong>Current Subscription Tiers: <?= e(strtoupper($plan['name'] ?? 'FREE')) ?></strong>
              <div class="small mt-1">Listing quota: <?= $userListingsCount ?> of <?= $userPlanLimit ?> used</div>
            </div>
            <button onclick="switchToTab('payments')" class="btn btn-primary btn-sm">Manage Plan</button>
          </div>
        </article>
      </div>
    </main>
  </div>
</section>

<script>
// Tab Switching Helper
function switchToTab(tabTarget) {
    if (!tabTarget) return;
    
    // Normalize target name
    const cleanTarget = tabTarget.replace('#', '').replace('tab-', '');
    
    // Hide all tab panels explicitly
    document.querySelectorAll('.provider-tab-panel').forEach(function(panel) {
        panel.style.display = 'none';
        panel.classList.remove('active');
    });
    
    // Remove active highlight from all nav links
    document.querySelectorAll('.provider-nav-menu .dash-nav-link').forEach(function(link) {
        link.classList.remove('active');
        const linkTab = link.getAttribute('data-provider-tab') || (link.getAttribute('href') ? link.getAttribute('href').replace('#', '') : '');
        if (linkTab === cleanTarget) {
            link.classList.add('active');
        }
    });

    // Display targeted panel explicitly
    const targetEl = document.getElementById('tab-' + cleanTarget) || document.getElementById(cleanTarget);
    if (targetEl) {
        targetEl.style.display = 'block';
        targetEl.classList.add('active');

        // On mobile / telephone screens (<992px), scroll smoothly to top of active panel
        if (window.innerWidth < 992) {
            targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Attach click handlers to sidebar navigation links
    document.querySelectorAll('.provider-nav-menu .dash-nav-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                const tabTarget = this.getAttribute('data-provider-tab') || href.replace('#', '');
                switchToTab(tabTarget);
                window.location.hash = tabTarget;
            }
        });
    });

    // Check initial URL hash on page load
    if (window.location.hash) {
        const hashTarget = window.location.hash.replace('#', '');
        switchToTab(hashTarget);
    } else {
        switchToTab('overview');
    }
});

// Audio & Notification Center Actions
function playChimeSound(soundFile) {
    try {
        const audio = new Audio('public/assets/audio/' + soundFile);
        audio.play();
    } catch (error) {
        console.warn('Audio play failed:', error);
    }
}

function markNotifRead(id) {
    fetch('?route=api-mark-read&id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
}

function archiveNotif(id) {
    fetch('?route=api-archive&id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
}

// Notification Polling Loop
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function() {
        try {
            const audio = new Audio('public/assets/audio/request.wav');
            audio.volume = 0;
            audio.play().catch(()=>{});
        } catch(e){}
    }, { once: true });

    setInterval(function() {
        fetch('?route=api-notifications')
            .then(res => res.json())
            .then(data => {
                if (data.unread > 0) {
                    const latestNotif = data.notifications && data.notifications.length > 0 ? data.notifications[0].message : 'You have a new alert!';
                    const msg = latestNotif.toLowerCase();
                    
                    let soundFile = 'request.wav';
                    if (msg.includes('payment') || msg.includes('subscription') || msg.includes('activated') || msg.includes('approved')) {
                        soundFile = 'success.wav';
                    } else if (msg.includes('listing')) {
                        soundFile = 'listing.wav';
                    }
                    
                    playChimeSound(soundFile);
                    
                    const toast = document.createElement('div');
                    toast.className = 'alert alert-info position-fixed start-50 translate-middle-x shadow-lg border-primary';
                    toast.style.cssText = 'bottom: 20px; z-index: 9999; min-width: 320px; animation: slideUp 0.3s ease; background-color: #0d6efd; color: #fff; border-radius: 8px;';
                    toast.innerHTML = `
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <span>🔔</span>
                                <span class="small fw-semibold">${latestNotif}</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="this.parentElement.parentElement.remove()"></button>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    fetch('?route=api-mark-notifications-read')
                        .then(() => {
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        });
                }
            })
            .catch(err => console.error('Notification poll error:', err));
    }, 4000);
});
</script>

<style>
.provider-tab-panel {
    display: none;
}
.provider-tab-panel.active {
    display: block;
    animation: fadeIn 0.25s ease;
}
.dash-nav-link svg {
    flex-shrink: 0;
}
@keyframes slideUp {
    from { transform: translate(-50%, 50px); opacity: 0; }
    to { transform: translate(-50%, 0); opacity: 1; }
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
.text-secondary-custom {
    color: #475569 !important;
}
</style>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
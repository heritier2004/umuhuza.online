<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$unreadNotifs = array_filter($notifications ?? [], fn($n) => (int)$n['is_read'] === 0 && (int)$n['is_archived'] === 0);
$readNotifs = array_filter($notifications ?? [], fn($n) => (int)$n['is_read'] === 1 && (int)$n['is_archived'] === 0);
$archivedNotifs = array_filter($notifications ?? [], fn($n) => (int)$n['is_archived'] === 1);
?>
<section class="dashboard-shell container py-4">
  <div class="row g-4 align-items-start">
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
            <h4 class="fw-bold mb-0"><?= e($user['full_name'] ?? 'Provider') ?></h4>
            <span class="badge badge-premium"><?= e($plan['name'] ?? 'Free') ?> plan</span>
          </div>
        </div>
        <nav class="nav flex-column gap-2">
          <a class="dash-nav-link active" href="#">Overview</a>
          <a class="dash-nav-link" href="#create-listing">Create listing</a>
          <a class="dash-nav-link" href="#matched-requests">Matched requests</a>
          <a class="dash-nav-link" href="#payments">Payments</a>
          <a class="dash-nav-link" href="#recent-listings">Recent listings</a>
          <a class="dash-nav-link" href="?route=listings">Marketplace</a>
        </nav>
        <div class="mt-4 p-3 rounded-4 bg-soft border border-warning-subtle">
          <div class="small text-muted-custom">Your next step</div>
          <div class="fw-semibold">Post a listing and boost visibility with a premium plan.</div>
        </div>
      </div>
    </aside>

    <main class="col-lg-9">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
        <div>
          <p class="eyebrow mb-1" data-i18n="provider_workspace">Provider workspace</p>
          <h2 class="section-title mb-1" data-i18n="dashboard_title">Your marketplace dashboard</h2>
          <p class="text-muted-custom mb-0" data-i18n="dashboard_sub">Track your plan, publish fresh listings, and respond to new leads faster.</p>
        </div>
        <a class="btn btn-primary" href="?route=listings" data-i18n="view_marketplace">View marketplace</a>
      </div>

      <div class="row g-4 mb-4">
        <article class="col-md-6 col-xl-3 stat-card p-4"><div class="text-muted-custom small">Current plan</div><div class="number text-orange"><?= e(strtoupper($plan['name'] ?? 'FREE')) ?></div><div class="text-muted-custom small"><?= e($plan['listing_limit'] ?? 5) ?> listings allowed</div></article>
        <article class="col-md-6 col-xl-3 stat-card p-4"><div class="text-muted-custom small">Remaining quota</div><div class="number text-success"><?= (int)($remainingQuota ?? 0) ?></div><div class="text-muted-custom small">Listings left this cycle</div></article>
        <article class="col-md-6 col-xl-3 stat-card p-4"><div class="text-muted-custom small">Total listings</div><div class="number text-primary"><?= count($listings ?? []) ?></div><div class="text-muted-custom small">Approved + pending</div></article>
        <article class="col-md-6 col-xl-3 stat-card p-4"><div class="text-muted-custom small">Matched requests</div><div class="number text-warning"><?= count($matchedRequests ?? []) ?></div><div class="text-muted-custom small">Inquiries in your area</div></article>
      </div>

      <div class="row g-4">
        <article id="create-listing" class="col-lg-7 panel p-4"><h5 class="fw-bold mb-3">Create a new listing</h5><p class="text-muted-custom small mb-3">Add a property or service and make it visible to buyers right away.</p><form class="row g-3" method="POST" action="?route=create-listing" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>"><div class="col-12"><label class="form-label small text-muted-custom">Listing title</label><input class="form-control" name="title" placeholder="Enter a clear listing title" required /></div><div class="col-md-6"><label class="form-label small text-muted-custom">Price</label><input class="form-control" name="price" placeholder="Amount in RWF" required /></div><div class="col-md-6"><label class="form-label small text-muted-custom">Province</label><input class="form-control" name="province" placeholder="Kigali" /></div><div class="col-md-6"><label class="form-label small text-muted-custom">District</label><input class="form-control" name="district" placeholder="Gasabo" /></div><div class="col-md-6"><label class="form-label small text-muted-custom">Sector</label><input class="form-control" name="sector" placeholder="Kacyiru" /></div><div class="col-12"><label class="form-label small text-muted-custom">Description</label><textarea class="form-control" name="description" rows="3" placeholder="Describe what buyers or clients will get"></textarea></div><div class="col-12"><label class="form-label small text-muted-custom">Listing image</label><input class="form-control" type="file" name="image" /></div><div class="col-12"><button class="btn btn-primary" type="submit">Publish listing</button></div></form></article>

        <article id="payments" class="col-lg-5 panel p-4"><h5 class="fw-bold mb-3">Upgrade plan & payments</h5><p class="text-muted-custom small mb-3">Choose a plan, pay manually, and get your listing boosted after admin approval.</p><form class="row g-3" method="POST" action="?route=upgrade-plan"><input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>"><div class="col-12"><label class="form-label small text-muted-custom">Plan</label><select class="form-select" name="plan_id"><option value="1">Free</option><option value="2">Premium</option><option value="3">Super</option></select></div><div class="col-12"><label class="form-label small text-muted-custom">Transaction / reference ID</label><input class="form-control" name="transaction_id" placeholder="Reference from your payment slip" /></div><div class="col-12"><label class="form-label small text-muted-custom">Sender name</label><input class="form-control" name="sender_name" placeholder="Name shown on the payment" /></div><div class="col-12"><label class="form-label small text-muted-custom">Sender phone</label><input class="form-control" name="sender_phone" placeholder="Phone used for payment" /></div><div class="col-12"><label class="form-label small text-muted-custom">Amount</label><input class="form-control" name="amount" placeholder="Amount sent" /></div><div class="col-12"><button class="btn btn-primary w-100" type="submit">Submit payment</button></div></form><div class="alert alert-warning mt-3 mb-0">Admin approval is required for payments and plan upgrades.</div></article>
      </div>

      <article id="matched-requests" class="panel p-4 mt-4">
        <h5 class="fw-bold mb-3">Matched Requests / Inquiries</h5>
        <?php if (($blockedLeadsCount ?? 0) > 0): ?>
          <div class="alert alert-warning d-flex align-items-center justify-content-between p-3 rounded-4 border-warning bg-warning-subtle mb-3">
            <div>
              <strong>⚠️ Delivery Limit Reached</strong>
              <p class="small mb-0 text-muted-custom">You have <?= (int)$blockedLeadsCount ?> blocked leads. Upgrade plan to receive unlimited leads.</p>
            </div>
            <a href="#payments" class="btn btn-warning btn-sm">Upgrade Plan</a>
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

      <article id="notifications-center" class="panel p-4 mt-4">
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

      <article id="recent-listings" class="panel p-4 mt-4"><h5 class="fw-bold mb-3">Recent listings</h5>
        <?php if (!empty($listings)): ?>
          <div class="recent-listings-grid">
            <?php foreach (($listings ?? []) as $item): ?>
              <div class="recent-list-item"><div><h6 class="fw-semibold mb-1"><?= e($item['title'] ?? '') ?></h6><p class="small text-muted-custom mb-0"><?= formatPrice($item['price'] ?? 0) ?> • <?= e($item['province'] ?? '') ?> / <?= e($item['district'] ?? '') ?></p></div><span class="badge badge-verified">Live</span></div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-muted-custom mb-0">You have no listings yet. Publish one to start getting matched with buyers.</p>
        <?php endif; ?>
      </article>
    </main>
  </div>
</section>

<script>
// Play local audio files
function playChimeSound(soundFile) {
    try {
        const audio = new Audio('public/assets/audio/' + soundFile);
        audio.play();
    } catch (error) {
        console.warn('Audio play failed:', error);
    }
}

// Notification Center actions
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

// Poll for notifications
document.addEventListener('DOMContentLoaded', function() {
    // Try to trigger Audio once on user interaction
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
                    // Get the latest notification message to determine sound type
                    const latestNotif = data.notifications && data.notifications.length > 0 ? data.notifications[0].message : 'You have a new alert!';
                    const msg = latestNotif.toLowerCase();
                    
                    let soundFile = 'request.wav'; // default
                    if (msg.includes('payment') || msg.includes('subscription') || msg.includes('activated') || msg.includes('approved')) {
                        soundFile = 'success.wav';
                    } else if (msg.includes('listing')) {
                        soundFile = 'listing.wav';
                    }
                    
                    playChimeSound(soundFile);
                    
                    // Create floating alert toast
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
                    
                    // Mark as read automatically so it doesn't loop play sound
                    fetch('?route=api-mark-notifications-read')
                        .then(() => {
                            // Reload the page after 2 seconds to update the lists
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
@keyframes slideUp {
    from { transform: translate(-50%, 50px); opacity: 0; }
    to { transform: translate(-50%, 0); opacity: 1; }
}
.text-secondary-custom {
    color: #475569 !important;
}
</style>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
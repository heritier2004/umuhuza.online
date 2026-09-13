<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$heroSlides = $heroSlides ?? [
  ['title' => 'Shakisha Inzu n\'Abatanga Serivisi Bizewe Mu Rwanda', 'location' => 'Kigali / Gasabo', 'rating' => 4.9, 'badge' => 'Premium', 'category' => 'Real Estate', 'phone' => '+250788367073', 'whatsapp' => '+250788367073', 'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=900&q=80'],
  ['title' => 'Tangira Abatanga Serivisi Mwiza', 'location' => 'Kigali / Nyarugenge', 'rating' => 4.8, 'badge' => 'Trending', 'category' => 'Technical Service', 'phone' => '+250788367073', 'whatsapp' => '+250788367073', 'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=900&q=80'],
];
$nearbyListings = $nearbyListings ?? [];
$featuredListings = $featuredListings ?? [];
$topProviders = $topProviders ?? [];
$recentListings = $recentListings ?? [];
$requests = $requests ?? [];
$categories = $categories ?? [
  ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-4v-6H8v6H4a1 1 0 0 1-1-1v-10.5z"/></svg>', 'title' => 'Real Estate', 'slug' => 'real-estate', 'subtitle' => 'Properties, land, and rentals'],
  ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10"/><path d="M18 17h2v2h-2z"/></svg>', 'title' => 'Technical Service', 'slug' => 'technical-service', 'subtitle' => 'Repairs, installation, and IT support'],
  ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 12h16M8 6l-4 6 4 6M16 6l4 6-4 6"/></svg>', 'title' => 'Plumbing', 'slug' => 'plumbing', 'subtitle' => 'Urgent home and business maintenance'],
  ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 2v20M2 12h20"/><circle cx="12" cy="12" r="4"/></svg>', 'title' => 'Electrical', 'slug' => 'electrical', 'subtitle' => 'Power, lighting, and wiring specialists'],
  ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 7h18M6 7v10m12-10v10M8 17h8"/><path d="M7 12h10"/></svg>', 'title' => 'Logistics', 'slug' => 'logistics', 'subtitle' => 'Transport and delivery services'],
  ['icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 7h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1z"/><path d="M8 11h8M8 15h5"/></svg>', 'title' => 'General Services', 'slug' => 'services', 'subtitle' => 'Cleaning, events, and daily help'],
];
$requests = $requests ?? [];
?>
<section class="container py-3 home-feed-shell">
  <div class="hero-slider card-hover" id="heroSlider">
    <?php foreach ($heroSlides as $index => $slide): ?>
      <?php 
        $bgStyle = '';
        $bgValue = $slide['image'] ?? '';
        if (strpos($bgValue, '#') === 0) {
          $bgStyle = 'background-color: ' . $bgValue . ';';
        } else {
          $bgStyle = 'background-image: url(' . $bgValue . '); background-size: cover; background-position: center;';
        }
      ?>
      <article class="hero-slide <?= $index === 0 ? 'active' : '' ?>" style="<?= e($bgStyle) ?>">
        <div class="hero-slide-overlay"></div>
        <div class="hero-slide-content">
          <p class="hero-eyebrow"><?= e($slide['badge'] ?? 'Featured') ?> • <?= e($slide['category'] ?? 'Marketplace') ?></p>
          <h1><?= e($slide['title'] ?? 'Marketplace listing') ?></h1>
          <p><?= e($slide['location'] ?? 'Rwanda') ?> • <?= e($slide['rating'] ?? '4.5') ?> ★</p>
          <div class="hero-actions">
            <a class="btn btn-light btn-sm" href="tel:<?= e($slide['phone'] ?? '+250788367073') ?>" data-i18n="call">Call</a>
            <a class="btn btn-success btn-sm" href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $slide['whatsapp'] ?? '+250788367073') ?>" data-i18n="whatsapp">WhatsApp</a>
          </div>
          <div class="hero-trust-indicators">
            <a href="?route=listings&category=Real+Estate" class="hero-trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
              <span data-i18n="hero_verified_agents">Verified Agents</span>
            </a>
            <a href="?route=listings" class="hero-trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
              <span data-i18n="hero_verified_providers">Verified Service Providers</span>
            </a>
            <a href="?route=listings" class="hero-trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
              <span data-i18n="hero_secure_marketplace">Secure Marketplace</span>
            </a>
            <a href="#requestModal" data-bs-toggle="modal" class="hero-trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
              <span data-i18n="hero_fast_requests">Fast Requests</span>
            </a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
    <div class="slider-dots" aria-label="Featured slides"></div>
  </div>

  <div class="search-panel card-hover mt-3">
    <div class="search-panel-header">
      <div>
        <p class="eyebrow" data-i18n="find_fast">Find fast</p>
        <h2 data-i18n="search_heading">Gura, gukodesha, tangaza inzu, cyangwa ushake serivisi</h2>
        <p class="text-muted-custom mb-0" data-i18n="search_subtitle">Ushake inzu y'umuntu, serivisi, cyangwa mutangirize umwanya mwiza.</p>
      </div>
      <span class="badge badge-verified" data-i18n="quick_filters">Quick filters</span>
    </div>
    <form class="market-search-card search-home-grid" action="?route=listings" method="GET">
      <input type="hidden" name="route" value="listings" />
      <input type="hidden" name="auto_location" value="" />
      <label class="search-field compact-field">
        <span data-i18n="location_label">Location</span>
        <input class="form-control form-control-sm" type="text" name="location" data-i18n-placeholder="location_placeholder" placeholder="Kigali, Southern..." />
      </label>
      <label class="search-field compact-field">
        <span data-i18n="category_label">Category</span>
        <input class="form-control form-control-sm" type="text" name="category" data-i18n-placeholder="category_placeholder" placeholder="Real Estate, Services..." />
      </label>
      <label class="search-field compact-field">
        <span data-i18n="price_label">Price</span>
        <input class="form-control form-control-sm" type="text" name="price" data-i18n-placeholder="price_placeholder" placeholder="Under 50M, 100M+" />
      </label>
      <label class="search-field compact-field keyword-field">
        <span data-i18n="keyword_label">Keyword</span>
        <input class="form-control form-control-sm" name="keyword" data-i18n-placeholder="keyword_placeholder" placeholder="House, repair, agent..." />
      </label>
      <button class="btn btn-search btn-sm search-action" type="submit" data-i18n="search_button">Search</button>
    </form>
    <div id="locationHint" class="small text-muted-custom mt-2" data-i18n="location_hint">Auto-detecting your location for nearby listings...</div>
  </div>



</section>

<section class="container py-4" data-section="nearby">
  <div class="section-header">
    <div class="section-header-content">
      <h2 data-i18n="nearby_section_title">Near you</h2>
      <p class="text-muted-custom mb-0" data-i18n="nearby_section_sub">Closest agents and listings sorted by proximity.</p>
    </div>
    <span class="section-header-badge blue">📍 Live location suggestions</span>
  </div>
  <div class="nearby-grid">
    <?php foreach (array_slice($nearbyListings, 0, 6) as $index => $item): 
      $planTier = strtolower($item['plan_name'] ?? 'standard');
      $isAgent = stripos($item['category_name'] ?? '', 'Real Estate') !== false || stripos($item['category_name'] ?? '', 'Property') !== false;
      $planBadgeClass = 'badge-standard';
      $planBadgeText = 'STANDARD';
      if (strpos($planTier, 'super') !== false) {
        $planBadgeClass = 'badge-super-premium';
        $planBadgeText = 'SUPER PREMIUM';
      } elseif (strpos($planTier, 'premium') !== false) {
        $planBadgeClass = 'badge-premium-tier';
        $planBadgeText = 'PREMIUM';
      }
    ?>
      <article class="listing-card card-hover" 
               data-listing-kind="<?= $isAgent ? 'agent' : 'service' ?>" 
               data-plan-tier="<?= strpos($planTier, 'super') !== false ? 'super-premium' : (strpos($planTier, 'premium') !== false ? 'premium' : 'standard') ?>"
               data-province="<?= e($item['province'] ?? '') ?>"
               data-district="<?= e($item['district'] ?? '') ?>">
        <img class="listing-visual" src="<?= e(listingCoverUrl($item)) ?>" alt="<?= e($item['title']) ?>" />
        <div class="listing-content">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <strong><?= e($item['title']) ?></strong>
            <span class="badge-near-you"><?= sprintf('%.1f km', 1.0 + $index * 0.4) ?></span>
          </div>
          <p class="text-muted-custom small mb-2"><?= e($item['description'] ?? 'Verified marketplace listing with trusted contact details.') ?></p>
          <div class="small text-muted-custom mb-2 d-flex align-items-center gap-2">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
            <?= e($item['province'] ?? 'Rwanda') ?> / <?= e($item['district'] ?? 'Nationwide') ?> • <?= e($item['rating'] ?? '4.5') ?> ★
          </div>
          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="<?= $planBadgeClass ?>"><?= $planBadgeText ?></span>
            <span class="badge-new"><?= e($item['category_name'] ?? 'Service') ?></span>
            <span class="badge badge-verified"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="margin-right:2px; vertical-align:middle;"><polyline points="20 6 9 17 4 12"/></svg>Verified</span>
          </div>
          <div class="small text-dark fw-semibold mb-3">Provider: <?= e($item['provider_name'] ?? 'Verified provider') ?></div>
          <div class="listing-actions compact-actions">
            <a class="btn btn-call btn-sm" href="tel:<?= e($item['phone'] ?? '+250788367073') ?>">Call</a>
            <a class="btn btn-whatsapp btn-sm" href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $item['whatsapp'] ?? '+250788367073') ?>">WhatsApp</a>
          </div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="container py-4" data-section="premium">
  <div class="section-header">
    <div class="section-header-content">
      <h2 data-i18n="featured_listings_title">Premium listings</h2>
      <p class="text-muted-custom mb-0" data-i18n="featured_listings_sub">Boosted picks prioritized for visibility and trust.</p>
    </div>
    <span class="section-header-badge"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none" style="vertical-align: middle; margin-right: 4px;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Premium visibility</span>
  </div>
  <div class="featured-scroll">
    <?php foreach ($featuredListings as $item): 
      $isAgent = stripos($item['category_name'] ?? '', 'Real Estate') !== false || stripos($item['category_name'] ?? '', 'Property') !== false;
    ?>
      <article class="featured-card card-hover" data-listing-kind="<?= $isAgent ? 'agent' : 'service' ?>">
        <img class="featured-thumb" src="<?= e(listingCoverUrl($item)) ?>" alt="<?= e($item['title'] ?? 'Featured listing') ?>" />
        <div class="featured-body">
          <span class="badge-premium-tier">PREMIUM</span>
          <h3><?= e($item['title'] ?? 'Featured listing') ?></h3>
          <p><?= e($item['province'] ?? 'Rwanda') ?> / <?= e($item['district'] ?? 'Nationwide') ?></p>
          <div class="small-muted"><?= formatPrice($item['price'] ?? 0) ?> • <?= e($item['rating'] ?? '4.5') ?> ★</div>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="container py-4" data-section="providers">
  <div class="section-header">
    <div class="section-header-content">
      <h2 data-i18n="top_rated_providers_title">Top rated providers</h2>
      <p class="text-muted-custom mb-0" data-i18n="top_rated_providers_sub">Verified profiles ranked by local rating and activity.</p>
    </div>
    <span class="section-header-badge success"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="vertical-align: middle; margin-right: 4px;"><polyline points="20 6 9 17 4 12"/></svg>Marketplace trusted</span>
  </div>
  <div class="provider-grid">
    <?php foreach ($topProviders as $provider): 
      $isAgent = stripos($provider['service_category'] ?? '', 'Real Estate') !== false || stripos($provider['service_category'] ?? '', 'Property') !== false;
    ?>
      <article class="provider-card card-hover" data-listing-kind="<?= $isAgent ? 'agent' : 'service' ?>" data-provider-type="<?= $isAgent ? 'agent' : 'service' ?>">
        <div class="provider-head">
          <?php if (!empty($provider['profile_image'])): ?>
            <img class="provider-avatar" src="<?= e($provider['profile_image']) ?>" alt="<?= e($provider['full_name'] ?? $provider['username'] ?? 'Provider') ?>" loading="lazy" />
          <?php else: ?>
            <div class="provider-avatar"><?= strtoupper(substr(($provider['username'] ?? $provider['full_name'] ?? 'PR'), 0, 2)) ?></div>
          <?php endif; ?>
          <div>
            <h3><?= e($provider['full_name'] ?? $provider['username'] ?? 'Verified provider') ?></h3>
            <p><?= e($provider['service_category'] ?? 'General Service') ?> • <?= e($provider['rating'] ?? '4.5') ?> ★</p>
          </div>
        </div>
        <div class="badges"><span class="badge badge-verified">Verified</span><span class="badge badge-premium"><?= e($provider['distance'] ?? '1.0 km') ?></span></div>
        <div class="listing-actions compact-actions">
          <a class="btn btn-call btn-sm" href="tel:<?= e($provider['phone'] ?? '+250788367073') ?>">Call</a>
          <a class="btn btn-whatsapp btn-sm" href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $provider['whatsapp'] ?? '+250788367073') ?>">WhatsApp</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="container py-4" data-section="activity">
  <div class="row g-4">
    <article class="col-lg-12">
      <div class="panel p-4 h-100">
        <div class="mb-3">
          <h2 class="section-title mb-1" data-i18n="recent_activity_title">Recent activity</h2>
          <p class="text-muted-custom mb-0" data-i18n="recent_activity_sub">New listings and live requests from the community.</p>
        </div>
        <div class="activity-feed">
          <?php foreach ($recentListings as $item): ?>
            <article class="activity-item">
              <div class="activity-dot"></div>
              <div>
                <strong><?= e($item['title'] ?? 'Marketplace update') ?></strong>
                <p class="text-muted-custom small mb-1"><?= e($item['category_name'] ?? 'Marketplace') ?> • <?= e($item['province'] ?? 'Rwanda') ?> / <?= e($item['district'] ?? 'Nationwide') ?></p>
                <span class="badge-new">New</span>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </article>
  </div>
</section>

<!-- Conversion Cards Section -->
<section class="conversion-section">
  <div class="container">
    <div style="text-align: center; margin-bottom: 32px;">
      <h2 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 12px; color: #0f172a;" data-i18n="join_title">Ready to join UMUHUZA.ONLINE?</h2>
      <p style="color: #475569; font-size: 1rem; max-width: 500px; margin: 0 auto;" data-i18n="join_sub">Choose your role and start connecting with buyers, renters, or service seekers today.</p>
    </div>
    <div class="conversion-cards-grid">
      <div class="conversion-card agent-card">
        <div class="conversion-card-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary);"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <h3 data-i18n="become_agent">Become an Agent</h3>
        <p data-i18n="become_agent_desc">Sell or rent properties and reach verified buyers and renters across Rwanda</p>
        <a href="?route=register" class="btn btn-outline-primary btn-sm" data-i18n="get_started">Get started</a>
      </div>
      <div class="conversion-card">
        <div class="conversion-card-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary);"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <h3 data-i18n="become_provider">Become a Service Provider</h3>
        <p data-i18n="become_provider_desc">Offer your services and receive direct requests from clients who need your expertise</p>
        <a href="?route=register" class="btn btn-outline-primary btn-sm" data-i18n="get_started">Get started</a>
      </div>
      <div class="conversion-card">
        <div class="conversion-card-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary);"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <h3 data-i18n="browse_connect">Browse & Connect</h3>
        <p data-i18n="browse_connect_desc">Find trusted agents, service providers, and post requests for the services you need</p>
        <a href="?route=listings" class="btn btn-outline-primary btn-sm" data-i18n="explore_now">Explore now</a>
      </div>
    </div>
  </div>
</section>

<section id="request" class="container py-4">
  <div class="panel p-4 p-lg-5 bg-primary-subtle border-primary-subtle">
    <div class="row g-4 align-items-center">
      <div class="col-lg-8">
        <h2 class="section-title mb-2" data-i18n="request_callout_title">Need a service or property?</h2>
        <p class="text-muted-custom mb-0" data-i18n="request_callout_sub">Submit your request and verified providers in Rwanda will contact you directly. No login required.</p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <button class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#requestModal" data-i18n="submit_request_button">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
          Submit request
        </button>
      </div>
    </div>
  </div>
</section>


<?php include __DIR__ . '/../layouts/footer.php'; ?>
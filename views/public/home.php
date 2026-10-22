<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$heroSlides = !empty($heroSlides) ? $heroSlides : [
  [
    'title' => 'Rwanda’s Verified Marketplace & Services Hub',
    'location' => 'Kigali & All Provinces',
    'rating' => 5.0,
    'badge' => 'UMUHUZA',
    'category' => 'Official Marketplace',
    'phone' => '',
    'whatsapp' => '',
    'image' => 'public/assets/logo_wide.png'
  ]
];
$nearbyListings = $nearbyListings ?? [];
$featuredListings = $featuredListings ?? [];
$topProviders = $topProviders ?? [];
$recentListings = $recentListings ?? [];
$requests = $requests ?? [];
$categories = $categories ?? [
  ['icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>', 'title' => 'Real Estate & Properties', 'slug' => 'real-estate', 'subtitle' => 'Houses, apartments, land & commercial property rentals'],
  ['icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 0-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 0-3-3l6.91-6.91a6 6 0 0 0 7.94-7.94l-3.76 3.76z"></path></svg>', 'title' => 'Technical & IT Services', 'slug' => 'technical-service', 'subtitle' => 'TV repairs, computers, appliances & IT support'],
  ['icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>', 'title' => 'Plumbing & Drainage', 'slug' => 'plumbing', 'subtitle' => 'Pipe leak repair, water pumps & bathroom maintenance'],
  ['icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>', 'title' => 'Electrical & Power', 'slug' => 'electrical', 'subtitle' => 'Wiring, solar systems, generators & lighting installation'],
  ['icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>', 'title' => 'Logistics & Moving', 'slug' => 'logistics', 'subtitle' => 'Transport trucks, house moving & cargo deliveries'],
  ['icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>', 'title' => 'General Services & Events', 'slug' => 'services', 'subtitle' => 'Home cleaning, event catering, security & daily help'],
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
            <?php if (!empty($slide['phone'])): ?>
              <a class="btn btn-light btn-sm" href="tel:<?= e($slide['phone']) ?>" data-i18n="call">Call</a>
            <?php endif; ?>
            <?php if (!empty($slide['whatsapp']) || !empty($slide['phone'])): ?>
              <a class="btn btn-success btn-sm" href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $slide['whatsapp'] ?: $slide['phone']) ?>" data-i18n="whatsapp">WhatsApp</a>
            <?php endif; ?>
            <a class="btn btn-outline-light btn-sm" href="?route=listings">Browse All</a>
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
            <?php if (!empty($item['phone'])): ?>
              <a class="btn btn-call btn-sm" href="tel:<?= e($item['phone']) ?>">Call</a>
            <?php endif; ?>
            <?php if (!empty($item['whatsapp']) || !empty($item['phone'])): ?>
              <a class="btn btn-whatsapp btn-sm" href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $item['whatsapp'] ?: $item['phone']) ?>">WhatsApp</a>
            <?php endif; ?>
            <a class="btn btn-outline-primary btn-sm" href="?route=listing&id=<?= (int)$item['id'] ?>">Details</a>
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

<!-- How System Works Section -->
<section id="how-it-works" class="container py-5">
  <div class="section-header text-center mb-5" style="max-width: 650px; margin: 0 auto;">
    <span class="badge badge-super mb-2" data-i18n="how_it_works_badge">⚡ SIMPLE & INSTANT</span>
    <h2 class="fw-bold fs-2" data-i18n="how_it_works_title">How UMUHUZA.ONLINE Works</h2>
    <p class="text-muted-custom fs-6" data-i18n="how_it_works_sub">Connecting service providers, real estate agents, and clients fast and reliably across Rwanda.</p>
  </div>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="panel p-4 text-center h-100 card-hover border-top border-4 border-primary">
        <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </div>
        <h4 class="fw-bold mb-2" data-i18n="step1_title">1. Submit request or search</h4>
        <p class="text-muted-custom small mb-0" data-i18n="step1_desc">Search properties or submit a service request in seconds. No login required.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel p-4 text-center h-100 card-hover border-top border-4 border-success">
        <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <h4 class="fw-bold mb-2" data-i18n="step2_title">2. Instant local matching</h4>
        <p class="text-muted-custom small mb-0" data-i18n="step2_desc">Our system routes your request directly to verified providers and agents in your sector or district.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel p-4 text-center h-100 card-hover border-top border-4 border-warning">
        <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.79.63 2.65a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.43-1.18a2 2 0 0 1 2.11-.45c.86.3 1.75.51 2.65.63A2 2 0 0 1 22 16.92z"/></svg>
        </div>
        <h4 class="fw-bold mb-2" data-i18n="step3_title">3. Direct contact</h4>
        <p class="text-muted-custom small mb-0" data-i18n="step3_desc">Call or message property owners or service providers on WhatsApp directly with zero commission.</p>
      </div>
    </div>
  </div>
</section>

<!-- Subscription Plans Section -->
<section id="subscriptions" class="container py-5">
  <div class="section-header text-center mb-5" style="max-width: 650px; margin: 0 auto;">
    <span class="badge badge-super mb-2" data-i18n="pricing_badge">💎 SUBSCRIPTIONS & PLANS</span>
    <h2 class="fw-bold fs-2" data-i18n="pricing_title">Service Provider & Agent Subscriptions</h2>
    <p class="text-muted-custom fs-6" data-i18n="pricing_sub">Choose the right plan to boost your visibility and get more clients across Rwanda.</p>
  </div>
  <div class="row g-4 justify-content-center">
    <!-- Free Plan -->
    <div class="col-lg-4 col-md-6">
      <div class="panel p-4 h-100 d-flex flex-column card-hover position-relative">
        <div class="mb-3">
          <span class="badge bg-secondary mb-2" data-i18n="free_plan_tag">BASIC</span>
          <h3 class="fw-bold" data-i18n="free_plan_name">Free Plan</h3>
          <div class="display-6 fw-bold text-dark my-2">0 <small class="fs-6 text-muted-custom">RWF / mo</small></div>
          <p class="text-muted-custom small" data-i18n="free_plan_desc">Ideal for new users getting started on the platform.</p>
        </div>
        <hr />
        <ul class="list-unstyled flex-fill mb-4 small space-y-2">
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <span data-i18n="free_feat_1">Up to 5 listings per week</span></li>
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <span data-i18n="free_feat_2">Standard search visibility</span></li>
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <span data-i18n="free_feat_3">5 free instant leads monthly</span></li>
          <li class="d-flex align-items-center gap-2 mb-2 text-muted"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> <span data-i18n="free_feat_4">Delayed leads throttling</span></li>
        </ul>
        <a href="?route=register" class="btn btn-outline-primary w-100" data-i18n="start_free">Start Free</a>
      </div>
    </div>

    <!-- Premium Plan -->
    <div class="col-lg-4 col-md-6">
      <div class="panel p-4 h-100 d-flex flex-column card-hover position-relative border border-2 border-primary shadow-sm" style="background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);">
        <div class="position-absolute top-0 end-0 m-3"><span class="badge badge-super-premium" data-i18n="popular">MOST POPULAR</span></div>
        <div class="mb-3">
          <span class="badge bg-primary mb-2" data-i18n="premium_plan_tag">GROWTH</span>
          <h3 class="fw-bold text-primary" data-i18n="premium_plan_name">Premium Plan</h3>
          <div class="display-6 fw-bold text-dark my-2">3,000 <small class="fs-6 text-muted-custom">RWF / mo</small></div>
          <p class="text-muted-custom small" data-i18n="premium_plan_desc">Designed for agents and providers who want more client leads.</p>
        </div>
        <hr />
        <ul class="list-unstyled flex-fill mb-4 small space-y-2">
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <strong data-i18n="prem_feat_1">Up to 20 listings per week</strong></li>
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <span data-i18n="prem_feat_2">Priority search ranking</span></li>
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <strong data-i18n="prem_feat_3">Instant lead notifications</strong></li>
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <span data-i18n="prem_feat_4">Verified Provider Badge</span></li>
        </ul>
        <a href="?route=register" class="btn btn-primary w-100 fw-semibold" data-i18n="get_premium">Upgrade to Premium</a>
      </div>
    </div>

    <!-- Super Premium Plan -->
    <div class="col-lg-4 col-md-6">
      <div class="panel p-4 h-100 d-flex flex-column card-hover position-relative">
        <div class="mb-3">
          <span class="badge bg-warning text-dark mb-2" data-i18n="super_plan_tag">VIP EXECUTIVE</span>
          <h3 class="fw-bold text-dark" data-i18n="super_plan_name">Super VIP Plan</h3>
          <div class="display-6 fw-bold text-dark my-2">5,000 <small class="fs-6 text-muted-custom">RWF / mo</small></div>
          <p class="text-muted-custom small" data-i18n="super_plan_desc">Get #1 top placement among agents and service providers in Rwanda.</p>
        </div>
        <hr />
        <ul class="list-unstyled flex-fill mb-4 small space-y-2">
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <strong data-i18n="super_feat_1">Unlimited listings</strong></li>
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <strong data-i18n="super_feat_2">#1 Top Placement in search</strong></li>
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <span data-i18n="super_feat_3">Instant lead delivery & SMS</span></li>
          <li class="d-flex align-items-center gap-2 mb-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="text-success"><polyline points="20 6 9 17 4 12"/></svg> <span data-i18n="super_feat_4">VIP Featured Badge & Dedicated Support</span></li>
        </ul>
        <a href="?route=register" class="btn btn-warning w-100 fw-semibold text-dark" data-i18n="get_super">Get Super VIP</a>
      </div>
    </div>
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
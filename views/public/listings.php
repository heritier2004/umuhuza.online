<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="container py-5">
  <div class="d-flex flex-column flex-lg-row gap-4">
    <aside class="col-lg-3">
      <div class="panel p-4 sticky-top" style="top:90px;">
        <h4 class="fw-bold mb-3">Filter marketplace</h4>
        <form class="row g-3" method="GET" action="?route=listings">
          <input type="hidden" name="route" value="listings" />
          <input type="hidden" name="auto_location" value="" />
          <div class="col-12"><input class="form-control" type="text" name="keyword" placeholder="Keyword" value="<?= e($_GET['keyword'] ?? '') ?>" /></div>
          <div class="col-12"><input class="form-control" type="text" name="category" placeholder="Category" value="<?= e($_GET['category'] ?? '') ?>" /></div>
          <div class="col-12"><input class="form-control" type="text" name="location" placeholder="Province / District" value="<?= e($_GET['location'] ?? '') ?>" /></div>
          <div class="col-12"><input class="form-control" type="text" name="price" placeholder="Max price" value="<?= e($_GET['budget'] ?? '') ?>" /></div>
          <div class="col-12"><button class="btn btn-primary w-100" type="submit">Apply filters</button></div>
        </form>
        <div class="mt-4 small text-muted-custom">Premium listings appear first, followed by verified providers and newest posts.</div>
      </div>
    </aside>
    <div class="flex-fill">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3 mb-3">
        <div>
          <h2 class="section-title mb-1">Marketplace listings</h2>
          <p class="text-muted-custom mb-0">Real properties, professional services, and verified providers.</p>
        </div>
        <span class="badge badge-super">Sorted by priority</span>
      </div>

      <div class="search-panel card-hover mb-3">
        <form class="market-search-card search-home-grid" action="?route=listings" method="GET">
          <input type="hidden" name="route" value="listings" />
          <input type="hidden" name="auto_location" value="" />
          <label class="search-field compact-field">
            <span>Location</span>
            <input class="form-control form-control-sm" type="text" name="location" placeholder="Kigali, Southern..." />
          </label>
          <label class="search-field compact-field">
            <span>Category</span>
            <input class="form-control form-control-sm" type="text" name="category" placeholder="Real Estate, Services..." />
          </label>
          <label class="search-field compact-field">
            <span>Price</span>
            <input class="form-control form-control-sm" type="text" name="price" placeholder="Under 50M, 100M+" />
          </label>
          <label class="search-field compact-field keyword-field">
            <span>Keyword</span>
            <input class="form-control form-control-sm" name="keyword" placeholder="House, repair, agent..." value="<?= e($_GET['keyword'] ?? '') ?>" />
          </label>
          <button class="btn btn-search btn-sm search-action" type="submit">Search</button>
        </form>
      </div>

      <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        <?php foreach (($listings ?? []) as $item): ?>
          <article class="col">
            <a href="?route=listing&id=<?= (int)$item['id'] ?>" class="listing-card card-hover h-100 d-block text-dark" data-listing-kind="<?= stripos($item['category_name'] ?? '', 'Real Estate') !== false || stripos($item['category_name'] ?? '', 'Property') !== false ? 'agent' : 'service' ?>">
              <img class="listing-visual" src="<?= e(listingCoverUrl($item)) ?>" alt="<?= e($item['title']) ?>" loading="lazy" />
              <div class="listing-content p-3">
                <div class="d-flex flex-wrap gap-2 mb-2">
                  <span class="badge badge-super"><?= e($item['plan_name'] ?? 'Free') ?></span>
                  <span class="badge badge-premium"><?= e($item['category_name'] ?? 'General') ?></span>
                  <span class="badge badge-verified">Verified</span>
                </div>
                <h5 class="fw-bold mb-2"><?= e($item['title']) ?></h5>
                <p class="text-muted-custom small mb-3"><?= e($item['description'] ?? 'No description available yet.') ?></p>
                <div class="fw-bold text-primary mb-2"><?= formatPrice($item['price']) ?></div>
                <div class="small text-muted-custom d-flex align-items-center gap-2 mb-2"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11z"/><circle cx="12" cy="10" r="2.5"/></svg><?= e($item['province']) ?> / <?= e($item['district']) ?></div>
                <div class="small text-warning">★ <?= e($item['rating'] ?? '4.8') ?> rating</div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
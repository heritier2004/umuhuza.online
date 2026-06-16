<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="container py-5">
  <div class="row g-4">
    <aside class="col-lg-4 panel p-4">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="rounded-circle bg-primary text-white d-grid place-items-center" style="width:68px;height:68px;font-size:1.1rem;"><?= strtoupper(substr(e(($listing['provider_name'] ?? 'Provider')),0,2)) ?></div>
        <div>
          <h2 class="fw-bold mb-1"><?= e(($listing['provider_name'] ?? 'Provider')) ?></h2>
          <p class="text-muted-custom small mb-0">Verified provider • <?= e(($listing['rating'] ?? '4.5')) ?> rating</p>
        </div>
      </div>
      <p class="small text-muted-custom mb-1"><strong>Phone:</strong> <?= e(($listing['phone'] ?? '')) ?></p>
      <p class="small text-muted-custom mb-1"><strong>WhatsApp:</strong> <?= e(($listing['whatsapp'] ?? '')) ?></p>
      <p class="small text-muted-custom mb-1"><strong>Plan:</strong> <?= e(($listing['plan_name'] ?? 'Free')) ?></p>
      <p class="small text-muted-custom mb-1"><strong>Status:</strong> <?= e(($listing['provider_status'] ?? 'active')) ?></p>
      <div class="d-flex gap-2 mt-3">
        <a class="btn btn-primary" href="tel:<?= e(($listing['phone'] ?? '')) ?>">Call</a>
        <a class="btn btn-success" href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $listing['whatsapp'] ?? '') ?>">WhatsApp</a>
      </div>
    </aside>
    <main class="col-lg-8">
      <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
          <h3 class="section-title mb-1">Listings by this provider</h3>
          <p class="text-muted-custom mb-0">Real marketplace inventory from the same verified business profile.</p>
        </div>
        <span class="badge badge-super"><?= count($providerListings ?? []) ?> active listings</span>
      </div>
      <div class="row g-4">
        <?php foreach (($providerListings ?? []) as $item): ?>
          <article class="col-md-6">
            <a href="?route=listing&id=<?= (int)$item['id'] ?>" class="listing-card card-hover p-3 h-100 d-block text-dark">
              <img class="listing-visual" src="<?= e(listingCoverUrl($item)) ?>" alt="<?= e($item['title'] ?? 'Listing') ?>" />
              <div class="mt-3">
                <div class="d-flex gap-2 mb-2"><span class="badge badge-super"><?= e($item['plan_name'] ?? 'Free') ?></span><span class="badge badge-premium"><?= e($item['category_name'] ?? 'General') ?></span></div>
                <h5 class="fw-bold mb-1"><?= e($item['title']) ?></h5>
                <p class="small text-muted-custom mb-2"><?= e($item['description']) ?></p>
                <div class="fw-bold text-primary mb-1"><?= formatPrice($item['price']) ?></div>
                <div class="small text-muted-custom d-flex align-items-center gap-2"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11z"/><circle cx="12" cy="10" r="2.5"/></svg><?= e($item['province']) ?> / <?= e($item['district']) ?></div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </main>
  </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

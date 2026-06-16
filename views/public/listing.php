<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="container py-5">
  <div class="row g-4">
    <article class="col-lg-8 panel p-4">
      <img class="listing-visual" src="<?= e(listingCoverUrl($listing ?? [])) ?>" alt="<?= e(($listing['title'] ?? 'Listing')) ?>" />
      <div class="d-flex gap-2 mt-3 mb-2"><span class="badge badge-super"><?= e(($listing['plan_name'] ?? 'Free')) ?></span><span class="badge badge-verified">Verified</span><span class="badge badge-premium"><?= e(($listing['category_name'] ?? 'General')) ?></span></div>
      <h1 class="fw-bold mb-2"><?= e(($listing['title'] ?? 'Listing not found')) ?></h1>
      <p class="text-muted-custom"><?= e(($listing['description'] ?? '')) ?></p>
      <p class="mb-1"><strong>Location:</strong> <?= e(($listing['province'] ?? '')) ?> / <?= e(($listing['district'] ?? '')) ?> / <?= e(($listing['sector'] ?? '')) ?> / <?= e(($listing['cell'] ?? '')) ?></p>
      <p class="mb-1"><strong>Category:</strong> <?= e(($listing['category_name'] ?? '')) ?></p>
      <p class="mb-1"><strong>Plan:</strong> <?= e(($listing['plan_name'] ?? '')) ?></p>
      <p class="fw-bold text-primary fs-5 mb-3"><?= formatPrice(($listing['price'] ?? 0)) ?></p>
      <div class="d-flex gap-2"><a class="btn btn-primary" href="tel:<?= e(($listing['phone'] ?? '')) ?>">Call now</a><a class="btn btn-success" href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $listing['whatsapp'] ?? '') ?>">WhatsApp</a></div>
    </article>
    <aside class="col-lg-4 panel p-4">
      <h4 class="fw-bold mb-3">Provider profile</h4>
      <div class="d-flex align-items-center gap-3 mb-3"><div class="rounded-circle bg-primary text-white d-grid place-items-center" style="width:56px;height:56px"><?= strtoupper(substr(e(($listing['provider_name'] ?? 'Provider')),0,2)) ?></div><div><h5 class="mb-1"><?= e(($listing['provider_name'] ?? 'Provider')) ?></h5><p class="text-muted-custom small mb-0">Verified provider • <?= e(($listing['rating'] ?? '4.5')) ?> rating</p></div></div>
      <p class="small text-muted-custom">Phone: <?= e(($listing['phone'] ?? '')) ?></p>
      <p class="small text-muted-custom">WhatsApp: <?= e(($listing['whatsapp'] ?? '')) ?></p>
      <a class="btn btn-outline-primary w-100 mt-2" href="?route=provider-profile&id=<?= (int)($listing['id'] ?? 0) ?>">View all listings by this provider</a>
      <form class="row g-3 mt-3" method="POST" action="?route=submit-request"><input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>"><input class="form-control" name="name" placeholder="Name" required /><input class="form-control" name="phone" placeholder="Phone" required /><input class="form-control" name="whatsapp" placeholder="WhatsApp" /><textarea class="form-control" name="description" rows="4" placeholder="Describe your request" required></textarea><button class="btn btn-primary w-100" type="submit">Submit request</button></form>
    </aside>
  </div>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
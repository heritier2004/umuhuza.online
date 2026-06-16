</main>
<nav class="bottom-nav" aria-label="Primary">
  <div class="bottom-nav-content">
    <a class="nav-pill active" href="?route=home"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 11.5L12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-5H9v5H4a1 1 0 0 1-1-1v-8.5z"/></svg><small data-i18n="home">Home</small></a>
    <a class="nav-pill" href="?route=listings"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h5"/></svg><small data-i18n="listings">Listings</small></a>
    <a class="nav-pill" href="#requestModal" data-bs-toggle="modal"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg><small data-i18n="request">Requests</small></a>
    <a class="nav-pill" href="?route=<?= isLoggedIn() ? 'provider-dashboard' : 'login' ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M5 20a7 7 0 0 1 14 0"/></svg><small data-i18n="profile">Profile</small></a>
    <a class="nav-pill" href="?route=about"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2v20M2 12h20"/></svg><small data-i18n="about">About</small></a>
  </div>
</nav>
<div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content request-modal-card">
      <div class="modal-header border-0"><h5 class="modal-title fw-bold">Submit request</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><form method="POST" action="?route=submit-request" class="row g-3"><div class="col-12"><label class="form-label small text-muted-custom">Name</label><div class="input-group"><span class="input-group-text"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M5 20a7 7 0 0 1 14 0"/></svg></span><input class="form-control" name="name" placeholder="Full name" required /></div></div><div class="col-md-6"><label class="form-label small text-muted-custom">Phone</label><div class="input-group"><span class="input-group-text"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.79.63 2.65a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.43-1.18a2 2 0 0 1 2.11-.45c.86.3 1.75.51 2.65.63A2 2 0 0 1 22 16.92z"/></svg></span><input class="form-control" name="phone" placeholder="Phone" required /></div></div><div class="col-md-6"><label class="form-label small text-muted-custom">WhatsApp</label><div class="input-group"><span class="input-group-text"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 3H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h4l3 3 3-3h5a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1z"/><path d="M8 10h8M8 13h5"/></svg></span><input class="form-control" name="whatsapp" placeholder="WhatsApp" /></div></div><div class="col-md-6"><label class="form-label small text-muted-custom">Location</label><div class="input-group"><span class="input-group-text"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11z"/><circle cx="12" cy="10" r="2.5"/></svg></span><input class="form-control" name="province" placeholder="Location" /></div></div><div class="col-md-6"><label class="form-label small text-muted-custom">Request type</label><input class="form-control" name="type" placeholder="Property / Service" /></div><div class="col-12"><label class="form-label small text-muted-custom">Budget</label><input class="form-control" name="budget" placeholder="Budget" /></div><div class="col-12"><label class="form-label small text-muted-custom">Description</label><textarea class="form-control" name="description" rows="4" placeholder="Describe what you need" required></textarea></div><div class="col-12"><button class="btn btn-primary w-100 d-inline-flex align-items-center justify-content-center gap-2" type="submit"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>Send request</button></div></form></div>
    </div>
  </div>
</div>
<?php if (($_GET['route'] ?? '') !== 'admin-dashboard'): ?>
<footer class="footer text-white py-5 mt-5">
  <div class="container row g-4">
    <div class="col-md-4"><h5>About UMUHUZA.ONLINE</h5><p class="text-light">UMUHUZA.ONLINE is a local property and service platform that helps buyers, renters, agents, and service providers discover trusted opportunities, request help, and connect faster in Rwanda.</p></div>
    <div class="col-md-4"><h5>Quick Links</h5><a href="?route=home" class="d-block text-light mb-2">Home</a><a href="?route=listings" class="d-block text-light mb-2">Listings</a><a href="?route=register" class="d-block text-light mb-2">Provider Registration</a></div>
    <div class="col-md-4"><h5>Contact</h5><p class="mb-1 text-light">Call: <a href="tel:+250788367073" class="text-light text-decoration-none fw-semibold">+250 788 367 073</a></p><p class="mb-1 text-light">WhatsApp: <a href="https://wa.me/250788367073" class="text-light text-decoration-none fw-semibold" target="_blank">+250 788 367 073</a></p><p class="mb-0 text-light">Terms & Privacy</p></div>
  </div>
</footer>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="public/assets/js/app.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/app.js') ?>"></script>
<script src="public/assets/js/onboarding-premium.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/onboarding-premium.js') ?>"></script>
<script src="public/assets/js/wizard.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/wizard.js') ?>"></script>
<script src="public/assets/js/marketplace-ui.js?v=<?= filemtime(__DIR__ . '/../../public/assets/js/marketplace-ui.js') ?>"></script>
</body>
</html>

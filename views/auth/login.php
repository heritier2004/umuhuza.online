<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="auth-shell container py-5">
  <article class="auth-card row overflow-hidden w-100" style="max-width:1200px;">
    <aside class="auth-side col-lg-5 p-4 p-lg-5 d-flex flex-column justify-content-between">
      <div>
        <div class="eyebrow" data-i18n="welcome_back">Welcome back</div>
        <h2 class="fw-bold mb-3" data-i18n="login_title">Welcome back to Rwanda Marketplace</h2>
        <p class="mb-4" data-i18n="login_intro">Sign in to view trusted agents, premium listings, and fresh leads in one clean marketplace flow.</p>
        <div class="auth-badges mb-4">
          <span class="auth-pill" data-i18n="login_chip_verified">Verified agents</span>
          <span class="auth-pill" data-i18n="login_chip_fast">Fast matching</span>
          <span class="auth-pill" data-i18n="login_chip_premium">Premium visibility</span>
        </div>
      </div>
    </aside>
    <div class="col-lg-7 p-4 p-lg-5 bg-white auth-form-card">
      <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <div>
          <h3 class="fw-bold mb-1" data-i18n="login_heading">Sign in to your marketplace account</h3>
          <p class="text-muted-custom mb-0" data-i18n="login_sub">Use your phone number or email and continue where you left off.</p>
        </div>
        <span class="badge rounded-pill bg-soft" data-i18n="login_badge">Secure</span>
      </div>
      <form class="row g-3" method="POST" action="?route=login-submit">
        <input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>">
        <div class="col-12"><label class="form-label small text-muted-custom" data-i18n="login_identifier">Phone or email</label><input class="form-control" type="text" name="identifier" placeholder="077... or you@example.com" required /></div>
        <div class="col-12">
          <label class="form-label small text-muted-custom" data-i18n="login_password">Password</label>
          <div class="input-group">
            <input class="form-control" type="password" name="password" id="loginPassword" placeholder="Enter password" required />
            <button class="btn btn-outline-secondary" type="button" data-password-toggle="true" data-password-target="loginPassword">Show</button>
          </div>
        </div>
        <div class="col-12 d-flex justify-content-between align-items-center gap-2"><label class="small text-muted-custom"><input type="checkbox" class="me-2" /> <span data-i18n="login_remember">Remember me</span></label><a href="#" class="small text-primary" data-i18n="login_forgot">Forgot password?</a></div>
        <div class="col-12"><button class="btn btn-primary w-100 btn-lg" type="submit" data-i18n="login_cta">Login & explore marketplace</button></div>
        <div class="col-12 text-center mt-2">
          <span class="small text-muted-custom" data-i18n="login_no_account">Don't have an account?</span>
          <a href="?route=register" class="small fw-bold text-primary ms-1" data-i18n="login_register_link">Register here</a>
        </div>
      </form>
    </div>
  </article>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
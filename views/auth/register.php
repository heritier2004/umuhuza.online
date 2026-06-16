<?php include __DIR__ . '/../layouts/header.php'; ?>
<section class="auth-shell container py-5">
  <article class="auth-card row overflow-hidden w-100" style="max-width:1200px;">
    <aside class="auth-side col-lg-6 p-4 p-lg-5">
      <div class="eyebrow">Marketplace onboarding</div>
      <h2 class="fw-bold mb-3">Buy, sell, and request trusted services in Rwanda</h2>
      <p class="text-light mb-4">Launch your profile in minutes with a premium look, verified trust badges, and fast lead matching for real estate and service providers.</p>
      <div class="auth-illustration" aria-hidden="true">
        <svg viewBox="0 0 64 64" fill="none"><rect x="8" y="10" width="48" height="36" rx="10" fill="#FFF7ED"/><path d="M18 42L28 28L34 35L46 21" stroke="#F97316" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="20" cy="22" r="4" fill="#EA580C"/></svg>
      </div>
      <div class="trust-badges mt-4">
        <span class="market-chip">Verified Agents</span>
        <span class="market-chip">Secure Payments</span>
        <span class="market-chip">Fast Requests</span>
      </div>
    </aside>
    <div class="col-lg-6 p-4 p-lg-5 bg-white auth-form-card">
      <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <div>
          <h3 class="fw-bold mb-1">Create your marketplace account</h3>
          <p class="text-muted-custom mb-0" data-i18n="register_intro">Start as an agent or service provider and manage listings professionally.</p>
        </div>
        <span class="badge rounded-pill bg-soft">New</span>
      </div>
      <form class="row g-3" method="POST" action="?route=register-submit" id="registerForm" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>">
        <div class="col-md-6"><label class="form-label small text-muted-custom">Full name</label><input class="form-control" type="text" name="full_name" placeholder="Your full name" required /></div>
        <div class="col-md-6"><label class="form-label small text-muted-custom">Username</label><input class="form-control" type="text" name="username" placeholder="Choose a username" required /></div>
        <div class="col-md-6"><label class="form-label small text-muted-custom">Phone number</label><input class="form-control" type="text" name="phone" placeholder="077..." required /></div>
        <div class="col-md-6"><label class="form-label small text-muted-custom">WhatsApp</label><input class="form-control" type="text" name="whatsapp" placeholder="Optional" /></div>
        <div class="col-md-6"><label class="form-label small text-muted-custom">Email</label><input class="form-control" type="email" name="email" placeholder="you@example.com" required /></div>
        <div class="col-md-6">
          <label class="form-label small text-muted-custom">Password</label>
          <div class="input-group">
            <input class="form-control" type="password" name="password" id="registerPassword" placeholder="Create password" required />
            <button class="btn btn-outline-secondary" type="button" data-password-toggle="true" data-password-target="registerPassword">Show</button>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label small text-muted-custom">Confirm password</label>
          <div class="input-group">
            <input class="form-control" type="password" name="confirm_password" id="confirmPassword" placeholder="Re-enter password" required />
            <button class="btn btn-outline-secondary" type="button" data-password-toggle="true" data-password-target="confirmPassword">Show</button>
          </div>
        </div>
        <!-- Role Selection -->
        <div class="col-md-6">
          <label class="form-label small text-muted-custom">I want to join as</label>
          <select class="form-select" id="accountType" name="account_type">
            <option value="agent">Agent</option>
            <option value="service_provider">Service Provider</option>
          </select>
        </div>

        <!-- Service Provider Additional Fields (Hidden by default) -->
        <div id="serviceProviderFields" class="service-provider-hidden" style="display:none;">
          <div class="col-12">
            <div class="provider-divider">
              <h6 class="fw-bold text-muted-custom mb-0">Service Details</h6>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label small text-muted-custom">Service Category</label>
            <select class="form-select" name="service_category">
              <option value="">Select category...</option>
              <option value="electrician">Electrician</option>
              <option value="plumber">Plumber</option>
              <option value="mechanic">Mechanic</option>
              <option value="painter">Painter</option>
              <option value="technician">Technician</option>
              <option value="welder">Welder</option>
              <option value="cleaner">Cleaner</option>
              <option value="moving">Moving Service</option>
              <option value="security">Security Service</option>
              <option value="it_support">IT Support</option>
              <option value="freelancer">Freelancer</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label small text-muted-custom">Primary Service</label>
            <input class="form-control" type="text" name="primary_service" placeholder="e.g., Electrical Repairs" />
          </div>
          <div class="col-md-6">
            <label class="form-label small text-muted-custom">Secondary Service</label>
            <input class="form-control" type="text" name="secondary_service" placeholder="Optional" />
          </div>
          <div class="col-md-6">
            <label class="form-label small text-muted-custom">Experience Level</label>
            <select class="form-select" name="experience_level">
              <option value="">Select level...</option>
              <option value="beginner">Beginner (0-2 years)</option>
              <option value="intermediate">Intermediate (2-5 years)</option>
              <option value="expert">Expert (5+ years)</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label small text-muted-custom">Coverage Areas</label>
            <input class="form-control" type="text" name="coverage_areas" placeholder="e.g., Gasabo, Nyarugenge, Kicukiro" />
          </div>
        </div>

        <!-- Location Section -->
        <div class="col-12">
          <div class="location-header">
            <h6 class="fw-bold text-muted-custom mb-3">📍 Your Location</h6>
            <button class="btn btn-sm btn-outline-primary" type="button" id="autoDetectBtn" onclick="detectLocation()">
              <span id="autoDetectText">Auto-detect location</span>
              <span id="autoDetectLoader" style="display:none;">Detecting...</span>
            </button>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label small text-muted-custom">Province</label>
          <input class="form-control" type="text" id="province" name="province" placeholder="Kigali" required />
        </div>
        <div class="col-md-6">
          <label class="form-label small text-muted-custom">District</label>
          <input class="form-control" type="text" id="district" name="district" placeholder="Gasabo" required />
        </div>
        <div class="col-md-6">
          <label class="form-label small text-muted-custom">Sector</label>
          <input class="form-control" type="text" id="sector" name="sector" placeholder="Remera" required />
        </div>
        <div class="col-md-6">
          <label class="form-label small text-muted-custom">Cell</label>
          <input class="form-control" type="text" id="cell" name="cell" placeholder="Rukiri II" required />
        </div>
        <div class="col-md-6">
          <label class="form-label small text-muted-custom">Village</label>
          <input class="form-control" type="text" id="village" name="village" placeholder="Optional" />
        </div>
        <div class="col-12">
          <label class="form-label small text-muted-custom">Working areas</label>
          <input class="form-control" type="text" name="service_areas" placeholder="Kacyiru, Kimihurura, Remera" />
        </div>

        <!-- Premium Profile Image Upload -->
        <div class="col-12">
          <label class="form-label small text-muted-custom d-block mb-3">Profile Photo</label>
          <div class="premium-upload-component" id="premiumUpload">
            <input type="file" id="profileImage" name="profile_image" accept="image/*" style="display:none;" />
            
            <!-- Upload Area -->
            <div class="upload-area" id="uploadArea">
              <div class="upload-icon">📷</div>
              <div class="upload-text">
                <div class="upload-main">Drop your photo here</div>
                <div class="upload-sub">or <span class="upload-link">click to upload</span></div>
              </div>
              <div class="upload-hint">JPG, PNG, or GIF • Max 5MB</div>
            </div>

            <!-- Preview Area -->
            <div class="preview-container" id="previewContainer" style="display:none;">
              <div class="avatar-preview-wrapper">
                <div class="avatar-circle" id="avatarPreview"></div>
                <button type="button" class="btn-remove-image" id="removeImageBtn" title="Remove photo">✕</button>
              </div>
              <div class="upload-actions">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="changeImageBtn">Change Photo</button>
                <div class="upload-success">✓ Ready to upload</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12"><button class="btn btn-primary w-100" type="submit">Join marketplace</button></div>
      </form>
    </div>
  </article>
</section>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<section class="wizard-container">
  <div class="wizard-card">
    <!-- Progress Bar -->
    <div class="wizard-progress">
      <div class="progress-bar-wrapper">
        <div class="progress-bar-fill" id="progressBarFill" style="width: 25%;"></div>
      </div>
      <div class="progress-text">
        <span id="stepIndicator">Step 1 of 4</span>
      </div>
    </div>

    <!-- Form Container -->
    <form id="wizardForm" method="POST" action="?route=register-submit" enctype="multipart/form-data" class="wizard-form">
      <input type="hidden" name="csrf_token" value="<?php echo e(generateCsrfToken()); ?>">
      <input type="hidden" id="currentStep" name="current_step" value="1">
      <input type="hidden" id="selectedRole" name="account_type" value="">

      <!-- STEP 1: Who Are You? -->
      <div class="wizard-step active" id="step1">
        <div class="step-content">
          <h2 class="step-title">Who are you?</h2>
          <p class="step-subtitle">Let's get started with your basic information</p>

          <div class="step-field">
            <label class="form-label">Full Name</label>
            <input class="form-control form-control-lg" type="text" id="fullName" name="full_name" placeholder="Your full name" required />
          </div>

          <div class="step-field">
            <label class="form-label">Phone Number</label>
            <input class="form-control form-control-lg" type="tel" id="phone" name="phone" placeholder="077 123 4567" required />
          </div>

          <button type="button" class="btn btn-primary btn-lg btn-block mt-4" onclick="nextStep(1)">Continue</button>
        </div>
      </div>

      <!-- STEP 2: Create Account -->
      <div class="wizard-step" id="step2">
        <div class="step-content">
          <h2 class="step-title">Create your account</h2>
          <p class="step-subtitle">Set up your login credentials</p>

          <div class="step-field">
            <label class="form-label">Username</label>
            <input class="form-control form-control-lg" type="text" id="username" name="username" placeholder="Choose a username" required />
          </div>

          <div class="step-field">
            <label class="form-label">Email</label>
            <input class="form-control form-control-lg" type="email" id="email" name="email" placeholder="you@example.com" required />
          </div>

          <div class="step-field">
            <label class="form-label">Password</label>
            <div class="input-group input-group-lg">
              <input class="form-control" type="password" id="password" name="password" placeholder="Create password (8+ characters)" required />
              <button class="btn btn-outline-secondary" type="button" data-password-toggle="true" data-password-target="password">Show</button>
            </div>
          </div>

          <div class="step-field">
            <label class="form-label">Confirm Password</label>
            <div class="input-group input-group-lg">
              <input class="form-control" type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm password" required />
              <button class="btn btn-outline-secondary" type="button" data-password-toggle="true" data-password-target="confirmPassword">Show</button>
            </div>
          </div>

          <div class="wizard-buttons">
            <button type="button" class="btn btn-outline-secondary btn-lg btn-block" onclick="prevStep(2)">Back</button>
            <button type="button" class="btn btn-primary btn-lg btn-block" onclick="nextStep(2)">Continue</button>
          </div>
        </div>
      </div>

      <!-- STEP 3: Role Selection -->
      <div class="wizard-step" id="step3">
        <div class="step-content">
          <h2 class="step-title">What do you want to do?</h2>
          <p class="step-subtitle">Choose how you want to use Rwanda Marketplace</p>

          <div class="role-cards">
            <!-- Agent Card -->
            <div class="role-card" onclick="selectRole('agent', this)">
              <div class="role-icon">🏠</div>
              <h3>Agent</h3>
              <p>Sell or rent properties</p>
            </div>

            <!-- Service Provider Card -->
            <div class="role-card" onclick="selectRole('service_provider', this)">
              <div class="role-icon">🔧</div>
              <h3>Service Provider</h3>
              <p>Offer services and receive clients</p>
            </div>
          </div>

          <div class="wizard-buttons">
            <button type="button" class="btn btn-outline-secondary btn-lg btn-block" onclick="prevStep(3)">Back</button>
            <button type="button" class="btn btn-primary btn-lg btn-block" id="continueStep3" onclick="nextStep(3)" disabled>Continue</button>
          </div>
        </div>
      </div>

      <!-- STEP 4A: Agent - Location -->
      <div class="wizard-step" id="step4agent">
        <div class="step-content">
          <h2 class="step-title">Your location</h2>
          <p class="step-subtitle">Where are you located?</p>

          <button type="button" class="btn btn-outline-primary btn-sm mb-3" onclick="detectLocation()">
            <span id="autoDetectText">📍 Auto-detect my location</span>
            <span id="autoDetectLoader" style="display:none;">Detecting...</span>
          </button>

          <div class="step-field">
            <label class="form-label">Province</label>
            <input class="form-control form-control-lg" type="text" id="province" name="province" placeholder="e.g., Kigali" required />
          </div>

          <div class="step-field">
            <label class="form-label">District</label>
            <input class="form-control form-control-lg" type="text" id="district" name="district" placeholder="e.g., Gasabo" required />
          </div>

          <div class="step-field">
            <label class="form-label">Sector</label>
            <input class="form-control form-control-lg" type="text" id="sector" name="sector" placeholder="e.g., Remera" />
          </div>

          <div class="optional-section">
            <h5>Optional information</h5>
            <div class="step-field">
              <label class="form-label">Working areas</label>
              <input class="form-control" type="text" id="serviceAreas" name="service_areas" placeholder="e.g., Kacyiru, Kimihurura, Remera" />
            </div>

            <div class="step-field">
              <label class="form-label">Profile Photo</label>
              <div class="profile-upload" onclick="document.getElementById('profileImageAgent').click()">
                <div class="profile-avatar" id="profileAvatarAgent">📷</div>
                <div class="profile-text">Add Profile Photo</div>
              </div>
              <input type="file" id="profileImageAgent" name="profile_image" accept="image/*" style="display:none;" />
            </div>
          </div>

          <div class="wizard-buttons">
            <button type="button" class="btn btn-outline-secondary btn-lg btn-block" onclick="prevStep(4)">Back</button>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Create Account</button>
          </div>
        </div>
      </div>

      <!-- STEP 4B: Service Provider -->
      <div class="wizard-step" id="step4provider">
        <div class="step-content">
          <h2 class="step-title">Your services</h2>
          <p class="step-subtitle">Tell us about your services</p>

          <div class="step-field">
            <label class="form-label">Service Category</label>
            <select class="form-select form-control-lg" id="serviceCategory" name="service_category" required>
              <option value="">Select what you do...</option>
              <option value="electrician">⚡ Electrician</option>
              <option value="plumber">🚰 Plumber</option>
              <option value="mechanic">🔧 Mechanic</option>
              <option value="painter">🎨 Painter</option>
              <option value="technician">💻 Technician</option>
              <option value="welder">🔥 Welder</option>
              <option value="cleaner">🧹 Cleaner</option>
              <option value="moving">📦 Moving Service</option>
              <option value="security">🛡️ Security Service</option>
              <option value="it_support">🖥️ IT Support</option>
              <option value="freelancer">💼 Freelancer</option>
            </select>
          </div>

          <div class="step-field">
            <label class="form-label">Working Areas</label>
            <input class="form-control form-control-lg" type="text" id="providerServiceAreas" name="service_areas" placeholder="e.g., Gasabo, Nyarugenge, Kicukiro" required />
          </div>

          <div class="step-field">
            <label class="form-label">Province</label>
            <input class="form-control form-control-lg" type="text" id="providerProvince" name="province" placeholder="e.g., Kigali" required />
          </div>

          <div class="step-field">
            <label class="form-label">District</label>
            <input class="form-control form-control-lg" type="text" id="providerDistrict" name="district" placeholder="e.g., Gasabo" required />
          </div>

          <div class="optional-section">
            <h5>Optional information</h5>
            <div class="step-field">
              <label class="form-label">Profile Photo</label>
              <div class="profile-upload" onclick="document.getElementById('profileImageProvider').click()">
                <div class="profile-avatar" id="profileAvatarProvider">📷</div>
                <div class="profile-text">Add Profile Photo</div>
              </div>
              <input type="file" id="profileImageProvider" name="profile_image" accept="image/*" style="display:none;" />
            </div>
          </div>

          <div class="wizard-buttons">
            <button type="button" class="btn btn-outline-secondary btn-lg btn-block" onclick="prevStep(4)">Back</button>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Create Account</button>
          </div>
        </div>
      </div>
    </form>

    <!-- Footer -->
    <div class="wizard-footer">
      <p>Already have an account? <a href="?route=login" class="fw-bold">Login here</a></p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

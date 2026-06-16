document.addEventListener('DOMContentLoaded', function () {
  const translations = {
    en: {
      register_intro: 'Start as an agent or service provider and manage listings professionally.',
      welcome_back: 'Welcome back',
      provider_workspace: 'Provider workspace',
      dashboard_title: 'Your marketplace dashboard',
      dashboard_sub: 'Track your plan, publish fresh listings, and respond to new leads faster.',
      view_marketplace: 'View marketplace',
      real_estate: 'Real Estate',
      services: 'Services',
      requests: 'Requests',
      post_listing: 'Post Listing',
      alerts: 'Alerts',
      logout: 'Logout',
      home: 'Home',
      listings: 'Listings',
      request: 'Request',
      profile: 'Profile',
      about: 'About',
      admin: 'Admin',
      submit_request: 'Submit request',
      provider_registration: 'Provider Registration',
      login_title: 'Welcome back to Rwanda Marketplace',
      login_intro: 'Find verified agents, premium listings, and fresh leads in one bright, trusted marketplace.',
      login_chip_verified: 'Verified agents',
      login_chip_fast: 'Fast matching',
      login_chip_premium: 'Premium visibility',
      login_heading: 'Login to your marketplace account',
      login_sub: 'Use your phone number or email and continue where you left off.',
      login_badge: 'Secure',
      login_identifier: 'Phone or email',
      login_password: 'Password',
      login_remember: 'Remember me',
      login_forgot: 'Forgot password?',
      login_cta: 'Login & explore marketplace',
      login_no_account: 'No account yet?',
      login_create: 'Create one here',
      find_fast: 'Find fast',
      search_heading: 'Search properties and trusted services',
      search_subtitle: 'Compact filters designed for quick marketplace discovery.',
      quick_filters: 'Quick filters',
      location_label: 'Location',
      location_placeholder: 'Kigali, Southern...',
      category_label: 'Category',
      category_placeholder: 'Real Estate, Services...',
      price_label: 'Price',
      price_placeholder: 'Under 50M, 100M+',
      keyword_label: 'Keyword',
      keyword_placeholder: 'House, repair, agent...',
      search_button: 'Search',
      search_placeholder: 'Search homes, services, providers',
      location_hint: 'Auto-detecting your location for nearby listings...',
      about_heading: 'About Rwanda Marketplace',
      about_title: 'A trusted marketplace for homes, services, and verified providers in Rwanda.',
      about_text_1: 'Rwanda Marketplace helps buyers, renters, agents, and service providers discover trusted properties and local services in one clean digital space. From home listings to repair and business support, everything is organized for quick, reliable discovery.',
      about_text_2: 'Our goal is simple: make local finding easier, faster, and more trustworthy for everyone in Rwanda.',
      verified_providers: 'Verified providers',
      verified_providers_text: 'Reliable agents and service teams ready to respond.',
      nearby_results: 'Nearby results',
      nearby_results_text: 'Location-aware suggestions help you reach the right people faster.',
      simple_flow: 'Simple flow',
      simple_flow_text: 'Browse, request, and connect without confusing steps.',
      nearby_section_title: 'Nearby results',
      nearby_section_sub: 'Closest agents and listings sorted by proximity.',
      live_location_suggestions: 'Live location suggestions',
      featured_listings_title: 'Featured listings',
      featured_listings_sub: 'Premium and boosted picks prioritized for visibility.',
      browse_all: 'Browse all',
      top_rated_providers_title: 'Top rated providers',
      top_rated_providers_sub: 'Verified profiles ranked by local rating and activity.',
      fresh_provider_suggestions: 'Fresh provider suggestions',
      recent_activity_title: 'Recent activity feed',
      recent_activity_sub: 'New listings and live requests from the marketplace.',
      infinite_feed_ready: 'Infinite feed ready',
      trending_services_title: 'Trending services',
      trending_services_sub: 'Auto-generated from request demand and market activity.',
      request_callout_title: 'Request a service or property',
      request_callout_sub: 'No login required. Submit your request and trusted providers in Rwanda will contact you directly.',
      submit_request_button: 'Submit request',
      request_form_title: 'Request form',
      request_form_sub: 'Fast, public, and no login required',
      full_name: 'Full name',
      phone_number: 'Phone number',
      whatsapp: 'WhatsApp',
      request_type: 'Request type',
      province: 'Province',
      district: 'District',
      budget: 'Budget',
      describe_need: 'Describe your need',
      send_request: 'Send request',
      call: 'Call',
      view_details: 'View details'
    },
    rw: {
      register_intro: 'Tangira nka umukozi wa marketplace cyangwa umuhanga kandi ukore ibiranga neza.',
      welcome_back: 'Murakaza neza',
      provider_workspace: 'Ahantu h’akazi ka provider',
      dashboard_title: 'Ikibaho cya marketplace cyawe',
      dashboard_sub: 'Komeza igenzura rya plan, ushyireho ibyatangaza, kandi usubize abashaka ku bwihuse.',
      view_marketplace: 'Reba marketplace',
      real_estate: 'Ubutaka n’imitungo',
      services: 'Serivisi',
      requests: 'Ibisabwa',
      post_listing: 'Oshyiraho icyatangaza',
      alerts: 'Amatangazo',
      logout: 'Gusohoka',
      home: 'Ahabanza',
      listings: 'Ibyatangaza',
      request: 'Icyifuzo',
      profile: 'Umwirondoro',
      about: 'Ibyerekeye',
      admin: 'Admin',
      submit_request: 'Ohereza icyifuzo',
      provider_registration: 'Kwiyandikisha nka Provider',
      login_title: 'Murakaza neza kuri Rwanda Marketplace',
      login_intro: 'Shaka abakozi bemewe, ibyatangaza byiza, n’amasoko mashya mu isoko imwe yizewe.',
      login_chip_verified: 'Abakozi bemewe',
      login_chip_fast: 'Guhita guhuza',
      login_chip_premium: 'Kugaragara kw’icyiciro cyiza',
      login_heading: 'Injira muri konte ya marketplace yawe',
      login_sub: 'Koresha nimero ya telefone cyangwa email yawe ukomeze aho wasize.',
      login_badge: 'Byizewe',
      login_identifier: 'Telefone cyangwa email',
      login_password: 'Ijambobanga',
      login_remember: 'Unyibuke',
      login_forgot: 'Wibagiwe ijambobanga?',
      login_cta: 'Injira ukore ishoramari rya marketplace',
      login_no_account: 'Nta konti ufite?',
      login_create: 'Iyandikishe hano',
      find_fast: 'Shaka vuba',
      search_heading: 'Shakisha amazu n’ibikorwa byizewe',
      search_subtitle: 'Amasaha yihuse yateguwe kugirango ubone ibyifuzo byoroshye.',
      quick_filters: 'Amasiba yihuse',
      location_label: 'Ahantu',
      location_placeholder: 'Kigali, Amajyepfo...',
      category_label: 'Uburyo',
      category_placeholder: 'Ubutaka, Serivisi...',
      price_label: 'Igiciro',
      price_placeholder: 'Munsi ya 50M, 100M+',
      keyword_label: 'Ijambo ry’ingenzi',
      keyword_placeholder: 'Inzu, gukosora, umukozi...',
      search_button: 'Shakisha',
      search_placeholder: 'Shakisha amazu, serivisi, n’abatanga serivisi',
      location_hint: 'Turashakisha aho uri kugira ngo tubone ibyatangaza biri hafi yawe...',
      about_heading: 'Ibyerekeye Rwanda Marketplace',
      about_title: 'Isoko yizewe y’amazu, serivisi, n’abahanga bemewe mu Rwanda.',
      about_text_1: 'Rwanda Marketplace ifasha abaguzi, abashaka gutura, abakozi, n’abatanga serivisi kubona amazu n’ibikorwa byizewe mu mwanya umwe. Uhereye ku byatangazo by’amazu kugeza ku buhuzabwonerabubata, byose byateguwe kugirango ubone byoroshye kandi ukize.',
      about_text_2: 'Intego yacu yoroshye: gufasha abantu kubona ibintu hafi yawe, vuba, kandi bakizera.',
      verified_providers: 'Abatanga serivisi bemewe',
      verified_providers_text: 'Abakozi bemewe bitezimbere bazahita bagusubiza.',
      nearby_results: 'Ibyegeranyo biri hafi',
      nearby_results_text: 'Ibyifuzo biri hafi yawe bikuraho abantu bakwiriye vuba.',
      simple_flow: 'Uburyo bworoshye',
      simple_flow_text: 'Reba, usabe, uhuzwe nta bintu byoroshye byatangiye.',
      nearby_section_title: 'Ibyegeranyo biri hafi',
      nearby_section_sub: 'Abakozi n’ibyatangazwa biri hafi yawe byateguwe ukurikije ubusanzwe.',
      live_location_suggestions: 'Ibyifuzo by’ahantu biriho',
      featured_listings_title: 'Ibyatangaza byihariye',
      featured_listings_sub: 'Ibyatangaza byatejwe imbere kugira byagaragare neza.',
      browse_all: 'Reba byose',
      top_rated_providers_title: 'Abatanga serivisi b’icyiciro cyiza',
      top_rated_providers_sub: 'Imyirondoro yemewe igereranwa ukurikije amanota n’ibikorwa.',
      fresh_provider_suggestions: 'Ibyifuzo bishya by’abatanga serivisi',
      recent_activity_title: 'Ibyabaye vuba',
      recent_activity_sub: 'Ibyatangaza bishya n’ibisabwa biri kubaho mu isoko.',
      infinite_feed_ready: 'Itangira ryuzuye',
      trending_services_title: 'Serivisi zikwiriye',
      trending_services_sub: 'Byakozwe ku isoko no ku bintu abantu basaba.',
      request_callout_title: 'Saba serivisi cyangwa umutungo',
      request_callout_sub: 'Nta konti irakenewe. Ohereza icyifuzo, abatanga serivisi bemewe bazaguhamagara.',
      submit_request_button: 'Ohereza icyifuzo',
      request_form_title: 'Ifishi y’icyifuzo',
      request_form_sub: 'Yihuse, irahari, nta konti irakenewe',
      full_name: 'Amazina yuzuye',
      phone_number: 'Numero ya telefone',
      whatsapp: 'WhatsApp',
      request_type: 'Ubwoko bw’icyifuzo',
      province: 'Intara',
      district: 'Akarere',
      budget: 'Ingengo y’imari',
      describe_need: 'Sobanura ibyo ukenera',
      send_request: 'Ohereza icyifuzo',
      call: 'Hamagara',
      view_details: 'Reba ibisobanuro'
    }
  };

  const langSwitcher = document.getElementById('langSwitcher');
  const applyTranslations = function (lang) {
    const dictionary = translations[lang] || translations.en;

    document.querySelectorAll('[data-i18n]').forEach(function (node) {
      if (dictionary[node.dataset.i18n]) {
        node.textContent = dictionary[node.dataset.i18n];
      }
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(function (node) {
      const key = node.dataset.i18nPlaceholder;
      if (dictionary[key]) {
        node.setAttribute('placeholder', dictionary[key]);
      }
    });

    document.querySelectorAll('[data-i18n-label]').forEach(function (node) {
      const key = node.dataset.i18nLabel;
      if (dictionary[key]) {
        node.textContent = dictionary[key];
      }
    });

    document.querySelectorAll('[data-i18n-aria-label]').forEach(function (node) {
      const key = node.dataset.i18nAriaLabel;
      if (dictionary[key]) {
        node.setAttribute('aria-label', dictionary[key]);
      }
    });

    if (langSwitcher) langSwitcher.value = lang;
    document.documentElement.lang = lang;
    localStorage.setItem('market_lang', lang);
  };

  const savedLang = localStorage.getItem('market_lang') || 'en';
  if (langSwitcher) {
    langSwitcher.addEventListener('change', function (event) {
      applyTranslations(event.target.value);
    });
  }
  applyTranslations(savedLang);

  const forms = document.querySelectorAll('form');
  forms.forEach(function (form) {
    form.addEventListener('submit', function () {
      form.classList.add('is-submitting');
    });
  });

  // Admin Sidebar switching & Drawer toggle
  const adminToggle = document.getElementById('adminMenuToggle');
  if (adminToggle) {
    adminToggle.addEventListener('click', function () {
      if (window.innerWidth < 992) {
        document.body.classList.toggle('mobile-open');
      } else {
        document.body.classList.toggle('sidebar-collapsed');
      }
    });
  }

  document.querySelectorAll('[data-admin-link]').forEach(function (button) {
    button.addEventListener('click', function () {
      const target = button.getAttribute('data-admin-link');
      document.querySelectorAll('.admin-view-panel').forEach(function (panel) {
        panel.classList.toggle('active', panel.getAttribute('data-admin-panel') === target);
      });
      document.querySelectorAll('[data-admin-link]').forEach(function (item) {
        item.classList.toggle('active', item === button);
      });
      document.body.classList.remove('mobile-open');
    });
  });

  // ChartJS Initialization for Analytics
  const userGrowthEl = document.getElementById('userGrowthChart');
  const revenueEl = document.getElementById('revenueChart');
  if (userGrowthEl && typeof Chart !== 'undefined') {
    new Chart(userGrowthEl, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Registered Users',
          data: [120, 150, 180, 220, 260, 310],
          borderColor: '#1E3A8A',
          backgroundColor: 'rgba(30, 58, 138, 0.05)',
          borderWidth: 3,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { grid: { color: 'rgba(15, 23, 42, 0.05)' } },
          x: { grid: { display: false } }
        }
      }
    });
  }

  if (revenueEl && typeof Chart !== 'undefined') {
    new Chart(revenueEl, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'Revenue (RWF)',
          data: [500000, 750000, 1200000, 900000, 1500000, 2100000],
          backgroundColor: '#CFAE00',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { grid: { color: 'rgba(15, 23, 42, 0.05)' } },
          x: { grid: { display: false } }
        }
      }
    });
  }


  document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
    button.addEventListener('click', function () {
      const targetId = button.getAttribute('data-password-target');
      const input = targetId ? document.getElementById(targetId) : null;
      if (!input) return;

      const isPassword = input.getAttribute('type') === 'password';
      input.setAttribute('type', isPassword ? 'text' : 'password');
      button.textContent = isPassword ? 'Hide' : 'Show';
      button.setAttribute('aria-pressed', String(isPassword));
    });
  });

  const chips = document.querySelectorAll('.chip');
  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      chips.forEach(function (item) { item.classList.remove('active'); });
      chip.classList.add('active');
    });
  });

  const navPills = document.querySelectorAll('.bottom-nav .nav-pill');
  navPills.forEach(function (pill) {
    pill.addEventListener('click', function () {
      navPills.forEach(function (item) { item.classList.remove('active'); });
      pill.classList.add('active');
    });
  });

  const uploadInput = document.getElementById('profileImage');
  const uploadDropzone = document.getElementById('uploadDropzone');
  const uploadPreview = document.getElementById('uploadPreview');
  if (uploadInput && uploadDropzone && uploadPreview) {
    const showPreview = function (file) {
      if (!file || !file.type.startsWith('image/')) return;
      uploadPreview.innerHTML = '<span class="spinner-border spinner-border-sm text-warning" role="status" aria-label="Loading"></span>';
      const reader = new FileReader();
      reader.onload = function (event) {
        const img = document.createElement('img');
        img.src = event.target.result;
        img.alt = 'Profile preview';
        uploadPreview.innerHTML = '';
        uploadPreview.appendChild(img);
      };
      reader.readAsDataURL(file);
    };
    uploadInput.addEventListener('change', function (event) { showPreview(event.target.files[0]); });
    ['dragenter', 'dragover'].forEach(function (eventName) {
      uploadDropzone.addEventListener(eventName, function (event) { event.preventDefault(); uploadDropzone.classList.add('is-dragging'); });
    });
    ['dragleave', 'drop'].forEach(function (eventName) {
      uploadDropzone.addEventListener(eventName, function (event) { event.preventDefault(); uploadDropzone.classList.remove('is-dragging'); });
    });
    uploadDropzone.addEventListener('drop', function (event) { showPreview(event.dataTransfer.files[0]); });
  }

  const bell = document.getElementById('notificationBell');
  if (bell && Number(bell.dataset.notificationCount || 0) > 0) {
    try {
      const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      const oscillator = audioCtx.createOscillator();
      const gain = audioCtx.createGain();
      oscillator.type = 'sine';
      oscillator.frequency.value = 880;
      gain.gain.value = 0.02;
      oscillator.connect(gain);
      gain.connect(audioCtx.destination);
      oscillator.start();
      setTimeout(function () { oscillator.stop(); audioCtx.close(); }, 180);
    } catch (error) { }
  }

  const slider = document.getElementById('heroSlider');
  if (slider) {
    const slides = slider.querySelectorAll('.hero-slide');
    const dots = slider.querySelector('.slider-dots');
    if (slides.length > 1) {
      let current = 0;
      slides.forEach(function (_, index) {
        const dot = document.createElement('span');
        if (index === 0) dot.classList.add('active');
        dots.appendChild(dot);
      });
      const dotItems = dots.querySelectorAll('span');
      setInterval(function () {
        slides[current].classList.remove('active');
        dotItems[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
        dotItems[current].classList.add('active');
      }, 5000);
    }
  }

  const locationHint = document.getElementById('locationHint');
  const locationLabel = document.getElementById('detectedLocationLabel');
  const locationInputs = Array.from(document.querySelectorAll('input[name="location"]'));
  const autoLocationInputs = Array.from(document.querySelectorAll('input[name="auto_location"]'));
  const marketFilterChips = Array.from(document.querySelectorAll('.header-filter-chip'));
  const zoneAwarenessBanner = document.getElementById('zoneAwarenessBanner');

  const applyMarketplaceFilter = function (mode) {
    const normalized = mode || 'all';
    document.querySelectorAll('[data-listing-kind]').forEach(function (item) {
      const shouldShow = normalized === 'all' || item.dataset.listingKind === normalized;
      item.style.display = shouldShow ? '' : 'none';
    });
    marketFilterChips.forEach(function (chip) {
      chip.classList.toggle('active', chip.dataset.marketFilter === normalized);
    });
    localStorage.setItem('market_filter_mode', normalized);
  };

  const updateZoneBanner = function () {
    if (!zoneAwarenessBanner) return;
    const agentCount = Number(zoneAwarenessBanner.dataset.agentCount || 0);
    const serviceCount = Number(zoneAwarenessBanner.dataset.serviceCount || 0);
    if (!agentCount && !serviceCount) {
      zoneAwarenessBanner.classList.add('d-none');
      return;
    }
    const preferredKind = agentCount >= serviceCount ? 'agent' : 'service';
    const count = Math.max(agentCount, serviceCount, 1);
    const label = preferredKind === 'agent' ? 'real estate agents' : 'service providers';
    zoneAwarenessBanner.textContent = 'There are ' + count + ' verified ' + label + ' available in your area.';
    zoneAwarenessBanner.classList.remove('d-none');
  };

  const saveDetectedLocation = function (value) {
    if (!value) return;
    localStorage.setItem('market_location', value);
    locationInputs.forEach(function (input) {
      if (!input.value.trim()) {
        input.value = value;
      }
    });
    autoLocationInputs.forEach(function (input) { input.value = value; });
    if (locationLabel) {
      locationLabel.textContent = value;
    }
    if (locationHint) {
      locationHint.textContent = 'Showing results near ' + value;
    }
    updateZoneBanner();
  };

  const buildLocationString = function (data) {
    if (!data) return '';
    const province = data.region || (data.address && (data.address.state || data.address.region)) || '';
    const district = data.city || data.address?.city || data.address?.town || data.address?.county || data.address?.state_district || '';
    const sector = data.address?.suburb || data.address?.village || data.address?.neighbourhood || '';
    return [province, district, sector].filter(Boolean).slice(0, 3).join(' / ');
  };

  const fetchIpLocation = function () {
    return fetch('https://ipapi.co/json/')
      .then(function (response) {
        if (!response.ok) throw new Error('IP lookup failed');
        return response.json();
      })
      .then(function (data) {
        const value = buildLocationString(data);
        if (value) saveDetectedLocation(value);
      })
      .catch(function () {
        if (locationHint) {
          locationHint.textContent = 'Unable to detect location automatically. Please enter your area.';
        }
      });
  };

  const reverseGeocode = function (lat, lon) {
    return fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lon)
      .then(function (response) {
        if (!response.ok) throw new Error('Reverse geocode failed');
        return response.json();
      })
      .then(function (data) {
        const value = buildLocationString(data);
        if (value) {
          saveDetectedLocation(value);
        } else {
          fetchIpLocation();
        }
      })
      .catch(fetchIpLocation);
  };

  marketFilterChips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      applyMarketplaceFilter(chip.dataset.marketFilter || 'all');
    });
  });

  const storedFilter = localStorage.getItem('market_filter_mode') || 'all';
  applyMarketplaceFilter(storedFilter);

  const detectLocation = function () {
    const cached = localStorage.getItem('market_location');
    if (cached) {
      saveDetectedLocation(cached);
      updateZoneBanner();
      return;
    }
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        reverseGeocode(position.coords.latitude, position.coords.longitude);
      }, function () {
        fetchIpLocation();
      }, { timeout: 10000, maximumAge: 600000 });
    } else {
      fetchIpLocation();
    }
  };

  detectLocation();
});

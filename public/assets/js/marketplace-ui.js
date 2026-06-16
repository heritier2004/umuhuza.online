// Marketplace UI Enhancements
document.addEventListener('DOMContentLoaded', function() {
  initMarketplaceTabs();
  initLocationAwareness();
  setupListingFiltering();
});

/**
 * Initialize marketplace tabs (All / Agents / Service Providers)
 */
function initMarketplaceTabs() {
  const container = document.querySelector('.marketplace-tabs');
  if (!container) return;

  // Check if tabs already initialized
  if (container.querySelector('.marketplace-tab')) return;

  const tabs = [
    { id: 'all', label: 'All', icon: '📋' },
    { id: 'agents', label: 'Agents', icon: '🏠' },
    { id: 'services', label: 'Service Providers', icon: '🔧' }
  ];

  tabs.forEach((tab, index) => {
    const tabEl = document.createElement('button');
    tabEl.className = `marketplace-tab ${index === 0 ? 'active' : ''}`;
    tabEl.dataset.filter = tab.id;
    tabEl.innerHTML = `${tab.icon} ${tab.label}`;
    tabEl.addEventListener('click', (e) => filterListings(e, tab.id));
    container.appendChild(tabEl);
  });
}

/**
 * Handle marketplace tab filtering
 */
function filterListings(event, filterType) {
  const tabs = document.querySelectorAll('.marketplace-tab');
  tabs.forEach(tab => tab.classList.remove('active'));
  event.target.closest('.marketplace-tab').classList.add('active');

  // Filter nearby listings
  const nearbyCards = document.querySelectorAll('.nearby-grid .listing-card');
  nearbyCards.forEach(card => {
    const isAgent = card.dataset.listingKind === 'agent';
    const matches = 
      filterType === 'all' ||
      (filterType === 'agents' && isAgent) ||
      (filterType === 'services' && !isAgent);

    if (matches) {
      card.classList.remove('hide-listing');
      card.style.display = '';
      // Trigger reflow for animation
      card.offsetHeight;
    } else {
      card.classList.add('hide-listing');
      card.style.display = 'none';
    }
  });

  // Filter featured listings
  const featuredCards = document.querySelectorAll('.featured-scroll .featured-card');
  featuredCards.forEach(card => {
    const isAgent = card.dataset.listingKind === 'agent';
    const matches = 
      filterType === 'all' ||
      (filterType === 'agents' && isAgent) ||
      (filterType === 'services' && !isAgent);

    if (matches) {
      card.classList.remove('hide-listing');
      card.style.display = '';
    } else {
      card.classList.add('hide-listing');
      card.style.display = 'none';
    }
  });

  // Filter providers
  const providerCards = document.querySelectorAll('.provider-grid .provider-card');
  providerCards.forEach(card => {
    const isAgent = card.dataset.listingKind === 'agent';
    const matches = 
      filterType === 'all' ||
      (filterType === 'agents' && isAgent) ||
      (filterType === 'services' && !isAgent);

    if (matches) {
      card.classList.remove('hide-listing');
      card.style.display = '';
    } else {
      card.classList.add('hide-listing');
      card.style.display = 'none';
    }
  });

  // Scroll to filtered results
  setTimeout(() => {
    const nearbySection = document.querySelector('.nearby-grid');
    if (nearbySection) {
      nearbySection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }, 100);
}

/**
 * Initialize location awareness messaging
 */
function initLocationAwareness() {
  const locationHint = document.getElementById('locationHint');
  if (!locationHint) return;

  // Check if location card already exists
  if (document.querySelector('.location-awareness-card')) return;

  // Try to get user location using HTML5 Geolocation
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude } = position.coords;
        // Estimate location based on coordinates
        estimateRwandaLocation(latitude, longitude);
      },
      (error) => {
        showLocationDisabledMessage();
      },
      {
        enableHighAccuracy: false,
        timeout: 8000,
        maximumAge: 3600000 // 1 hour cache
      }
    );
  } else {
    showLocationDisabledMessage();
  }
}

/**
 * Estimate Rwanda location from coordinates
 */
function estimateRwandaLocation(lat, lng) {
  // Rwanda provinces with approximate centers
  const provinces = [
    { name: 'Kigali', district: 'Gasabo', lat: -1.9536, lng: 30.0605 },
    { name: 'Kigali', district: 'Nyarugenge', lat: -1.9551, lng: 30.0576 },
    { name: 'Kigali', district: 'Nyarugenge', lat: -1.9651, lng: 30.0476 },
    { name: 'Northern Province', district: 'Burera', lat: -1.6558, lng: 29.6328 },
    { name: 'Northern Province', district: 'Gicumbi', lat: -1.4911, lng: 29.7742 },
    { name: 'Southern Province', district: 'Huye', lat: -2.6000, lng: 29.7389 },
    { name: 'Southern Province', district: 'Burundi', lat: -2.3089, lng: 29.9403 },
    { name: 'Western Province', district: 'Rusizi', lat: -2.5089, lng: 29.0461 },
    { name: 'Western Province', district: 'Nyungwe', lat: -2.4520, lng: 29.3067 },
    { name: 'Eastern Province', district: 'Ngoma', lat: -2.0189, lng: 30.8258 },
    { name: 'Eastern Province', district: 'Kayonza', lat: -1.9739, lng: 30.8089 }
  ];

  // Find closest province/district
  let closest = provinces[0];
  let minDistance = calculateDistance(lat, lng, closest.lat, closest.lng);

  for (let i = 1; i < provinces.length; i++) {
    const distance = calculateDistance(lat, lng, provinces[i].lat, provinces[i].lng);
    if (distance < minDistance) {
      minDistance = distance;
      closest = provinces[i];
    }
  }

  showLocationCard(closest.name, closest.district);
  // Store in session
  sessionStorage.setItem('userLocation', JSON.stringify({
    province: closest.name,
    district: closest.district,
    lat: lat,
    lng: lng,
    timestamp: Date.now()
  }));
}

/**
 * Calculate distance between two coordinates (Haversine formula)
 */
function calculateDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // Earth radius in km
  const dLat = toRad(lat2 - lat1);
  const dLon = toRad(lon2 - lon1);
  const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}

function toRad(degrees) {
  return degrees * (Math.PI / 180);
}

/**
 * Show location card with detected location
 */
function showLocationCard(province, district) {
  const locationHint = document.getElementById('locationHint');
  if (!locationHint) return;

  const card = document.createElement('div');
  card.className = 'location-awareness-card';
  card.innerHTML = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
      <circle cx="12" cy="10" r="3"/>
    </svg>
    <span class="location-awareness-text">
      You are browsing listings near: <span class="location-name">${province} • ${district}</span>
      <br>
      <small style="opacity: 0.8;">Showing nearest results first</small>
    </span>
  `;

  locationHint.style.display = 'none';
  locationHint.parentNode.insertBefore(card, locationHint);
}

/**
 * Show location disabled message
 */
function showLocationDisabledMessage() {
  const locationHint = document.getElementById('locationHint');
  if (!locationHint) return;

  const card = document.createElement('div');
  card.className = 'location-awareness-card location-disabled';
  card.innerHTML = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m3.08 3.08l4.24 4.24M1 12h6m6 0h6M4.22 19.78l4.24-4.24m3.08-3.08l4.24-4.24M19 4.22l-4.24 4.24m-3.08 3.08l-4.24 4.24"/>
      <circle cx="12" cy="12" r="1"/>
    </svg>
    <span class="location-awareness-text">
      Enable location for better recommendations • <a href="#" onclick="requestLocationPermission(event)" style="color: #1E40AF; text-decoration: underline; font-weight: 700;">Enable now</a>
    </span>
  `;

  locationHint.style.display = 'none';
  locationHint.parentNode.insertBefore(card, locationHint);
}

/**
 * Request location permission
 */
window.requestLocationPermission = function(e) {
  e.preventDefault();
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude } = position.coords;
        estimateRwandaLocation(latitude, longitude);
        const disabledCard = document.querySelector('.location-awareness-card.location-disabled');
        if (disabledCard) disabledCard.remove();
      }
    );
  }
};

/**
 * Setup listing filtering data attributes
 */
function setupListingFiltering() {
  // Mark listing cards with plan tier for future filtering if needed
  const nearbyCards = document.querySelectorAll('.nearby-grid .listing-card');
  nearbyCards.forEach(card => {
    const badges = card.querySelectorAll('.badge');
    let planTier = 'standard';
    
    badges.forEach(badge => {
      const text = badge.textContent.toLowerCase();
      if (text.includes('super')) planTier = 'super-premium';
      else if (text.includes('premium')) planTier = 'premium';
    });

    card.dataset.planTier = planTier;
  });

  // Mark provider cards with type
  const providerCards = document.querySelectorAll('.provider-grid .provider-card');
  providerCards.forEach(card => {
    const serviceCategory = card.querySelector('p')?.textContent || '';
    const isAgent = serviceCategory.toLowerCase().includes('real estate') || 
                   serviceCategory.toLowerCase().includes('property');
    card.dataset.providerType = isAgent ? 'agent' : 'service';
  });
}

/**
 * Add smooth scroll behavior for section links
 */
document.addEventListener('click', function(e) {
  if (e.target.matches('.browse-all-link') || e.target.closest('.browse-all-link')) {
    const section = e.target.closest('[data-section]');
    if (section) {
      e.preventDefault();
      const sectionId = section.dataset.section;
      const targetSection = document.querySelector(`[data-section-target="${sectionId}"]`);
      if (targetSection) {
        targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }
  }
});

// Initialize when DOM is ready
console.log('✅ Marketplace UI initialized');

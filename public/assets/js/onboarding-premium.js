/**
 * Premium Onboarding Module
 * Handles: Dynamic role fields, Geolocation, Premium upload
 */

const PremiumOnboarding = (() => {
  // ===== ROLE-BASED FIELDS =====
  const initializeRoleFields = () => {
    const accountTypeSelect = document.getElementById('accountType');
    const serviceProviderFields = document.getElementById('serviceProviderFields');

    if (!accountTypeSelect || !serviceProviderFields) return;

    accountTypeSelect.addEventListener('change', (e) => {
      const isServiceProvider = e.target.value === 'service_provider';
      
      if (isServiceProvider) {
        serviceProviderFields.style.display = 'block';
        serviceProviderFields.classList.remove('service-provider-hidden');
      } else {
        serviceProviderFields.classList.add('service-provider-hidden');
        setTimeout(() => {
          serviceProviderFields.style.display = 'none';
        }, 250);
      }
    });
  };

  // ===== GEOLOCATION SYSTEM =====
  const rwandaLocationMap = {
    // Province approximation based on latitude/longitude
    // Rwanda bounds: ~-1.05 to -2.84 latitude, 28.85 to 30.90 longitude
    provinces: {
      'kigali': { lat: -1.943, lng: 29.873, regions: ['Gasabo', 'Kicukiro', 'Nyarugenge'] },
      'western': { lat: -2.5, lng: 29.25, regions: ['Karongi', 'Rutsiro', 'Rubavu', 'Nyungwe'] },
      'southern': { lat: -2.6, lng: 29.75, regions: ['Gisagara', 'Nyamagabe', 'Nyaruguru', 'Huye'] },
      'eastern': { lat: -2.2, lng: 30.5, regions: ['Bugesera', 'Gatsibo', 'Kayonza', 'Rwamagana'] },
      'northern': { lat: -1.5, lng: 29.73, regions: ['Burera', 'Gicumbi', 'Musanze', 'Ruhengeri'] }
    }
  };

  const estimateLocation = (lat, lng) => {
    // Estimate province and district based on coordinates
    let closestProvince = 'Kigali';
    let closestDistrict = 'Gasabo';
    let minDistance = Infinity;

    for (const [province, data] of Object.entries(rwandaLocationMap.provinces)) {
      const distance = Math.sqrt(
        Math.pow(lat - data.lat, 2) + Math.pow(lng - data.lng, 2)
      );
      
      if (distance < minDistance) {
        minDistance = distance;
        closestProvince = province.charAt(0).toUpperCase() + province.slice(1);
        closestDistrict = data.regions[0];
      }
    }

    // Estimate sector from coordinates
    const sectors = ['Remera', 'Kimihurura', 'Kacyiru', 'Nyarutarama', 'Muhima'];
    const sectorIndex = Math.floor((lng - 28.85) / (30.90 - 28.85) * sectors.length);
    const estimatedSector = sectors[Math.min(sectorIndex, sectors.length - 1)];

    return {
      province: closestProvince,
      district: closestDistrict,
      sector: estimatedSector
    };
  };

  const detectLocation = () => {
    const autoDetectBtn = document.getElementById('autoDetectBtn');
    const autoDetectText = document.getElementById('autoDetectText');
    const autoDetectLoader = document.getElementById('autoDetectLoader');

    if (!navigator.geolocation) {
      alert('Geolocation not supported. Please enter location manually.');
      return;
    }

    autoDetectBtn.disabled = true;
    autoDetectText.style.display = 'none';
    autoDetectLoader.style.display = 'inline-block';

    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude } = position.coords;
        const location = estimateLocation(latitude, longitude);

        // Fill location fields
        document.getElementById('province').value = location.province;
        document.getElementById('district').value = location.district;
        document.getElementById('sector').value = location.sector;

        // Reset button
        autoDetectBtn.disabled = false;
        autoDetectText.style.display = 'inline';
        autoDetectLoader.style.display = 'none';
        autoDetectText.textContent = '✓ Location detected';

        setTimeout(() => {
          autoDetectText.textContent = 'Auto-detect location';
        }, 3000);
      },
      (error) => {
        console.warn('Geolocation error:', error.message);
        autoDetectBtn.disabled = false;
        autoDetectText.style.display = 'inline';
        autoDetectLoader.style.display = 'none';
        alert('Unable to detect location. Please enter manually.');
      },
      { enableHighAccuracy: false, timeout: 10000, maximumAge: 0 }
    );
  };

  // Make detectLocation globally accessible
  window.detectLocation = detectLocation;

  // ===== PREMIUM PROFILE UPLOAD =====
  const initializePremiumUpload = () => {
    const premiumUpload = document.getElementById('premiumUpload');
    const uploadArea = document.getElementById('uploadArea');
    const profileImage = document.getElementById('profileImage');
    const previewContainer = document.getElementById('previewContainer');
    const avatarPreview = document.getElementById('avatarPreview');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const changeImageBtn = document.getElementById('changeImageBtn');

    if (!premiumUpload) return;

    let selectedFile = null;

    // Click to upload
    uploadArea.addEventListener('click', () => profileImage.click());

    // File input change
    profileImage.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        handleFileSelect(file);
      }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      e.stopPropagation();
      uploadArea.classList.add('drag-over');
    });

    uploadArea.addEventListener('dragleave', (e) => {
      e.preventDefault();
      e.stopPropagation();
      uploadArea.classList.remove('drag-over');
    });

    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      e.stopPropagation();
      uploadArea.classList.remove('drag-over');

      const files = e.dataTransfer.files;
      if (files.length > 0) {
        const file = files[0];
        if (file.type.startsWith('image/')) {
          profileImage.files = files;
          handleFileSelect(file);
        } else {
          alert('Please drop an image file.');
        }
      }
    });

    // Remove image
    removeImageBtn.addEventListener('click', (e) => {
      e.preventDefault();
      resetUpload();
    });

    // Change image
    changeImageBtn.addEventListener('click', (e) => {
      e.preventDefault();
      profileImage.click();
    });

    const handleFileSelect = (file) => {
      selectedFile = file;

      // Validate file size (5MB = 5242880 bytes)
      if (file.size > 5242880) {
        alert('File size must be less than 5MB.');
        resetUpload();
        return;
      }

      // Validate file type
      if (!file.type.startsWith('image/')) {
        alert('Please select an image file (JPG, PNG, GIF).');
        resetUpload();
        return;
      }

      // Show preview
      const reader = new FileReader();
      reader.onload = (e) => {
        avatarPreview.style.backgroundImage = `url('${e.target.result}')`;
        uploadArea.style.display = 'none';
        previewContainer.style.display = 'flex';
      };
      reader.readAsDataURL(file);
    };

    const resetUpload = () => {
      selectedFile = null;
      profileImage.value = '';
      avatarPreview.style.backgroundImage = '';
      uploadArea.style.display = 'flex';
      previewContainer.style.display = 'none';
    };
  };

  // ===== PUBLIC API =====
  return {
    init: () => {
      initializeRoleFields();
      initializePremiumUpload();
    }
  };
})();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  PremiumOnboarding.init();
});

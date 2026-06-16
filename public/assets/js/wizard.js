/**
 * Registration Wizard Module
 * Handles: Step navigation, role selection, geolocation, profile upload
 */

const RegistrationWizard = (() => {
  let currentStep = 1;
  let selectedRole = null;

  // ===== STEP NAVIGATION =====
  const showStep = (step) => {
    document.querySelectorAll('.wizard-step').forEach(el => {
      el.classList.remove('active');
    });
    
    // Show appropriate step
    if (step === 4) {
      const stepId = selectedRole === 'agent' ? 'step4agent' : 'step4provider';
      const stepEl = document.getElementById(stepId);
      if (stepEl) stepEl.classList.add('active');
    } else {
      const stepEl = document.getElementById(`step${step}`);
      if (stepEl) stepEl.classList.add('active');
    }

    // Update progress bar
    const progress = (step / 4) * 100;
    document.getElementById('progressBarFill').style.width = progress + '%';
    document.getElementById('stepIndicator').textContent = `Step ${step} of 4`;

    currentStep = step;
    document.getElementById('currentStep').value = step;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const validateStep = (step) => {
    const form = document.getElementById('wizardForm');
    
    switch(step) {
      case 1:
        const fullName = document.getElementById('fullName').value.trim();
        const phone = document.getElementById('phone').value.trim();
        
        if (!fullName) {
          alert('Please enter your full name');
          return false;
        }
        if (!phone || !isValidPhone(phone)) {
          alert('Please enter a valid phone number');
          return false;
        }
        return true;

      case 2:
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!username || username.length < 3) {
          alert('Username must be at least 3 characters');
          return false;
        }
        if (!email || !isValidEmail(email)) {
          alert('Please enter a valid email address');
          return false;
        }
        if (!password || password.length < 8) {
          alert('Password must be at least 8 characters');
          return false;
        }
        if (password !== confirmPassword) {
          alert('Passwords do not match');
          return false;
        }
        return true;

      case 3:
        if (!selectedRole) {
          alert('Please select a role');
          return false;
        }
        document.getElementById('selectedRole').value = selectedRole;
        return true;

      case 4:
        if (selectedRole === 'agent') {
          const province = document.getElementById('province').value.trim();
          const district = document.getElementById('district').value.trim();
          const sector = document.getElementById('sector').value.trim();
          
          if (!province || !district || !sector) {
            alert('Please fill in your location information');
            return false;
          }
        } else {
          const serviceCategory = document.getElementById('serviceCategory').value;
          const providerServiceAreas = document.getElementById('providerServiceAreas').value.trim();
          const providerProvince = document.getElementById('providerProvince').value.trim();
          const providerDistrict = document.getElementById('providerDistrict').value.trim();

          if (!serviceCategory) {
            alert('Please select your service category');
            return false;
          }
          if (!providerServiceAreas) {
            alert('Please enter your working areas');
            return false;
          }
          if (!providerProvince || !providerDistrict) {
            alert('Please fill in your location information');
            return false;
          }
        }
        return true;

      default:
        return true;
    }
  };

  // ===== GEOLOCATION =====
  const rwandaLocationMap = {
    provinces: {
      'kigali': { lat: -1.943, lng: 29.873, name: 'Kigali', districts: ['Gasabo', 'Kicukiro', 'Nyarugenge'] },
      'western': { lat: -2.5, lng: 29.25, name: 'Western', districts: ['Karongi', 'Rutsiro', 'Rubavu', 'Nyungwe'] },
      'southern': { lat: -2.6, lng: 29.75, name: 'Southern', districts: ['Gisagara', 'Nyamagabe', 'Nyaruguru', 'Huye'] },
      'eastern': { lat: -2.2, lng: 30.5, name: 'Eastern', districts: ['Bugesera', 'Gatsibo', 'Kayonza', 'Rwamagana'] },
      'northern': { lat: -1.5, lng: 29.73, name: 'Northern', districts: ['Burera', 'Gicumbi', 'Musanze', 'Ruhengeri'] }
    }
  };

  const estimateLocation = (lat, lng) => {
    let closestProvince = 'Kigali';
    let closestDistrict = 'Gasabo';
    let minDistance = Infinity;

    for (const [key, data] of Object.entries(rwandaLocationMap.provinces)) {
      const distance = Math.sqrt(
        Math.pow(lat - data.lat, 2) + Math.pow(lng - data.lng, 2)
      );
      
      if (distance < minDistance) {
        minDistance = distance;
        closestProvince = data.name;
        closestDistrict = data.districts[0];
      }
    }

    const sectors = ['Remera', 'Kimihurura', 'Kacyiru', 'Nyarutarama', 'Muhima'];
    const sectorIndex = Math.floor((lng - 28.85) / (30.90 - 28.85) * sectors.length);
    const estimatedSector = sectors[Math.min(sectorIndex, sectors.length - 1)];

    return {
      province: closestProvince,
      district: closestDistrict,
      sector: estimatedSector
    };
  };

  window.detectLocation = () => {
    const autoDetectBtn = document.querySelector('[onclick="detectLocation()"]');
    const autoDetectText = document.getElementById('autoDetectText');
    const autoDetectLoader = document.getElementById('autoDetectLoader');

    if (!navigator.geolocation) {
      alert('Geolocation not supported in your browser');
      return;
    }

    autoDetectBtn.disabled = true;
    autoDetectText.style.display = 'none';
    autoDetectLoader.style.display = 'inline';

    navigator.geolocation.getCurrentPosition(
      (position) => {
        const { latitude, longitude } = position.coords;
        const location = estimateLocation(latitude, longitude);

        document.getElementById('province').value = location.province;
        document.getElementById('district').value = location.district;
        document.getElementById('sector').value = location.sector;

        autoDetectBtn.disabled = false;
        autoDetectText.style.display = 'inline';
        autoDetectLoader.style.display = 'none';
        autoDetectText.textContent = '✓ Location detected';

        setTimeout(() => {
          autoDetectText.textContent = '📍 Auto-detect my location';
        }, 3000);
      },
      () => {
        autoDetectBtn.disabled = false;
        autoDetectText.style.display = 'inline';
        autoDetectLoader.style.display = 'none';
        alert('Unable to detect location. Please enter manually.');
      },
      { enableHighAccuracy: false, timeout: 10000 }
    );
  };

  // ===== ROLE SELECTION =====
  window.selectRole = (role, element) => {
    document.querySelectorAll('.role-card').forEach(card => {
      card.classList.remove('selected');
    });
    
    element.classList.add('selected');
    selectedRole = role;
    document.getElementById('continueStep3').disabled = false;
  };

  // ===== PROFILE UPLOAD =====
  const initializeProfileUpload = () => {
    // Agent profile upload
    const agentInput = document.getElementById('profileImageAgent');
    const agentAvatar = document.getElementById('profileAvatarAgent');
    
    if (agentInput && agentAvatar) {
      agentInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file && validateImageFile(file)) {
          const reader = new FileReader();
          reader.onload = (event) => {
            agentAvatar.style.backgroundImage = `url('${event.target.result}')`;
            agentAvatar.textContent = '';
          };
          reader.readAsDataURL(file);
        }
      });

      agentAvatar.parentElement.addEventListener('click', () => {
        agentInput.click();
      });
    }

    // Provider profile upload
    const providerInput = document.getElementById('profileImageProvider');
    const providerAvatar = document.getElementById('profileAvatarProvider');
    
    if (providerInput && providerAvatar) {
      providerInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file && validateImageFile(file)) {
          const reader = new FileReader();
          reader.onload = (event) => {
            providerAvatar.style.backgroundImage = `url('${event.target.result}')`;
            providerAvatar.textContent = '';
          };
          reader.readAsDataURL(file);
        }
      });

      providerAvatar.parentElement.addEventListener('click', () => {
        providerInput.click();
      });
    }
  };

  const validateImageFile = (file) => {
    if (!file.type.startsWith('image/')) {
      alert('Please select an image file');
      return false;
    }
    if (file.size > 5242880) { // 5MB
      alert('File size must be less than 5MB');
      return false;
    }
    return true;
  };

  // ===== VALIDATION HELPERS =====
  const isValidEmail = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  };

  const isValidPhone = (phone) => {
    return /^(\+?250|0)?[0-9]{9,12}$/.test(phone);
  };

  // ===== PUBLIC API =====
  return {
    nextStep: (step) => {
      if (validateStep(step)) {
        showStep(step + 1);
      }
    },
    prevStep: (step) => {
      showStep(step - 1);
    },
    init: () => {
      initializeProfileUpload();
      showStep(1);
    }
  };
})();

// Make functions global
window.nextStep = (step) => RegistrationWizard.nextStep(step);
window.prevStep = (step) => RegistrationWizard.prevStep(step);

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  RegistrationWizard.init();
});

/* ============================================================
   UMUHUZA.ONLINE — CEO & IT Executive Admin Dashboard
   admin-dashboard.js | Interactive Controls & Charts
   ============================================================ */

(function () {
  'use strict';

  /* ----------------------------------------------------------
     SIDEBAR TOGGLE (desktop collapse + mobile drawer)
  ---------------------------------------------------------- */
  var menuToggle = document.getElementById('adMenuToggle');
  var sidebar    = document.getElementById('adSidebar');
  var overlay    = document.getElementById('adOverlay');
  var mainWrap   = document.getElementById('adMain');

  function closeSidebar() {
    if (sidebar) sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('active');
  }

  if (menuToggle) {
    menuToggle.addEventListener('click', function () {
      if (window.innerWidth < 992) {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
      } else {
        document.body.classList.toggle('ad-sidebar-collapsed');
        if (document.body.classList.contains('ad-sidebar-collapsed')) {
          sidebar.style.transform = 'translateX(-100%)';
          if (mainWrap) mainWrap.style.marginLeft = '0';
        } else {
          sidebar.style.transform = '';
          if (mainWrap) mainWrap.style.marginLeft = '';
        }
      }
    });
  }

  if (overlay) {
    overlay.addEventListener('click', closeSidebar);
  }

  window.addEventListener('resize', function () {
    if (window.innerWidth >= 992) {
      closeSidebar();
      sidebar.style.transform = '';
      if (mainWrap) mainWrap.style.marginLeft = '';
      document.body.classList.remove('ad-sidebar-collapsed');
    }
  });

  /* ----------------------------------------------------------
     NAVIGATION — View Switching
  ---------------------------------------------------------- */
  var navItems    = document.querySelectorAll('[data-ad-view]');
  var viewPanels  = document.querySelectorAll('.ad-view');
  var pageTitle   = document.getElementById('adPageTitle');
  var pageEyebrow = document.getElementById('adPageEyebrow');
  var pageSub     = document.getElementById('adPageSub');

  var viewMeta = {
    'executive':   { eyebrow: 'Command Center', title: 'Executive Overview', sub: 'High-level business performance, growth indicators, and critical actions for the CEO.' },
    'operations':  { eyebrow: 'Day-to-Day Management', title: 'Operations Center', sub: 'Manage users, marketplace listings, and service requests from a single operational hub.' },
    'finance':     { eyebrow: 'Finance & Trust', title: 'Finance & Trust Center', sub: 'Monitor payments, subscriptions, revenue, verification requests, and fraud signals.' },
    'technology':  { eyebrow: 'IT & Infrastructure', title: 'Technology & System Center', sub: 'Full infrastructure oversight — server health, security, backups, and audit logs.' },
    'notifications': { eyebrow: 'Alerts & Inbox', title: 'Notifications', sub: 'System alerts, payment notifications, approval requests, and critical platform messages.' },
    'settings':    { eyebrow: 'Configuration', title: 'Settings', sub: 'Platform configuration, administrator preferences, and access control management.' }
  };

  function switchView(target) {
    viewPanels.forEach(function (panel) {
      panel.classList.toggle('active', panel.getAttribute('data-view') === target);
    });
    navItems.forEach(function (item) {
      item.classList.toggle('active', item.getAttribute('data-ad-view') === target);
    });
    if (viewMeta[target]) {
      if (pageEyebrow) pageEyebrow.textContent = viewMeta[target].eyebrow;
      if (pageTitle)   pageTitle.textContent   = viewMeta[target].title;
      if (pageSub)     pageSub.textContent      = viewMeta[target].sub;
    }
    closeSidebar();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  navItems.forEach(function (item) {
    item.addEventListener('click', function () {
      var target = item.getAttribute('data-ad-view');
      if (target) switchView(target);
    });
  });

  /* ----------------------------------------------------------
     SUBMENU ACCORDION
  ---------------------------------------------------------- */
  document.querySelectorAll('.ad-nav-expandable').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      var submenu = btn.nextElementSibling;
      if (!submenu || !submenu.classList.contains('ad-submenu')) return;
      
      var isOpen = submenu.classList.contains('open');
      
      // Close all others
      document.querySelectorAll('.ad-submenu.open').forEach(function (sm) {
        if (sm !== submenu) sm.classList.remove('open');
      });
      document.querySelectorAll('.ad-nav-expandable.open').forEach(function (b) {
        if (b !== btn) b.classList.remove('open');
      });
      
      if (isOpen) {
        submenu.classList.remove('open');
        btn.classList.remove('open');
      } else {
        submenu.classList.add('open');
        btn.classList.add('open');
      }
    });
  });

  /* Submenu items switch view and navigate to sub-tabs */
  document.querySelectorAll('.ad-submenu-item[data-ad-view]').forEach(function (item) {
    item.addEventListener('click', function () {
      document.querySelectorAll('.ad-submenu-item').forEach(function (i) { i.classList.remove('active'); });
      item.classList.add('active');
      
      var target = item.getAttribute('data-ad-view');
      var subTab = item.getAttribute('data-sub-tab');
      if (target) {
        switchView(target);
        if (subTab) {
          var tabBtn = document.querySelector(`.ad-view[data-view="${target}"] .ad-tab[data-tab="${subTab}"]`);
          if (tabBtn) {
            tabBtn.click();
          }
        }
      }
    });
  });

  /* ----------------------------------------------------------
     TABS within a view
  ---------------------------------------------------------- */
  document.querySelectorAll('.ad-tab[data-tab]').forEach(function (tab) {
    tab.addEventListener('click', function () {
      var group = tab.closest('.ad-tab-group');
      var targetId = tab.getAttribute('data-tab');
      if (group) {
        group.querySelectorAll('.ad-tab').forEach(function (t) { t.classList.remove('active'); });
      }
      tab.classList.add('active');
      
      // Show/hide tab panels within the same section
      var section = tab.closest('section') || tab.closest('.ad-view');
      if (section) {
        section.querySelectorAll('.ad-tab-panel').forEach(function (panel) {
          panel.style.display = panel.getAttribute('data-tab-panel') === targetId ? 'block' : 'none';
        });
      }
    });
  });

  /* ----------------------------------------------------------
     SYSTEM MAINTENANCE ACTIONS
  ---------------------------------------------------------- */
  window.triggerSysAction = function (action) {
    var title = "";
    var message = "";
    if (action === 'backup') {
      title = "Database Backup";
      message = "Database backup has been initiated and successfully completed! Saved as: backup-2026-06-09.sql.gz (2.4 GB)";
    } else if (action === 'updates') {
      title = "System Update Check";
      message = "All packages are up to date. UMUHUZA.ONLINE core is running on version 2.4.1.";
    } else if (action === 'integrity') {
      title = "Security Verification";
      message = "System scan completed! Checksums match core repositories. 0 altered files detected.";
    }
    
    showToast(title, message, 'success');
  };

  function showToast(title, message, type) {
    var container = document.getElementById('adToastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'adToastContainer';
      container.style.position = 'fixed';
      container.style.top = '80px';
      container.style.right = '20px';
      container.style.zIndex = '9999';
      document.body.appendChild(container);
    }
    
    var toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show shadow-lg mb-2`;
    toast.style.minWidth = '320px';
    toast.innerHTML = `
      <strong>${title}</strong><br>
      <span style="font-size:0.82rem;">${message}</span>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    container.appendChild(toast);
    
    setTimeout(function () {
      if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
        var bsAlert = new bootstrap.Alert(toast);
        bsAlert.close();
      } else {
        toast.remove();
      }
    }, 4000);
  }

  /* ----------------------------------------------------------
     HEALTH SCORE RING
  ---------------------------------------------------------- */
  function animateHealthRing() {
    var fill = document.querySelector('.ad-health-ring-fill');
    if (!fill) return;
    var score = parseInt(fill.getAttribute('data-score') || '92', 10);
    var r = 42;
    var circumference = 2 * Math.PI * r;
    fill.style.strokeDasharray = circumference;
    fill.style.strokeDashoffset = circumference;
    setTimeout(function () {
      fill.style.strokeDashoffset = circumference - (score / 100) * circumference;
    }, 300);
  }

  /* ----------------------------------------------------------
     PROGRESS BARS animation
  ---------------------------------------------------------- */
  function animateProgressBars() {
    document.querySelectorAll('.ad-progress-fill[data-width]').forEach(function (bar) {
      bar.style.width = '0%';
      setTimeout(function () { bar.style.width = bar.getAttribute('data-width'); }, 200);
    });
    document.querySelectorAll('.ad-metric-bar-fill[data-width]').forEach(function (bar) {
      bar.style.width = '0%';
      setTimeout(function () { bar.style.width = bar.getAttribute('data-width'); }, 400);
    });
  }

  /* ----------------------------------------------------------
     CHART.JS INITIALIZATION
  ---------------------------------------------------------- */
  var chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: '#0F172A',
        titleColor: '#fff',
        bodyColor: 'rgba(255,255,255,0.7)',
        borderColor: 'rgba(255,255,255,0.08)',
        borderWidth: 1,
        padding: 10,
        cornerRadius: 8
      }
    }
  };

  function initCharts() {
    if (typeof Chart === 'undefined') return;

    /* Revenue Trend */
    var revEl = document.getElementById('adRevenueChart');
    if (revEl) {
      new Chart(revEl, {
        type: 'line',
        data: {
          labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
          datasets: [{
            label: 'Revenue (RWF)',
            data: [0, 480000, 720000, 1100000, 950000, 1400000, 1850000, 2100000, 1750000, 2400000, 2800000, 3200000],
            borderColor: '#1E40AF',
            backgroundColor: 'rgba(30,64,175,0.06)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.45,
            pointBackgroundColor: '#1E40AF',
            pointRadius: 3,
            pointHoverRadius: 6
          }]
        },
        options: Object.assign({}, chartDefaults, {
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: 'rgba(15,23,42,0.05)', drawBorder: false },
              ticks: { color: '#94A3B8', font: { size: 11 }, callback: function(v) { return v >= 1000000 ? (v/1000000).toFixed(1)+'M' : v >= 1000 ? (v/1000).toFixed(0)+'K' : v; } }
            },
            x: { grid: { display: false }, ticks: { color: '#94A3B8', font: { size: 11 } } }
          }
        })
      });
    }

    /* User Growth */
    var ugEl = document.getElementById('adUserGrowthChart');
    if (ugEl) {
      new Chart(ugEl, {
        type: 'bar',
        data: {
          labels: ['Jan','Feb','Mar','Apr','May','Jun'],
          datasets: [
            { label: 'Customers', data: [80, 120, 155, 200, 260, 310], backgroundColor: 'rgba(30,64,175,0.75)', borderRadius: 6 },
            { label: 'Providers', data: [25, 38, 52, 67, 88, 105], backgroundColor: 'rgba(212,160,23,0.75)', borderRadius: 6 }
          ]
        },
        options: Object.assign({}, chartDefaults, {
          plugins: Object.assign({}, chartDefaults.plugins, { legend: { display: true, labels: { color: '#64748B', font: { size: 11 } } } }),
          scales: {
            y: { grid: { color: 'rgba(15,23,42,0.05)', drawBorder: false }, ticks: { color: '#94A3B8', font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { color: '#94A3B8', font: { size: 11 } } }
          }
        })
      });
    }

    /* Request Status Donut */
    var reqEl = document.getElementById('adRequestDonut');
    if (reqEl) {
      new Chart(reqEl, {
        type: 'doughnut',
        data: {
          labels: ['Open', 'Assigned', 'Completed'],
          datasets: [{
            data: [18, 34, 112],
            backgroundColor: ['#DC2626','#D97706','#16A34A'],
            borderWidth: 0,
            hoverOffset: 6
          }]
        },
        options: Object.assign({}, chartDefaults, {
          cutout: '72%',
          plugins: Object.assign({}, chartDefaults.plugins, { legend: { display: true, position: 'bottom', labels: { color: '#64748B', font: { size: 11 }, padding: 14 } } })
        })
      });
    }

    /* Monthly Revenue Bar (Finance) */
    var finEl = document.getElementById('adFinanceBar');
    if (finEl) {
      new Chart(finEl, {
        type: 'bar',
        data: {
          labels: ['Jan','Feb','Mar','Apr','May','Jun'],
          datasets: [
            { label: 'Approved', data: [420000, 680000, 1050000, 880000, 1320000, 1900000], backgroundColor: 'rgba(22,163,74,0.75)', borderRadius: 6 },
            { label: 'Pending',  data: [60000,  70000,  150000,  70000,  180000,  200000],  backgroundColor: 'rgba(217,119,6,0.75)',  borderRadius: 6 }
          ]
        },
        options: Object.assign({}, chartDefaults, {
          plugins: Object.assign({}, chartDefaults.plugins, { legend: { display: true, labels: { color: '#64748B', font: { size: 11 } } } }),
          scales: {
            y: { grid: { color: 'rgba(15,23,42,0.05)', drawBorder: false }, ticks: { color: '#94A3B8', font: { size: 11 }, callback: function(v) { return v >= 1000000 ? (v/1000000).toFixed(1)+'M' : (v/1000).toFixed(0)+'K'; } } },
            x: { grid: { display: false }, ticks: { color: '#94A3B8', font: { size: 11 } } }
          }
        })
      });
    }

    /* System Load Line */
    var sysEl = document.getElementById('adSysLoadChart');
    if (sysEl) {
      var sysLabels = [];
      for (var h = 0; h < 24; h++) { sysLabels.push(h + ':00'); }
      new Chart(sysEl, {
        type: 'line',
        data: {
          labels: sysLabels,
          datasets: [
            { label: 'CPU %', data: [18,15,12,14,22,35,55,70,72,68,65,60,58,62,67,70,65,58,50,44,38,30,24,20], borderColor: '#1E40AF', backgroundColor: 'rgba(30,64,175,0.05)', borderWidth: 2, fill: true, tension: 0.45, pointRadius: 0 },
            { label: 'Memory %', data: [40,39,38,38,42,48,58,65,67,66,64,62,61,63,65,67,64,60,56,52,48,45,43,41], borderColor: '#D97706', backgroundColor: 'rgba(217,119,6,0.04)', borderWidth: 2, fill: true, tension: 0.45, pointRadius: 0 }
          ]
        },
        options: Object.assign({}, chartDefaults, {
          plugins: Object.assign({}, chartDefaults.plugins, { legend: { display: true, labels: { color: '#64748B', font: { size: 11 } } } }),
          scales: {
            y: { min: 0, max: 100, grid: { color: 'rgba(15,23,42,0.05)', drawBorder: false }, ticks: { color: '#94A3B8', font: { size: 10 }, callback: function(v) { return v + '%'; } } },
            x: { grid: { display: false }, ticks: { color: '#94A3B8', font: { size: 9 }, maxTicksLimit: 8 } }
          }
        })
      });
    }

    /* API Response Time */
    var apiEl = document.getElementById('adApiChart');
    if (apiEl) {
      new Chart(apiEl, {
        type: 'line',
        data: {
          labels: ['00:00','03:00','06:00','09:00','12:00','15:00','18:00','21:00'],
          datasets: [{
            label: 'Response Time (ms)',
            data: [82,75,68,95,145,138,120,88],
            borderColor: '#16A34A',
            backgroundColor: 'rgba(22,163,74,0.06)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#16A34A'
          }]
        },
        options: Object.assign({}, chartDefaults, {
          scales: {
            y: { grid: { color: 'rgba(15,23,42,0.05)', drawBorder: false }, ticks: { color: '#94A3B8', font: { size: 11 }, callback: function(v) { return v + 'ms'; } } },
            x: { grid: { display: false }, ticks: { color: '#94A3B8', font: { size: 11 } } }
          }
        })
      });
    }
  }

  /* ----------------------------------------------------------
     REAL-TIME CLOCK in top nav
  ---------------------------------------------------------- */
  var clockEl = document.getElementById('adClock');
  function updateClock() {
    if (!clockEl) return;
    var now = new Date();
    var h = now.getHours().toString().padStart(2, '0');
    var m = now.getMinutes().toString().padStart(2, '0');
    var s = now.getSeconds().toString().padStart(2, '0');
    clockEl.textContent = h + ':' + m + ':' + s;
  }
  setInterval(updateClock, 1000);
  updateClock();

  /* ----------------------------------------------------------
     COUNTER ANIMATION on KPI values
  ---------------------------------------------------------- */
  function animateCounters() {
    document.querySelectorAll('.ad-kpi-value[data-count]').forEach(function (el) {
      var target = parseInt(el.getAttribute('data-count'), 10);
      var prefix = el.getAttribute('data-prefix') || '';
      var suffix = el.getAttribute('data-suffix') || '';
      var duration = 900;
      var start = 0;
      var startTime = null;
      function step(timestamp) {
        if (!startTime) startTime = timestamp;
        var progress = Math.min((timestamp - startTime) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        var value = Math.floor(eased * target);
        el.textContent = prefix + value.toLocaleString() + suffix;
        if (progress < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    });
  }

  /* ----------------------------------------------------------
     NOTIFICATIONS mark-as-read
  ---------------------------------------------------------- */
  document.querySelectorAll('.ad-notif-item').forEach(function (item) {
    item.addEventListener('click', function () {
      item.classList.remove('unread');
      updateNotifBadge();
    });
  });

  function updateNotifBadge() {
    var badge = document.querySelector('.ad-notif-badge');
    if (!badge) return;
    var unread = document.querySelectorAll('.ad-notif-item.unread').length;
    badge.textContent = unread;
    badge.style.display = unread > 0 ? 'flex' : 'none';
  }

  /* ----------------------------------------------------------
     COLLAPSIBLE SECTIONS (expand/collapse cards)
  ---------------------------------------------------------- */
  document.querySelectorAll('[data-ad-collapse]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var targetId = btn.getAttribute('data-ad-collapse');
      var target = document.getElementById(targetId);
      if (!target) return;
      var isOpen = target.style.display !== 'none';
      target.style.display = isOpen ? 'none' : 'block';
      var icon = btn.querySelector('i');
      if (icon) {
        icon.className = isOpen ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
      }
    });
  });

  /* ----------------------------------------------------------
     INIT — Run on DOM ready
  ---------------------------------------------------------- */
  function init() {
    switchView('executive');
    animateCounters();
    animateHealthRing();
    animateProgressBars();
    initCharts();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();

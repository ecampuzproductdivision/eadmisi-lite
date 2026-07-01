/**
 * Dashboard Onboarding Tour using Driver.js
 * Introduces the layout and menus on the dashboard page.
 * 
 * NOTE: Driver.js is loaded from CDN. See app.blade.php for CDN link.
 */

// Tutorial identifier — must match a key in OnboardingController::getTutorials()
var TUTORIAL_ID = 'onboarding';

var ONBOARDING_STEPS = [
  {
    element: '#miniSidebar',
    popover: {
      title: 'Navigasi Sidebar',
      description: 'Sidebar ini berisi menu-menu utama aplikasi. Klik menu untuk membuka halaman terkait. Gunakan tanda panah untuk membuka submenu.',
      side: 'right',
      align: 'start',
    },
    onHighlighted: function() { markStep('sidebar'); },
  },
  {
    element: '#content .btn-white',
    popover: {
      title: 'Pencarian Fitur',
      description: 'Gunakan kolom pencarian ini untuk mencari fitur yang tersedia dengan cepat. Tekan <strong>Ctrl+K</strong> untuk fokus ke pencarian.',
      side: 'bottom',
      align: 'start',
    },
    onHighlighted: function() { markStep('search'); },
  },
  {
    element: '.ms-3.dropdown',
    popover: {
      title: 'Profile & Pengaturan',
      description: 'Klik avatar Anda untuk mengakses menu Profile, Account Settings, Panduan Aplikasi, dan Logout.',
      side: 'bottom',
      align: 'end',
    },
    onHighlighted: function() { markStep('profile'); },
  },
  {
    element: '.sidebar-toggle',
    popover: {
      title: 'Toggle Sidebar',
      description: 'Klik tombol ini untuk memperluas atau mengecilkan sidebar, memberikan lebih banyak ruang untuk konten.',
      side: 'bottom',
      align: 'start',
    },
    onHighlighted: function() { markStep('sidebar-toggle'); },
  },
  {
    element: '.btn-ghost.btn-icon',
    popover: {
      title: 'Mode Tampilan',
      description: 'Ganti tampilan aplikasi antara mode <strong>Light</strong>, <strong>Dark</strong>, atau <strong>Auto</strong> (mengikuti sistem).',
      side: 'bottom',
      align: 'end',
    },
    onHighlighted: function() { markStep('theme'); },
  },
  {
    element: '.badge.bg-primary-subtle, .badge.bg-warning-subtle',
    popover: {
      title: 'Periode Aktif',
      description: 'Menampilkan periode pendaftaran yang sedang aktif. Jika tidak ada periode aktif, akan muncul peringatan.',
      side: 'bottom',
      align: 'end',
    },
    onHighlighted: function() { markStep('periode'); },
  },
  {
    element: '.position-relative.btn-icon.btn-ghost',
    popover: {
      title: 'Notifikasi',
      description: 'Lihat pemberitahuan dan aktivitas terbaru di sini. Tanda merah menunjukkan notifikasi yang belum dibaca.',
      side: 'bottom',
      align: 'end',
    },
    onHighlighted: function() { markStep('notifications'); },
  },
  {
    element: '#content',
    popover: {
      title: 'Konten Dashboard',
      description: 'Area ini menampilkan konten utama halaman, seperti statistik, grafik, dan data lainnya.',
      side: 'top',
      align: 'center',
    },
    onHighlighted: function() { markStep('content'); },
  },
];

/**
 * Mark a step as completed and update the checklist widget.
 * Sends tutorial_id along with step for multi-tutorial support.
 */
function markStep(stepId) {
  fetch('/onboarding/complete-step', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ 
      step: stepId,
      tutorial_id: TUTORIAL_ID,
    }),
  })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.tutorials_progress && window.updateOnboardingChecklist) {
        window.updateOnboardingChecklist(data.tutorials_progress);
      }
      // If all tutorials completed, show completion modal
      if (data.all_completed) {
        showTutorialCompletionModal();
      }
    })
    .catch(function() {});
}

var tourInstance = null;

/**
 * Start the dashboard tour.
 * If already running, destroy and restart.
 */
function startDashboardTour() {
  // Destroy previous instance if exists
  if (tourInstance) {
    try { tourInstance.destroy(); } catch (e) {}
    tourInstance = null;
  }

  // Access Driver.js factory: window.driver.js.driver is the factory function
  var DriverFactory = window.driver && window.driver.js && window.driver.js.driver;
  if (typeof DriverFactory !== 'function') {
    console.error('Driver.js not loaded yet. Retrying in 500ms...');
    setTimeout(startDashboardTour, 500);
    return;
  }

  tourInstance = DriverFactory({
    showProgress: true,
    showButtons: ['next', 'previous', 'close'],
    steps: ONBOARDING_STEPS,
    progressText: 'Langkah {{current}} dari {{total}}',
    nextBtnText: 'Lanjut',
    prevBtnText: 'Kembali',
    doneBtnText: 'Selesai',
    closeBtnText: 'Tutup',
    onDestroyStarted: function() {
      if (tourInstance) {
        tourInstance.destroy();
        tourInstance = null;
      }
    },
    onDestroyed: function() {
      tourInstance = null;
    },
  });

  tourInstance.drive();
}

/**
 * Show the tutorial completion modal with confetti animation.
 */
function showTutorialCompletionModal() {
  var modalEl = document.getElementById('tutorialCompletionModal');
  if (!modalEl) return;

  var modal = new bootstrap.Modal(modalEl, {
    backdrop: 'static',
    keyboard: false,
  });
  modal.show();

  // Fire confetti animation
  if (typeof confetti === 'function') {
    // Fire multiple bursts for a nice effect
    var duration = 3000;
    var end = Date.now() + duration;

    (function frame() {
      confetti({
        particleCount: 3,
        angle: 60,
        spread: 55,
        origin: { x: 0 },
        colors: ['#f63a4c', '#ffd700', '#0ea5e9', '#22c55e'],
      });
      confetti({
        particleCount: 3,
        angle: 120,
        spread: 55,
        origin: { x: 1 },
        colors: ['#f63a4c', '#ffd700', '#0ea5e9', '#22c55e'],
      });

      if (Date.now() < end) {
        requestAnimationFrame(frame);
      }
    })();
  }
}

// Make startDashboardTour available globally
window.startDashboardTour = startDashboardTour;
window.showTutorialCompletionModal = showTutorialCompletionModal;
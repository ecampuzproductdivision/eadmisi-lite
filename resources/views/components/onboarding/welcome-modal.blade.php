{{-- 
  Welcome Modal Component
  Shown on first login to introduce the onboarding tour.
--}}
<div class="modal fade" id="welcomeOnboardingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="border-radius: 16px;">
      <div class="modal-body p-5 text-center">
        {{-- Welcome Illustration --}}
        <div class="mb-4">
          <div class="mx-auto bg-primary-subtle text-primary d-flex align-items-center justify-content-center" 
               style="width: 80px; height: 80px; border-radius: 20px;">
            <i class="ti ti-school fs-1"></i>
          </div>
        </div>

        <h3 class="fw-bold mb-4">Selamat Datang di eAdmisi!</h2>
        <p class="text-secondary mb-4">Platform Sistem Penerimaan Mahasiswa. Ikuti tour singkat untuk mengenali fitur dan tata letak aplikasi.</p>

        <div class="gap-4">
          <button type="button" class="btn btn-light" id="skipOnboardingTour">
            Nanti Saja
          </button>
          <button type="button" class="btn btn-primary" id="startOnboardingTour">
            <i class="ti ti-player-play-filled me-2"></i> Lanjutkan Tour
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Check if we should show welcome modal
  const welcomeModal = document.getElementById('welcomeOnboardingModal');
  if (!welcomeModal) return;

  // Start tour button
  document.getElementById('startOnboardingTour')?.addEventListener('click', function() {
    fetch('{{ route("onboarding.complete-welcome") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, 'Content-Type': 'application/json' } })
      .then(() => {
        const modal = bootstrap.Modal.getInstance(welcomeModal);
        if (modal) modal.hide();
        // Start the Driver.js tour
        if (typeof startDashboardTour === 'function') {
          setTimeout(() => startDashboardTour(), 300);
        }
      });
  });

  // Skip button — show info popover pointing to "Panduan Aplikasi" in profile dropdown
  document.getElementById('skipOnboardingTour')?.addEventListener('click', function() {
    fetch('{{ route("onboarding.dismiss") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, 'Content-Type': 'application/json' } })
      .then(() => {
        const modal = bootstrap.Modal.getInstance(welcomeModal);
        if (modal) modal.hide();

        // Open the profile dropdown to reveal the "Panduan Aplikasi" menu
        const profileDropdown = document.querySelector('.ms-3.dropdown > a[data-bs-toggle="dropdown"]');
        if (profileDropdown) {
          const dropdown = bootstrap.Dropdown.getOrCreateInstance(profileDropdown);
          dropdown.show();

          // After dropdown animation completes, show Driver.js popover
          setTimeout(function() {
            var DriverFactory = window.driver && window.driver.js && window.driver.js.driver;
            if (typeof DriverFactory !== 'function') return;

            var skipTour = DriverFactory({
              showProgress: false,
              showButtons: ['close'],
              closeBtnText: 'Tutup',
              steps: [{
                element: '#onboardingMenuEntry',
                popover: {
                  title: 'Panduan Aplikasi',
                  description: 'Anda dapat mengakses kembali panduan aplikasi ini kapan saja melalui menu <strong>Panduan Aplikasi</strong> di dropdown Profile Anda.',
                  side: 'left',
                  align: 'center',
                },
              }],
              onDestroyed: function() {
                // Close the dropdown after popover is closed
                if (dropdown) dropdown.hide();
              },
            });

            skipTour.drive();
          }, 400);
        }
      });
  });
});
</script>
@endpush
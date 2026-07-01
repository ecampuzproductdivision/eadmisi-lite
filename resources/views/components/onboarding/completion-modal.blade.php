{{-- 
  Tutorial Completion Modal
  Shown when user completes all tutorial tours.
  Includes confetti animation celebration.
--}}
<div class="modal fade" id="tutorialCompletionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow" style="border-radius: 16px;">
      <div class="modal-body p-5 text-center">
        {{-- Celebration Icon --}}
        <div class="mb-4">
          <div class="mx-auto bg-success-subtle text-success d-flex align-items-center justify-content-center" 
               style="width: 80px; height: 80px; border-radius: 20px; animation: pulse-success 1.5s ease-in-out infinite;">
            <i class="ti ti-celebration fs-1"></i>
          </div>
        </div>

        <h3 class="fw-bold mb-3">🎉 Selamat!</h3>
        <p class="text-secondary mb-4 fs-5">Anda telah menyelesaikan seluruh Tutorial Tour.</p>
        <p class="text-muted mb-4">Sekarang Anda siap menggunakan aplikasi eAdmisi dengan lebih percaya diri.</p>

        <div class="d-flex justify-content-center">
          <button type="button" class="btn btn-primary px-5" data-bs-dismiss="modal" id="closeTutorialCompletion">
            <i class="ti ti-check me-2"></i> Selesai
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes pulse-success {
  0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); }
  50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
  100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
}
</style>
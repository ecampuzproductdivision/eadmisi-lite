@auth
@if(!auth()->user()->isProfileComplete())
@php
    $user = auth()->user();
    $locations = json_decode(file_get_contents(public_path('assets/data/wilayah_indonesia.json')), true) ?? [];
    $userDomisili = $user->domisili ?? $user->regency_id;
    $domisiliExists = false;
@endphp
<style>
/* Standard Bootstrap 5 Modal Z-Index Stack Fix */
.modal-backdrop {
    z-index: 1050 !important;
}
#modalCompleteProfile {
    z-index: 1055 !important;
}
#modalCompleteProfile .modal-dialog {
    z-index: 1056 !important;
    pointer-events: auto !important;
}
#modalCompleteProfile .modal-content {
    z-index: 1057 !important;
    pointer-events: auto !important;
    background-color: #ffffff !important;
    overflow: visible !important;
}
#modalCompleteProfile .modal-body {
    overflow: visible !important;
}
#modalCompleteProfile input,
#modalCompleteProfile select,
#modalCompleteProfile button,
#modalCompleteProfile label,
#modalCompleteProfile .form-check-input {
    pointer-events: auto !important;
    position: relative !important;
}
#modalCompleteProfile .select2-container--default .select2-selection--single {
    height: 48px !important;
    padding: 8px 12px !important;
    border: 1px solid #d9dee3 !important;
    border-radius: 0.375rem !important;
    cursor: pointer !important;
}
#modalCompleteProfile .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 30px !important;
    color: #43495b !important;
}
#modalCompleteProfile .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 46px !important;
}
/* Select2 Dropdown sits on top of modal content (z-index 1060) */
.select2-container--open,
.select2-container--open .select2-dropdown,
#modalCompleteProfile .select2-dropdown {
    z-index: 1060 !important;
    pointer-events: auto !important;
}
</style>

<div class="modal fade" id="modalCompleteProfile" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalCompleteProfileLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: visible;">
            <div class="modal-header bg-primary text-white p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="ti ti-user-check fs-2"></i>
                    </div>
                    <div>
                        <h4 class="modal-title fw-bold text-white mb-0" id="modalCompleteProfileLabel">Lengkapi Biodata Singkat</h4>
                        <p class="small text-white-50 mb-0">Silakan lengkapi data profil Anda untuk membuka akses penuh ke Portal PMB.</p>
                    </div>
                </div>
            </div>

            <form id="formCompleteProfile" action="{{ route('profile.complete-biodata') }}" method="POST">
                @csrf
                <div class="modal-body p-4" style="overflow: visible;">
                    <div id="modalAlertError" class="alert alert-danger d-none mb-3 py-2 small">
                        <i class="ti ti-alert-circle me-1"></i> <span class="error-text"></span>
                    </div>

                    <!-- Informational Banner -->
                    <div class="alert alert-info border-0 shadow-sm d-flex gap-3 mb-4 rounded-3">
                        <i class="ti ti-info-circle fs-3 text-info flex-shrink-0 mt-1"></i>
                        <div class="small">
                            <strong>Selamat Datang, {{ $user->name }}!</strong><br>
                            Data Nama & Email telah terisi otomatis dari akun Google Anda. Silakan periksa kembali dan lengkapi Jenis Kelamin, No. HP, dan Asal Kota/Kabupaten.
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- 1. Nama Lengkap (Autofilled from Google, Editable) -->
                        <div class="col-md-6 mb-2">
                            <label for="modal_nama_lengkap" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="modal_nama_lengkap" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap', $user->name) }}" required placeholder="Nama lengkap sesuai kependudukan">
                        </div>

                        <!-- 2. Email (Autofilled from Google, Readonly) -->
                        <div class="col-md-6 mb-2">
                            <label for="modal_email" class="form-label fw-semibold">Alamat Email <span class="text-muted small">(Terkunci / Readonly)</span></label>
                            <input type="email" id="modal_email" class="form-control bg-light" name="email" value="{{ $user->email }}" readonly tabindex="-1">
                        </div>

                        <!-- 3. Jenis Kelamin -->
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" id="modal_jk_l" {{ (old('jenis_kelamin', $user->jenis_kelamin) === 'L') ? 'checked' : '' }} required>
                                    <label class="form-check-label fw-medium" for="modal_jk_l">
                                        <i class="ti ti-gender-male text-primary me-1"></i> Laki-Laki
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" id="modal_jk_p" {{ (old('jenis_kelamin', $user->jenis_kelamin) === 'P') ? 'checked' : '' }} required>
                                    <label class="form-check-label fw-medium" for="modal_jk_p">
                                        <i class="ti ti-gender-female text-danger me-1"></i> Perempuan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Nomor Handphone / WhatsApp -->
                        <div class="col-md-6 mb-2">
                            <label for="modal_phone" class="form-label fw-semibold">Nomor Handphone (WhatsApp) <span class="text-danger">*</span></label>
                            <input type="text" id="modal_phone" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="08xxxxxxxxxx" autocomplete="tel">
                        </div>

                        <!-- 5. Dimana Kamu Tinggal? (Kota/Kabupaten Autocomplete) -->
                        <div class="col-12 mb-2">
                            <label for="modal_domisili" class="form-label fw-semibold">Dimana Kamu Tinggal? (Kota/Kabupaten) <span class="text-danger">*</span></label>
                            <select id="modal_domisili" class="form-control select2-modal-domisili" name="domisili" required style="width: 100%;">
                                <option value=""></option>
                                @foreach($locations as $loc)
                                    @php
                                        $isSelected = ($userDomisili == $loc['id'] || $userDomisili == $loc['text']);
                                        if ($isSelected) $domisiliExists = true;
                                    @endphp
                                    <option value="{{ $loc['id'] }}" {{ $isSelected ? 'selected' : '' }}>{{ $loc['text'] }}</option>
                                @endforeach
                                @if($userDomisili && !$domisiliExists)
                                    <option value="{{ $userDomisili }}" selected>{{ $userDomisili }}</option>
                                @endif
                            </select>
                            <small class="text-muted d-block mt-1">Ketikkan nama kota atau kabupaten tempat Anda tinggal saat ini.</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light p-4 border-top" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="submit" id="btnSubmitModalProfile" class="btn btn-primary btn-lg w-100 py-3 font-weight-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="ti ti-send fs-4"></i> Simpan & Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modalCompleteProfile');
    if (!modalEl) return;

    // Clean any lingering duplicate backdrops before showing
    document.querySelectorAll('.modal-backdrop').forEach(function(el, index) {
        if (index > 0) el.remove();
    });

    // Disable Bootstrap focus trap conflict with Select2 search
    if (typeof jQuery !== 'undefined' && jQuery.fn.modal && jQuery.fn.modal.Constructor && jQuery.fn.modal.Constructor.prototype) {
        jQuery.fn.modal.Constructor.prototype._enforceFocus = function() {};
    }

    // Function to initialize Select2 safely inside modal
    function initSelect2Domisili() {
        if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
            var $select = $('.select2-modal-domisili');
            if (!$select.hasClass("select2-hidden-accessible")) {
                $select.select2({
                    dropdownParent: $('#modalCompleteProfile'),
                    placeholder: "Pilih / Ketik Kota atau Kabupaten Domisili...",
                    allowClear: true,
                    width: '100%'
                });
            }
        }
    }

    // Hide any other conflicting modals
    var otherModals = document.querySelectorAll('.modal');
    otherModals.forEach(function(m) {
        if (m.id !== 'modalCompleteProfile') {
            try {
                var inst = bootstrap.Modal.getInstance(m);
                if (inst) inst.hide();
            } catch(e) {}
        }
    });

    // Show modal automatically on page load
    var bsModal = new bootstrap.Modal(modalEl, {
        backdrop: 'static',
        keyboard: false,
        focus: false
    });
    bsModal.show();

    // Initialize Select2 when modal is shown
    modalEl.addEventListener('shown.bs.modal', function() {
        initSelect2Domisili();
    });

    // Fallback init
    setTimeout(initSelect2Domisili, 200);

    // Prevent closing via Esc key or backdrop
    modalEl.addEventListener('hide.bs.modal', function(e) {
        if (!window.__profileCompleted) {
            e.preventDefault();
            return false;
        }
    });

    // Handle Form Submission via AJAX
    var formEl = document.getElementById('formCompleteProfile');
    if (formEl) {
        formEl.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = document.getElementById('btnSubmitModalProfile');
            var alertErr = document.getElementById('modalAlertError');
            
            if (alertErr) alertErr.classList.add('d-none');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Menyimpan...';

            var formData = new FormData(formEl);

            fetch(formEl.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    window.__profileCompleted = true;
                    bsModal.hide();
                    
                    // Cleanup backdrop artifacts
                    document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Profil Berhasil Dilengkapi',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ti ti-send fs-4"></i> Simpan & Lanjutkan';
                    if (alertErr) {
                        alertErr.querySelector('.error-text').innerText = data.message || 'Gagal menyimpan biodata. Silakan periksa kembali.';
                        alertErr.classList.remove('d-none');
                    }
                }
            })
            .catch(function(err) {
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-send fs-4"></i> Simpan & Lanjutkan';
                if (alertErr) {
                    alertErr.querySelector('.error-text').innerText = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
                    alertErr.classList.remove('d-none');
                }
            });
        });
    }
});
</script>
@endpush
@endif
@endauth

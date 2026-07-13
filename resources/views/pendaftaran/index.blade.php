@extends('layouts.app')

@section('content')
@component('components.data-page-layout', ['data' => $registrations])
    @slot('breadcrumbs', [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Pendaftaran PMB', 'active' => true],
    ])
    @slot('title', 'Pendaftaran PMB')
    @slot('description', 'Data pendaftaran yang sudah disubmit oleh calon mahasiswa.')
    @slot('actions')

    @endslot
    @slot('filters')
        <div class="col-md-3 col-12">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="ti ti-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama / NIK / No. HP..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <select name="path_id" class="form-select">
                <option value="">Semua Jalur</option>
                @foreach($paths as $path)
                    <option value="{{ $path->id }}" {{ request('path_id') == $path->id ? 'selected' : '' }}>{{ $path->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-12">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="documents_uploaded" {{ request('status') == 'documents_uploaded' ? 'selected' : '' }}>Dokumen Diupload</option>
                <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                <option value="payment_verified" {{ request('status') == 'payment_verified' ? 'selected' : '' }}>Pembayaran Terverifikasi</option>
                <option value="exam_completed" {{ request('status') == 'exam_completed' ? 'selected' : '' }}>Ujian Selesai</option>
                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Direview</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="Gagal" {{ request('status') == 'Gagal' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>
        <div class="col-md-3 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-white border"><i class="ti ti-filter"></i> Filter</button>
            <a href="{{ route('pendaftaran.index') }}" class="btn btn-white border px-3" title="Reset Filter"><i class="ti ti-refresh"></i></a>
        </div>
    @endslot
    @slot('exports')
        <button type="button" class="btn btn-outline-primary d-inline-flex align-items-center gap-1 me-2" data-bs-toggle="modal" data-bs-target="#importExcelModal">
            <i class="ti ti-upload"></i> Import Excel
        </button>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.location.href='{{ route('pendaftaran.index') }}?export=xls'">
            <i class="ti ti-file-spreadsheet"></i> .xls
        </a>
        <a href="#" class="btn btn-white d-inline-flex align-items-center gap-1" onclick="window.print()">
            <i class="ti ti-printer"></i> Print
        </a>
        <button id="btn-bulk-kelulusan" class="btn btn-primary ml-2 d-inline-flex align-items-center gap-1">
            <i class="ti ti-checklist"></i> Proses Kelulusan Massal
        </button>
    @endslot
    @slot('table')
        <form id="bulk-kelulusan-form" action="{{ route('pendaftaran.bulk-kelulusan') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="" id="bulk-action-input">
        <table class="table table-hover align-middle mb-0 no-sticky-global table-ead">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3" style="width:40px;">
                        <input type="checkbox" id="check-all" class="form-check-input">
                    </th>
                    <th class="ps-2 py-3 fw-semibold" style="width:50px;">#</th>
                    <th class="py-3 fw-semibold">Nama Lengkap</th>
                    <th class="py-3 fw-semibold">Jalur</th>
                    <th class="py-3 fw-semibold">No. HP</th>
                    <th class="py-3 fw-semibold">Tgl Daftar</th>
                    <th class="py-3 fw-semibold">Status</th>
                    <th class="pe-4 py-3 fw-semibold text-end" style="width:60px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $registration)
                @php
                    // ═══ RESET ALL STATE VARIABLES PER ITERATION ═══
                    $pathObj = null;
                    $totalRequiredDocs = 0;
                    $totalUploadedDocs = 0;
                    $hasExamBeenTaken = false;
                    $isPaymentLocked = true;
                    $statusLabel = null;
                    $badgeBg = 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle';
                    $badgeText = '';
                    $subBadge = '';

                    $pathObj = $registration->registrationPath;

                    $paidInvoice = $registration->payments->firstWhere('transaction_status', 'success');
                    if ($paidInvoice) $isPaymentLocked = false;

                    if ($pathObj && $pathObj->templateBerkas) {
                        $totalRequiredDocs = $pathObj->templateBerkas->syaratDokumens()
                            ->where('status_wajib', true)
                            ->count();
                    }
                    $totalUploadedDocs = $registration->documents->count();

                    if ($pathObj && $pathObj->is_ujian_online) {
                        $hasExamBeenTaken = $registration->examResults
                            ->where('status', 'completed')
                            ->isNotEmpty();
                    }

                    $isStep3Completed = ($totalRequiredDocs == 0) || ($totalUploadedDocs >= $totalRequiredDocs);

                    // ── PRIORITY: Re-registration Lunas state ──
                    if ($registration->status_kelulusan === 'Lulus' && $registration->status_registrasi_ulang === 'sudah_registrasi_lunas') {
                        $nimDisplay = $registration->nim ? ' (NIM: ' . $registration->nim . ')' : '';
                        $badgeBg = 'bg-success-subtle text-success-emphasis border border-success-subtle'; $badgeText = ''; $statusLabel = 'Sudah Melakukan Registrasi Ulang' . $nimDisplay; $subBadge = '';
                    } elseif ($registration->status === 'rejected') {
                        $badgeBg = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle'; $badgeText = ''; $statusLabel = 'Ditolak'; $subBadge = '';
                    } elseif ($registration->status === 'accepted') {
                        $badgeBg = 'bg-success-subtle text-success-emphasis border border-success-subtle'; $badgeText = ''; $statusLabel = 'Diterima'; $subBadge = '';
                    } elseif ($registration->status === 'Lulus') {
                        $badgeBg = 'bg-success-subtle text-success-emphasis border border-success-subtle'; $badgeText = ''; $statusLabel = 'Lulus'; $subBadge = '';
                    } elseif ($registration->status === 'Gagal') {
                        $badgeBg = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle'; $badgeText = ''; $statusLabel = 'Gagal'; $subBadge = '';
                    } elseif ($registration->status === 'Menunggu Verifikasi Registrasi Ulang') {
                        $badgeBg = 'bg-info-subtle text-info-emphasis border border-info-subtle'; $badgeText = ''; $statusLabel = 'Menunggu Pembayaran Registrasi Ulang'; $subBadge = '';
                    } elseif ($registration->status === 'registered') {
                        $nimDisplay = $registration->nim ? ' (NIM: ' . $registration->nim . ')' : '';
                        $badgeBg = 'bg-success-subtle text-success-emphasis border border-success-subtle'; $badgeText = ''; $statusLabel = 'Terdaftar' . $nimDisplay; $subBadge = '';
                    } elseif ($registration->status === 'reviewed') {
                        $badgeBg = 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle'; $badgeText = ''; $statusLabel = 'Direview'; $subBadge = '';
                    } elseif ($registration->status === 'exam_completed') {
                        $badgeBg = 'bg-primary-subtle text-primary-emphasis border border-primary-subtle'; $badgeText = ''; $statusLabel = 'Ujian Selesai'; $subBadge = '';
                    } elseif ($registration->status === 'payment_pending') {
                        $isPaymentLocked = true;
                    }

                    if (!isset($statusLabel)) {
                        if ($isPaymentLocked) {
                            $badgeBg = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle'; $badgeText = ''; $statusLabel = 'Menunggu Pembayaran'; $subBadge = '';
                        } else {
                            $subBadge = '<span class="badge bg-success-subtle text-success-emphasis border border-success-subtle mt-1 d-inline-block" style="font-size:0.65rem;">Pembayaran Terverifikasi</span>';
                            if ($totalRequiredDocs > 0 && !$isStep3Completed) {
                                $badgeBg = 'bg-warning-subtle text-warning-emphasis'; $badgeText = ''; $statusLabel = 'Belum Unggah Berkas';
                            } elseif ($pathObj && $pathObj->is_ujian_online && !$hasExamBeenTaken) {
                                $badgeBg = 'bg-info-subtle text-info-emphasis border border-info-subtle'; $badgeText = ''; $statusLabel = 'Menunggu Ujian';
                            } else {
                                $badgeBg = 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle'; $badgeText = ''; $statusLabel = 'Menunggu Verifikasi Berkas';
                            }
                        }
                    }

                    $paymentVerified = !$isPaymentLocked;
                    $canProcessKelulusan = $paymentVerified && !in_array($registration->status, ['Lulus', 'Gagal', 'rejected', 'accepted', 'Menunggu Verifikasi Registrasi Ulang', 'registered']);
                @endphp
                <tr>
                    <td class="ps-4 py-3">
                        @if($canProcessKelulusan)
                            <input type="checkbox" name="selected_ids[]" value="{{ $registration->id }}" class="form-check-input row-checkbox">
                        @endif
                    </td>
                    <td class="ps-2 py-3 text-muted">{{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}</td>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <span class="fw-bold text-primary">{{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}</span>
                            </div>
                            <div>
                                <span class="fw-semibold">{{ $registration->nama_lengkap }}</span>
                                @if($registration->no_pendaftaran)<br><span class="text-primary small fw-bold">No. Reg: {{ $registration->no_pendaftaran }}</span>@endif
                                @if($registration->nik)<br><span class="text-muted small">NIK: {{ $registration->nik }}</span>@endif
                            </div>
                        </div>
                    </td>
                    <td class="py-3"><span>{{ $registration->registrationPath?->name ?? '-' }}</span></td>
                    <td class="py-3">{{ $registration->no_hp ?? '-' }}</td>
                    <td class="py-3">{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3">
                        <span class="badge {{ $badgeBg }} rounded-pill px-3 py-1 fw-semibold">{{ $statusLabel }}</span>
                        @if(!empty($subBadge))
                            {!! $subBadge !!}
                        @endif
                    </td>
                    <td class="text-end">
                        @php
                            $dropdownItems = [
                                ['url' => route('pendaftaran.show', $registration->id), 'icon' => 'ti ti-eye', 'label' => 'Detail', 'title' => 'Lihat Detail Pendaftaran'],
                            ];
                            if ($canProcessKelulusan) {
                                $dropdownItems[] = ['onclick' => "processSingle('{$registration->id}', 'Lulus')", 'icon' => 'ti ti-circle-check', 'label' => 'Set Lulus Seleksi', 'class' => 'text-success'];
                                $dropdownItems[] = ['onclick' => "processSingle('{$registration->id}', 'Gagal')", 'icon' => 'ti ti-circle-x', 'label' => 'Set Gagal Seleksi', 'class' => 'text-danger'];
                            }
                        @endphp
                        @include('components.actions-dropdown', ['items' => $dropdownItems])
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-5">@include('components.empty-state', ['icon' => 'ti-inbox', 'title' => 'Belum ada data pendaftaran', 'subtitle' => 'Belum ada calon mahasiswa yang melakukan submit pendaftaran.'])</td></tr>
                @endforelse
            </tbody>
        </table>
        </form>
        @if($registrations->hasPages())
            <div class="card-footer bg-light border-top d-flex justify-content-center py-3">{{ $registrations->appends(request()->query())->links() }}</div>
        @endif
    @endslot
@endcomponent

    <!-- Modal Import Excel -->
    <div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="importExcelModalLabel">
                        <i class="ti ti-upload text-primary me-2"></i>Import Data Calon Mahasiswa via Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pendaftaran.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 rounded-3 mb-4 d-flex align-items-start gap-2 shadow-none">
                            <i class="ti ti-info-circle text-info fs-4 mt-0"></i>
                            <div>
                                <span class="fw-semibold text-info d-block">Panduan Pengisian</span>
                                <small class="text-muted" style="font-size: 0.8rem; display: block; line-height: 1.4;">
                                    Silakan pilih jalur pendaftaran terlebih dahulu untuk mengunduh template Excel yang sesuai. Kolom data pada Excel akan menyesuaikan dengan field dinamis aktif jalur tersebut.
                                    <br><strong>Username:</strong> Nomor Pendaftaran
                                    <br><strong>Password Default:</strong> <code>pendaftaran123</code>
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Jalur Pendaftaran <span class="text-danger">*</span></label>
                            <select name="registration_path_id" id="import_path_select" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Jalur Pendaftaran --</option>
                                @foreach($paths as $path)
                                    <option value="{{ $path->id }}">{{ $path->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <a href="#" id="download-template-btn" class="btn btn-light-primary text-primary w-100 disabled d-inline-flex align-items-center justify-content-center gap-2 py-2">
                                <i class="ti ti-file-text fs-5"></i> 📄 Download Template Excel
                            </a>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload File Excel (.xlsx) <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".xlsx" required>
                            <div class="form-text">Pastikan file bertipe .xlsx dan kolom baris data diisi lengkap.</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top p-3">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="ti ti-check"></i> Proses Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
async function processSingle(id, action) {
    const confirmed = await confirmAsync('Yakin ingin mengubah status pendaftar ini menjadi ' + action + '?');
    if (!confirmed) return;
    
    const form = document.getElementById('bulk-kelulusan-form');
    const input = document.getElementById('bulk-action-input');
    
    // Uncheck all checkboxes
    document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => cb.checked = false);
    
    // Check only the target one
    const targetCb = document.querySelector('input.row-checkbox[value="' + id + '"]');
    if (targetCb) targetCb.checked = true;
    
    input.value = action;
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('check-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    }

    const btnBulk = document.getElementById('btn-bulk-kelulusan');
    const bulkForm = document.getElementById('bulk-kelulusan-form');
    const bulkActionInput = document.getElementById('bulk-action-input');

    if (btnBulk && bulkForm) {
        btnBulk.addEventListener('click', async function(e) {
            e.preventDefault();
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) {
                alert('Silakan pilih minimal satu pendaftar terlebih dahulu.');
                return;
            }
            const doLulus = await confirmAsync(checked.length + ' pendaftar dipilih. Klik OK untuk Luluskan, Batal untuk Gagalkan.', {
              confirmText: 'Ya, Luluskan',
              buttonClass: 'btn-success',
              icon: 'checklist',
              iconColor: 'text-success',
              title: 'Konfirmasi Kelulusan Massal'
            });
            if (doLulus) {
                bulkActionInput.value = 'Lulus';
            } else {
                bulkActionInput.value = 'Gagal';
            }
            bulkForm.submit();
        });
    }

    // Dynamic Download Template link updates
    const pathSelect = document.getElementById('import_path_select');
    const downloadBtn = document.getElementById('download-template-btn');

    if (pathSelect && downloadBtn) {
        pathSelect.addEventListener('change', function() {
            const pathId = this.value;
            if (pathId) {
                downloadBtn.href = '/pendaftaran/export-template/' + pathId;
                downloadBtn.classList.remove('disabled');
            } else {
                downloadBtn.href = '#';
                downloadBtn.classList.add('disabled');
            }
        });
    }
});
</script>
@endpush
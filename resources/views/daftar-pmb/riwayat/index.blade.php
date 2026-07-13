@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
      <h4 class="fw-bold mb-1">Riwayat Pendaftaran</h4>
      <p class="text-muted mb-0 small">Daftar pendaftaran yang telah Anda lakukan</p>
    </div>
    @if($registrations->isNotEmpty())
      <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
        <i class="ti ti-clipboard-list me-1"></i> Total: {{ $registrations->count() }}
      </span>
    @endif
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($registrations->isEmpty())
    <!-- Empty State -->
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-body text-center py-5">
        <i class="ti ti-inbox text-muted" style="font-size: 3rem;"></i>
        <h5 class="mt-3 text-muted">Belum ada pendaftaran</h5>
        <p class="text-muted small mb-3">Anda belum melakukan pendaftaran melalui jalur PMB.</p>
        <a href="{{ route('daftar-pmb') }}" class="btn btn-primary">
          <i class="ti ti-plus me-1"></i> Daftar Sekarang
        </a>
      </div>
    </div>
  @else
    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 no-sticky-global">
            <thead class="bg-light">
              <tr>
                <th class="ps-4 py-3 fw-semibold">#</th>
                <th class="py-3 fw-semibold">Jalur Pendaftaran</th>
                <th class="py-3 fw-semibold">Pilihan 1</th>
                <th class="py-3 fw-semibold">Pilihan 2</th>
                <th class="py-3 fw-semibold">Tgl Daftar</th>
                <th class="py-3 fw-semibold">Status</th>
                <th class="pe-4 py-3 fw-semibold text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($registrations as $registration)
                @php
                  $statusLabel = null;
                  $badgeBg = null;
                  $badgeText = null;

                  // ── PRIORITY 1: Multi-State Re-Registration Matrix ──
                  // If student has passed selection, show re-registration progression
                  if ($registration->status_kelulusan === 'Lulus') {
                      switch ($registration->status_registrasi_ulang) {
                          case 'belum_registrasi':
                              $statusLabel = 'Belum Registrasi Ulang';
                              $badgeBg = 'bg-warning';
                              $badgeText = 'text-dark';
                              break;
                          case 'menunggu_pembayaran':
                              $statusLabel = 'Menunggu Pembayaran Registrasi Ulang';
                              $badgeBg = 'bg-danger';
                              $badgeText = 'text-white';
                              break;
                          case 'sudah_registrasi_no_tagihan':
                              $statusLabel = 'Sudah Registrasi Ulang';
                              $badgeBg = 'bg-success';
                              $badgeText = 'text-white';
                              break;
                          case 'sudah_registrasi_lunas':
                              $statusLabel = 'Sudah Melakukan Registrasi Ulang';
                              $badgeBg = 'bg-success';
                              $badgeText = 'text-white';
                              break;
                          default:
                              $statusLabel = 'Lulus Seleksi';
                              $badgeBg = 'bg-primary';
                              $badgeText = 'text-white';
                      }
                  } elseif ($registration->status_kelulusan === 'Tidak Lulus') {
                      $statusLabel = 'Tidak Lulus';
                      $badgeBg = 'bg-dark';
                      $badgeText = 'text-white';
                  }

                  // ── PRIORITY 2: UNIFIED STATUS PIPELINE (Fallback) ──
                  if (!$statusLabel) {
                      $pathObj = $registration->registrationPath;
                      $totalRequiredDocs = 0;
                      $totalUploadedDocs = 0;
                      $hasExamBeenTaken = false;
                      $isPaymentLocked = true;

                      // Payment check
                      $paidInvoice = $registration->payments->firstWhere('transaction_status', 'success');
                      if ($paidInvoice) $isPaymentLocked = false;

                      // Document check
                      if ($pathObj && $pathObj->templateBerkas) {
                          $totalRequiredDocs = $pathObj->templateBerkas->syaratDokumens()
                              ->where('status_wajib', true)
                              ->count();
                          $totalUploadedDocs = \App\Models\RegistrationDocument::where('registration_id', $registration->id)->count();
                      }

                      // Exam check
                      if ($pathObj && $pathObj->is_ujian_online) {
                          $hasExamBeenTaken = \App\Models\ExamResult::where('registration_id', $registration->id)
                              ->where('status', 'completed')
                              ->exists();
                      }

                      $isStep3Completed = ($totalRequiredDocs == 0) || ($totalUploadedDocs >= $totalRequiredDocs);

                      // Terminal states (bypass cascade).
                      if ($registration->status === 'rejected') {
                          $badgeBg = 'bg-danger'; $badgeText = 'text-white'; $statusLabel = 'Ditolak';
                      } elseif ($registration->status === 'accepted') {
                          $badgeBg = 'bg-success'; $badgeText = 'text-white'; $statusLabel = 'Diterima';
                      } elseif ($registration->status === 'reviewed') {
                          $badgeBg = 'bg-secondary'; $badgeText = 'text-white'; $statusLabel = 'Direview';
                      } elseif ($registration->status === 'exam_completed') {
                          if ($pathObj && $pathObj->gunakan_wawancara) {
                              if ($registration->status_wawancara === 'menunggu_penjadwalan_wawancara') {
                                  $badgeBg = 'bg-warning'; $badgeText = 'text-dark'; $statusLabel = 'Menunggu Penjadwalan Wawancara';
                              } else {
                                  $badgeBg = 'bg-info'; $badgeText = 'text-dark'; $statusLabel = 'Proses Seleksi Wawancara';
                              }
                          } else {
                              $badgeBg = 'bg-primary'; $badgeText = 'text-white'; $statusLabel = 'Ujian Selesai';
                          }
                      } elseif ($registration->status === 'payment_pending') {
                          $isPaymentLocked = true;
                      }

                      // Cascade for unresolved statuses
                      if (!$statusLabel) {
                          // STEP 1: Financial Gate
                          if ($isPaymentLocked) {
                              $badgeBg = 'bg-danger'; $badgeText = 'text-white'; $statusLabel = 'Menunggu Pembayaran';
                          } else {
                              // STEP 2: Document Phase
                              if ($totalRequiredDocs > 0 && !$isStep3Completed) {
                                  $badgeBg = 'bg-warning'; $badgeText = 'text-dark'; $statusLabel = 'Belum Unggah Berkas';
                              }
                              // STEP 3: Exam Phase
                              elseif ($pathObj && $pathObj->is_ujian_online && !$hasExamBeenTaken) {
                                  $badgeBg = 'bg-info'; $badgeText = 'text-dark'; $statusLabel = 'Menunggu Ujian';
                              }
                              // STEP 4: Final Verification
                              else {
                                  $badgeBg = 'bg-secondary'; $badgeText = 'text-white'; $statusLabel = 'Menunggu Verifikasi Berkas';
                              }
                          }
                      }
                  }
                @endphp
                <tr>
                  <td class="ps-4 py-3 text-muted fw-semibold">{{ $loop->iteration }}</td>
                  <td class="py-3">
                    <div class="d-flex align-items-center gap-2">
                      <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="ti ti-road text-primary"></i>
                      </div>
                      <span class="fw-semibold">{{ $registration->registrationPath?->name ?? '-' }}</span>
                    </div>
                  </td>
                  <td class="py-3">{{ $registration->programStudi1?->nama ?? '-' }}</td>
                  <td class="py-3">{{ $registration->programStudi2?->nama ?? '-' }}</td>
                  <td class="py-3">{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                  <td class="py-3">
                    <span class="badge {{ $badgeBg }} {{ $badgeText }} rounded-pill px-3 py-1 fw-semibold">
                      {{ $statusLabel }}
                    </span>
                  </td>
                  <td class="pe-4 py-3 text-end">
                    <div class="d-flex gap-1 justify-content-end">
                      {{-- Always link to the Registration Stepper Workflow for unified flow --}}
                      <a href="{{ route('daftar-pmb.steps', $registration->registrationPath?->code) }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-arrow-right"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5">
                    <i class="ti ti-inbox text-muted" style="font-size: 3rem;"></i>
                    <h6 class="mt-3 text-muted">Belum ada pendaftaran</h6>
                    <p class="text-muted small mb-3">Anda belum melakukan pendaftaran melalui jalur PMB.</p>
                    <a href="{{ route('daftar-pmb') }}" class="btn btn-primary">
                      <i class="ti ti-plus me-1"></i> Daftar Sekarang
                    </a>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
</main>
@endsection

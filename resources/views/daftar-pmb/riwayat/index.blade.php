@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
      <h4 class="fw-bold mb-1">Riwayat Pendaftaran</h4>
      <p class="text-muted mb-0 small">Daftar pendaftaran yang telah Anda lakukan</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 no-sticky-global">
          <thead class="bg-light">
            <tr>
              <th class="ps-4 py-3 small fw-semibold">#</th>
              <th class="py-3 small fw-semibold">Jalur Pendaftaran</th>
              <th class="py-3 small fw-semibold">Pilihan 1</th>
              <th class="py-3 small fw-semibold">Pilihan 2</th>
              <th class="py-3 small fw-semibold">Tgl Daftar</th>
              <th class="py-3 small fw-semibold">Status</th>
              <th class="pe-4 py-3 small fw-semibold text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($registrations as $registration)
              @php
                $statusBadge = [
                  'submitted' => ['bg-warning', 'text-dark', 'Submitted'],
                  'documents_uploaded' => ['bg-info', 'text-dark', 'Dokumen Diupload'],
                  'payment_pending' => ['bg-warning', 'text-dark', 'Menunggu Pembayaran'],
                  'payment_verified' => ['bg-success', 'text-dark', 'Pembayaran Terverifikasi'],
                  'exam_completed' => ['bg-primary', 'text-white', 'Ujian Selesai'],
                  'reviewed' => ['bg-secondary', 'text-white', 'Direview'],
                  'accepted' => ['bg-success', 'text-white', 'Diterima'],
                  'rejected' => ['bg-danger', 'text-white', 'Ditolak'],
                ];
                $badge = $statusBadge[$registration->status] ?? ['bg-secondary', 'text-white', $registration->status];
              @endphp
              <tr>
                <td class="ps-4 py-3 text-muted small">{{ $loop->iteration }}</td>
                <td class="py-3 small fw-semibold">{{ $registration->registrationPath?->name ?? '-' }}</td>
                <td class="py-3 small">{{ $registration->programStudi1?->nama ?? '-' }}</td>
                <td class="py-3 small">{{ $registration->programStudi2?->nama ?? '-' }}</td>
                <td class="py-3 small">{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                <td class="py-3">
                  <span class="badge {{ $badge[0] }} {{ $badge[1] }} rounded-pill px-3 py-1 small fw-semibold">
                    {{ $badge[2] }}
                  </span>
                </td>
                <td class="pe-4 py-3 text-end">
                  <div class="d-flex gap-1 justify-content-end">
                    <a href="{{ route('daftar-pmb.review', $registration->registrationPath?->code) }}" class="btn btn-sm btn-outline-primary" title="Lihat Ringkasan">
                      <i class="ti ti-eye"></i>
                    </a>
                    @if(in_array($registration->status, ['submitted', 'documents_uploaded']))
                      <a href="{{ route('daftar-pmb.steps', $registration->registrationPath?->code) }}" class="btn btn-sm btn-primary" title="Lanjutkan">
                        <i class="ti ti-arrow-right"></i>
                      </a>
                    @endif
                    @if(in_array($registration->status, ['payment_pending', 'payment_verified']))
                      <a href="{{ route('tagihan.index') }}" class="btn btn-sm btn-warning" title="Tagihan">
                        <i class="ti ti-receipt"></i>
                      </a>
                    @endif
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
</main>
@endsection
@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
      <h4 class="fw-bold mb-1">Pendaftaran PMB</h4>
      <p class="text-muted mb-0 small">Data pendaftaran yang sudah disubmit oleh calon mahasiswa</p>
    </div>
    <div class="d-flex gap-2">
      <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
        <i class="ti ti-users me-1"></i> Total: {{ $registrations->total() }}
      </span>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Filters -->
  <div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-body">
      <form method="GET" action="{{ route('pendaftaran.index') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label small fw-semibold">Cari Nama / NIK / No. HP</label>
          <input type="text" name="search" class="form-control form-control-sm" placeholder="Ketikkan kata kunci..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Jalur Pendaftaran</label>
          <select name="path_id" class="form-select form-select-sm">
            <option value="">Semua Jalur</option>
            @foreach($paths as $path)
              <option value="{{ $path->id }}" {{ request('path_id') == $path->id ? 'selected' : '' }}>{{ $path->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Status</label>
          <select name="status" class="form-select form-select-sm">
            <option value="">Semua Status</option>
            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
            <option value="documents_uploaded" {{ request('status') == 'documents_uploaded' ? 'selected' : '' }}>Dokumen Diupload</option>
            <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
            <option value="payment_verified" {{ request('status') == 'payment_verified' ? 'selected' : '' }}>Pembayaran Terverifikasi</option>
            <option value="exam_completed" {{ request('status') == 'exam_completed' ? 'selected' : '' }}>Ujian Selesai</option>
            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Direview</option>
            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
          </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
          <button type="submit" class="btn btn-primary btn-sm w-100">
            <i class="ti ti-filter me-1"></i> Filter
          </button>
          <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm w-100">
            <i class="ti ti-refresh me-1"></i> Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Data Table -->
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 no-sticky-global">
          <thead class="bg-light">
            <tr>
              <th class="ps-4 py-3 small fw-semibold">#</th>
              <th class="py-3 small fw-semibold">Nama Lengkap</th>
              <th class="py-3 small fw-semibold">Jalur</th>
              <th class="py-3 small fw-semibold">No. HP</th>
              <th class="py-3 small fw-semibold">Tgl Daftar</th>
              <th class="py-3 small fw-semibold">Status</th>
              <th class="pe-4 py-3 small fw-semibold text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($registrations as $registration)
              <tr>
                <td class="ps-4 py-3 text-muted small">{{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}</td>
                <td class="py-3">
                  <div class="d-flex align-items-center gap-2">
                    <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                      <span class="fw-bold text-primary small">{{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}</span>
                    </div>
                    <div>
                      <span class="fw-semibold small">{{ $registration->nama_lengkap }}</span>
                      @if($registration->nik)
                        <br><small class="text-muted">NIK: {{ $registration->nik }}</small>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="py-3">
                  <span class="small">{{ $registration->registrationPath?->name ?? '<span class="text-muted">-</span>' }}</span>
                </td>
                <td class="py-3 small">{{ $registration->no_hp ?? '-' }}</td>
                <td class="py-3 small">{{ $registration->created_at->format('d/m/Y H:i') }}</td>
                <td class="py-3">
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
                  <span class="badge {{ $badge[0] }} {{ $badge[1] }} rounded-pill px-3 py-1 small fw-semibold">
                    {{ $badge[2] }}
                  </span>
                </td>
                <td class="pe-4 py-3 text-end">
                  <a href="{{ route('pendaftaran.show', $registration->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-eye me-1"></i> Detail
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5">
                  <i class="ti ti-inbox text-muted" style="font-size: 3rem;"></i>
                  <h6 class="mt-3 text-muted">Belum ada data pendaftaran</h6>
                  <p class="text-muted small mb-0">Belum ada calon mahasiswa yang melakukan submit pendaftaran.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($registrations->hasPages())
      <div class="card-footer bg-light border-top d-flex justify-content-center py-3">
        {{ $registrations->appends(request()->query())->links() }}
      </div>
    @endif
  </div>
</main>
@endsection
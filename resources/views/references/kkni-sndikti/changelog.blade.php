@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Riwayat Perubahan Referensi</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.index') }}">KKNI SNDikti</a></li>
              <li class="breadcrumb-item active" aria-current="page">Riwayat Perubahan</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
          <a href="{{ route('kkni-sndikti.index') }}" class="btn btn-light border fw-semibold">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
        </div>
      </div>

      <!-- Filters -->
      <form method="GET" action="{{ route('kkni-sndikti.changelog') }}" class="row g-3 mb-4">
        <div class="col-md-3">
          <select name="tabel" class="form-select">
            <option value="">-- Semua Tabel --</option>
            @foreach($tabelList as $key => $label)
              <option value="{{ $key }}" {{ request('tabel') == $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <select name="aksi" class="form-select">
            <option value="">-- Semua Aksi --</option>
            @foreach($aksiList as $aksi)
              <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>{{ $aksi }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Dari tanggal">
        </div>
        <div class="col-md-2">
          <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Sampai tanggal">
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="ti ti-filter me-1"></i> Terapkan</button>
          <a href="{{ route('kkni-sndikti.changelog') }}" class="btn btn-light border">Reset</a>
        </div>
      </form>

      <!-- Changelog Table -->
      <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover table-sm">
          <thead class="table-light">
            <tr>
              <th>Waktu</th>
              <th>Tabel</th>
              <th>Aksi</th>
              <th>Record ID</th>
              <th>Alasan Perubahan</th>
              <th>Regulasi Baru</th>
              <th>Oleh</th>
            </tr>
          </thead>
          <tbody>
            @forelse($changelogs as $log)
            <tr>
              <td class="text-nowrap">{{ $log->changed_at instanceof \Carbon\Carbon ? $log->changed_at->format('d/m/Y H:i:s') : date('d/m/Y H:i:s', strtotime($log->changed_at)) }}</td>
              <td><span class="badge bg-secondary">{{ $log->tabel_terdampak }}</span></td>
              <td>
                @php
                  $aksiColors = ['INSERT' => 'success', 'UPDATE' => 'primary', 'DEACTIVATE' => 'danger', 'ACTIVATE' => 'warning'];
                @endphp
                <span class="badge bg-{{ $aksiColors[$log->aksi] ?? 'secondary' }}">{{ $log->aksi }}</span>
              </td>
              <td><code class="text-muted small">{{ substr($log->id_record_terdampak, 0, 8) }}...</code></td>
              <td class="text-wrap" style="max-width: 300px;">{{ $log->alasan_perubahan }}</td>
              <td>{{ $log->nomor_regulasi_baru ?? '-' }}</td>
              <td>{{ $log->changedBy->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-5">
                <p class="text-muted mb-0">Belum ada riwayat perubahan.</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($changelogs->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $changelogs->links() }}
      </div>
      @endif

      <div class="card-footer bg-white border-top py-3">
        <p class="text-muted mb-0 small">
          Menampilkan {{ $changelogs->count() }} data dari total {{ $changelogs->total() }} riwayat perubahan
        </p>
      </div>
    </div>
  </div>
</main>
@endsection
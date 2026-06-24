@extends('layouts.app')

@section('content')
<main class="p-2">
  <!-- Header Card -->
  <div class="card border-1 mb-6">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold">Aktivasi Tahun Akademik</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Master Data</a></li>
              <li class="breadcrumb-item"><a href="{{ route('tahun-akademik.index') }}">Tahun Akademik</a></li>
              <li class="breadcrumb-item"><a href="{{ route('tahun-akademik.show', $ta->id_tahun_akademik) }}">{{ $ta->kode_ta }}</a></li>
              <li class="breadcrumb-item active" aria-current="page">Aktivasi</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto d-flex gap-2">
          <a href="{{ route('tahun-akademik.edit', $ta->id_tahun_akademik) }}" class="btn btn-light border d-inline-flex align-items-center gap-2">
            <i class="ti ti-arrow-left"></i> Kembali ke Edit
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Ringkasan TA Baru -->
  <div class="card card-lg mb-6">
    <div class="card-header bg-white border-bottom py-3">
      <h5 class="fw-bold mb-0 d-flex align-items-center">
        <i class="ti ti-info-square me-2 fs-3"></i> Ringkasan Tahun Akademik
      </h5>
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-2">
          <label class="text-muted small">Kode TA</label>
          <p class="fw-bold mb-0 fs-5">{{ $ta->kode_ta }}</p>
        </div>
        <div class="col-md-4">
          <label class="text-muted small">Nama TA</label>
          <p class="fw-bold mb-0 fs-5">{{ $ta->nama_ta }}</p>
        </div>
        <div class="col-md-2">
          <label class="text-muted small">Jenis Semester</label>
          <p class="fw-semibold mb-0">
            @php
              $semesterLabels = ['GANJIL' => 'Ganjil', 'GENAP' => 'Genap', 'PENDEK' => 'Pendek'];
              $semesterColors = ['GANJIL' => 'primary', 'GENAP' => 'success', 'PENDEK' => 'info'];
            @endphp
            <span class="badge bg-{{ $semesterColors[$ta->jenis_semester] ?? 'secondary' }}">{{ $semesterLabels[$ta->jenis_semester] ?? $ta->jenis_semester }}</span>
          </p>
        </div>
        <div class="col-md-2">
          <label class="text-muted small">Tanggal Mulai</label>
          <p class="fw-semibold mb-0">{{ $ta->tanggal_mulai->format('d/m/Y') }}</p>
        </div>
        <div class="col-md-2">
          <label class="text-muted small">Tanggal Selesai</label>
          <p class="fw-semibold mb-0">{{ $ta->tanggal_selesai->format('d/m/Y') }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Peringatan TA Aktif -->
  @if($taAktif)
    <div class="alert alert-warning border-0 shadow-sm mb-6">
      <div class="d-flex align-items-center">
        <i class="ti ti-alert-triangle fs-3 me-3 text-warning"></i>
        <div>
          <h6 class="fw-bold mb-1">Perhatian! TA Aktif akan ditutup</h6>
          <p class="mb-0">Tahun Akademik <strong>"{{ $taAktif->nama_ta }}" ({{ $taAktif->kode_ta }})</strong> yang saat ini aktif akan otomatis berubah status menjadi <strong>Selesai</strong> ketika TA baru diaktifkan.</p>
        </div>
      </div>
    </div>
  @else
    <div class="alert alert-info border-0 shadow-sm mb-6">
      <div class="d-flex align-items-center">
        <i class="ti ti-info-circle fs-3 me-3 text-info"></i>
        <div>
          <h6 class="fw-bold mb-1">Tidak ada TA aktif saat ini</h6>
          <p class="mb-0">TA ini akan menjadi semester berjalan pertama dalam sistem.</p>
        </div>
      </div>
    </div>
  @endif

  <!-- Checklist Aktivasi -->
  <div class="card card-lg mb-6">
    <div class="card-header bg-white border-bottom py-3">
      <h5 class="fw-bold mb-0 d-flex align-items-center">
        <i class="ti ti-checklist me-2 fs-3"></i> Checklist Syarat Aktivasi
      </h5>
    </div>
    <div class="card-body p-4">
      @php
        $allPassed = collect($checklist)->every(fn($item) => $item['status'] === true);
      @endphp

      <div class="list-group list-group-flush">
        @foreach($checklist as $index => $item)
          <div class="list-group-item d-flex align-items-start gap-3 px-0 border-bottom">
            <div class="flex-shrink-0">
              @if($item['status'])
                <span class="badge bg-success rounded-circle p-2" style="font-size: 0.8rem;">
                  <i class="ti ti-check"></i>
                </span>
              @else
                <span class="badge bg-danger rounded-circle p-2" style="font-size: 0.8rem;">
                  <i class="ti ti-x"></i>
                </span>
              @endif
            </div>
            <div class="flex-grow-1">
              <h6 class="fw-semibold mb-1">{{ $item['label'] }}</h6>
              <p class="text-muted small mb-0">{{ $item['detail'] }}</p>
            </div>
            <div class="flex-shrink-0">
              @if($item['status'])
                <span class="text-success fw-semibold small">Terpenuhi</span>
              @else
                <span class="text-danger fw-semibold small">Belum Terpenuhi</span>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Status Ringkasan -->
      @php
        $total = count($checklist);
        $passed = collect($checklist)->where('status', true)->count();
        $failed = $total - $passed;
      @endphp
      <div class="mt-4 p-3 bg-light rounded">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <span class="fw-bold">{{ $passed }}/{{ $total }}</span> syarat terpenuhi
            @if($failed > 0)
              <span class="text-danger ms-2">({{ $failed }} belum terpenuhi)</span>
            @endif
          </div>
          <div class="progress" style="width: 200px; height: 10px;">
            <div class="progress-bar bg-{{ $allPassed ? 'success' : 'warning' }}" role="progressbar" style="width: {{ ($passed/$total)*100 }}%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tombol Aktivasi -->
  <div class="d-flex justify-content-end gap-2 mb-6">
    <a href="{{ route('tahun-akademik.edit', $ta->id_tahun_akademik) }}" class="btn btn-light border fw-semibold px-4 py-2">Kembali</a>
    @if($allPassed)
      <form action="{{ route('tahun-akademik.aktivasi', $ta->id_tahun_akademik) }}" method="POST" onsubmit="return confirm('Aktifkan TA ini sebagai semester berjalan?\n\nTA: {{ $ta->nama_ta }} ({{ $ta->kode_ta }})\nSemester: {{ $semesterLabels[$ta->jenis_semester] ?? $ta->jenis_semester }}\nRentang: {{ $ta->tanggal_mulai->format('d/m/Y') }} - {{ $ta->tanggal_selesai->format('d/m/Y') }}\n\n{{ $taAktif ? 'TA "' . $taAktif->nama_ta . '" akan ditutup.' : '' }}')">
        @csrf
        <button type="submit" class="btn btn-success fw-semibold px-5 py-2 d-inline-flex align-items-center gap-2">
          <i class="ti ti-toggle-left fs-4"></i> Aktifkan Semester
        </button>
      </form>
    @else
      <button type="button" class="btn btn-secondary fw-semibold px-5 py-2" disabled title="Penuhi semua syarat terlebih dahulu">
        <i class="ti ti-toggle-left fs-4 me-1"></i> Aktifkan Semester
      </button>
    @endif
  </div>
</main>
@endsection
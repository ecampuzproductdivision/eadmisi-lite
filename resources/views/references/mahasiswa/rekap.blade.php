@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-1 shadow-sm mb-4">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col">
          <h3 class="mb-1 fw-bold">Rekap Mahasiswa</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('mahasiswa.index') }}">Mahasiswa</a></li>
              <li class="breadcrumb-item active">Rekap</li>
            </ol>
          </nav>
        </div>
        <div class="col-auto">
          <a href="{{ route('mahasiswa.index') }}" class="btn btn-light border fw-semibold text-dark"><i class="ti ti-arrow-left me-1"></i> Kembali</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-1 shadow-sm rounded-4 h-100">
        <div class="card-body p-4 d-flex align-items-center gap-3">
          <div class="rounded-3 bg-primary-subtle p-3"><i class="ti ti-users fs-1 text-primary"></i></div>
          <div>
            <div class="text-muted small">Total Mahasiswa</div>
            <div class="fs-2 fw-bold text-primary">{{ number_format($totalMahasiswa) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-1 shadow-sm rounded-4 h-100">
        <div class="card-body p-4 d-flex align-items-center gap-3">
          <div class="rounded-3 bg-success-subtle p-3"><i class="ti ti-user-check fs-1 text-success"></i></div>
          <div>
            <div class="text-muted small">Mahasiswa Aktif</div>
            <div class="fs-2 fw-bold text-success">{{ number_format($aktif) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-1 shadow-sm rounded-4 h-100">
        <div class="card-body p-4 d-flex align-items-center gap-3">
          <div class="rounded-3 bg-warning-subtle p-3"><i class="ti ti-calendar-off fs-1 text-warning"></i></div>
          <div>
            <div class="text-muted small">Mahasiswa Cuti</div>
            <div class="fs-2 fw-bold text-warning">{{ number_format($cuti) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row g-4">
    <div class="col-md-7">
      <div class="card border-1 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 rounded-top-4">
          <h5 class="fw-bold mb-0 text-primary"><i class="ti ti-chart-bar me-2"></i>Distribusi per Angkatan</h5>
        </div>
        <div class="card-body p-4">
          <canvas id="chartAngkatan" height="280"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <div class="card border-1 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 rounded-top-4">
          <h5 class="fw-bold mb-0 text-primary"><i class="ti ti-chart-pie me-2"></i>Distribusi Jalur Masuk</h5>
        </div>
        <div class="card-body p-4">
          <canvas id="chartJalurMasuk" height="280"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Detail Table per Angkatan -->
  <div class="card border-1 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white border-bottom py-3 rounded-top-4">
      <h5 class="fw-bold mb-0 text-primary"><i class="ti ti-table me-2"></i>Detail per Angkatan</h5>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Angkatan</th>
            <th class="text-center">Jumlah</th>
            <th>Proporsi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($angkatan as $a)
          <tr>
            <td class="fw-semibold">{{ $a->tahun_masuk }}</td>
            <td class="text-center fw-bold">{{ $a->total }}</td>
            <td>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-primary" style="width: {{ $totalMahasiswa > 0 ? round($a->total / $totalMahasiswa * 100) : 0 }}%"></div>
              </div>
              <span class="small text-muted">{{ $totalMahasiswa > 0 ? round($a->total / $totalMahasiswa * 100, 1) : 0 }}%</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Angkatan Chart
  const angkatanData = @json($angkatan);
  new Chart(document.getElementById('chartAngkatan'), {
    type: 'bar',
    data: {
      labels: angkatanData.map(d => d.tahun_masuk),
      datasets: [{
        label: 'Jumlah Mahasiswa',
        data: angkatanData.map(d => d.total),
        backgroundColor: 'rgba(59, 130, 246, 0.7)',
        borderColor: 'rgba(59, 130, 246, 1)',
        borderWidth: 1,
        borderRadius: 6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } }
      }
    }
  });

  // Jalur Masuk Chart
  const jalurData = @json($jalurMasuk);
  const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
  new Chart(document.getElementById('chartJalurMasuk'), {
    type: 'doughnut',
    data: {
      labels: jalurData.map(d => d.jalur_masuk),
      datasets: [{
        data: jalurData.map(d => d.total),
        backgroundColor: colors.slice(0, jalurData.length),
        borderWidth: 0,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'bottom' } }
    }
  });
});
</script>
@endsection

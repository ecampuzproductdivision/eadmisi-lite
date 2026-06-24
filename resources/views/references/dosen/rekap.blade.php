@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-1 shadow-sm mb-4">
    <div class="card-body py-4">
      <div class="row align-items-center">
        <div class="col-md-7 col-12">
          <h3 class="mb-1 fw-bold">Rekapitulasi Dosen</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('dosen.index') }}">Dosen</a></li>
              <li class="breadcrumb-item active" aria-current="page">Rekapitulasi</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-5 col-12 text-md-end mt-3 mt-md-0">
          <form action="{{ route('dosen.rekap') }}" method="GET" class="d-flex justify-content-md-end align-items-center gap-2">
            <label class="form-label mb-0 text-muted fw-semibold" style="white-space: nowrap;">Pilih Prodi:</label>
            <select name="prodi" class="form-select w-auto" onchange="this.form.submit()">
              <option value="">-- Semua Prodi --</option>
              @foreach($prodiList as $p)
                <option value="{{ $p->prodiKode }}" {{ $selectedProdiId == $p->prodiKode ? 'selected' : '' }}>
                  {{ $p->prodiNamaResmi }}
                </option>
              @endforeach
            </select>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card rekap-card rekap-card--dosen border-0 shadow-sm h-100 overflow-hidden position-relative">
        <div class="card-body p-4 position-relative" style="z-index: 2;">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 rekap-card__label text-uppercase fw-bold small">Total Dosen Aktif</h6>
            <div class="rekap-card__icon-wrap d-flex align-items-center justify-content-center">
              <i class="ti ti-users fs-3"></i>
            </div>
          </div>
          <h2 class="display-5 fw-bolder mb-0">{{ $activeDosenCount }}</h2>
          <p class="mb-0 rekap-card__sub mt-2 small">Untuk prodi terpilih</p>
        </div>
        <div class="position-absolute top-0 end-0" style="z-index: 1; opacity: 0.08; transform: translate(20%, -20%);">
          <i class="ti ti-users" style="font-size: 150px;"></i>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card rekap-card rekap-card--rasio border-0 shadow-sm h-100 overflow-hidden position-relative">
        <div class="card-body p-4 position-relative" style="z-index: 2;">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 rekap-card__label text-uppercase fw-bold small">Rasio Dosen : Mahasiswa</h6>
            <div class="rekap-card__icon-wrap d-flex align-items-center justify-content-center">
              <i class="ti ti-chart-pie-2 fs-3"></i>
            </div>
          </div>
          <h2 class="display-5 fw-bolder mb-0">1 : {{ $dosenMhsRatio }}</h2>
          <p class="mb-0 rekap-card__sub mt-2 small">Standar Ideal (1:30)</p>
        </div>
        <div class="position-absolute top-0 end-0" style="z-index: 1; opacity: 0.08; transform: translate(20%, -20%);">
          <i class="ti ti-chart-pie-2" style="font-size: 150px;"></i>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card rekap-card rekap-card--serdos border-0 shadow-sm h-100 overflow-hidden position-relative">
        <div class="card-body p-4 position-relative" style="z-index: 2;">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 rekap-card__label text-uppercase fw-bold small">Status Serdos</h6>
            <div class="rekap-card__icon-wrap d-flex align-items-center justify-content-center">
              <i class="ti ti-certificate fs-3"></i>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-end">
            <div>
              <h2 class="display-6 fw-bolder mb-0">{{ $serdosCount['Serdos'] }} <span class="fs-6 rekap-card__sub fw-normal">Tersertifikasi</span></h2>
            </div>
            <div class="text-end">
              <h4 class="mb-0 fw-bold">{{ $serdosCount['Belum'] }}</h4>
              <span class="small rekap-card__sub">Belum</span>
            </div>
          </div>
        </div>
        <div class="position-absolute top-0 end-0" style="z-index: 1; opacity: 0.08; transform: translate(20%, -20%);">
          <i class="ti ti-certificate" style="font-size: 150px;"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row g-4 mb-4">
    <div class="col-lg-5">
      <div class="card border-1 shadow-sm h-100">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-4"><i class="ti ti-school me-2 text-primary"></i>Kualifikasi Pendidikan</h5>
          <div id="chart-pendidikan" style="min-height: 300px;"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card border-1 shadow-sm h-100">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-4"><i class="ti ti-medal me-2 text-primary"></i>Distribusi Jabatan Fungsional</h5>
          <div id="chart-jabatan" style="min-height: 300px;"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Expiring Certifications Alert -->
  @if($expiringCertifications->isNotEmpty())
    <div class="card border-1 shadow-sm mb-4 rekap-alert-card">
      <div class="card-header border-bottom-0 pt-4 pb-2 rekap-alert-card__header">
        <h5 class="fw-bold mb-0 text-warning"><i class="ti ti-alert-triangle me-2"></i>Sertifikasi Akan Berakhir (H-90)</h5>
      </div>
      <div class="card-body pt-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 mt-3">
            <thead class="table-light">
              <tr>
                <th>Nama Dosen</th>
                <th>Sertifikasi</th>
                <th>Penerbit</th>
                <th>Tanggal Berakhir</th>
              </tr>
            </thead>
            <tbody>
              @foreach($expiringCertifications as $cert)
                <tr>
                  <td class="fw-semibold">{{ $cert->gelar_depan ? $cert->gelar_depan . ' ' : '' }}{{ $cert->nama_lengkap }}{{ $cert->gelar_belakang ? ', ' . $cert->gelar_belakang : '' }}</td>
                  <td>{{ $cert->nama_sertifikasi }}<br><small class="text-muted">{{ $cert->no_sertifikat }}</small></td>
                  <td>{{ $cert->lembaga_penerbit }}</td>
                  <td class="text-danger fw-semibold">{{ \Carbon\Carbon::parse($cert->tanggal_berlaku_akhir)->format('d M Y') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
</main>

<style>
/* ── Rekap Summary Cards ── */
.rekap-card {
  border-radius: 12px !important;
  color: #fff;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.rekap-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

/* Card Variants */
.rekap-card--dosen  { background: linear-gradient(135deg, #d82939 0%, #a01525 100%); }
.rekap-card--rasio  { background: linear-gradient(135deg, #0d9488 0%, #065f56 100%); }
.rekap-card--serdos { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); }

.rekap-card__label { color: rgba(255,255,255,0.6); letter-spacing: 0.05em; }
.rekap-card__sub   { color: rgba(255,255,255,0.55); }
.rekap-card__icon-wrap {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  background: rgba(255,255,255,0.2);
  color: #fff;
}

/* ── Alert Card (expiring certifications) ── */
.rekap-alert-card {
  border-left: 4px solid var(--bs-warning) !important;
  border-radius: 8px !important;
}
.rekap-alert-card__header {
  background-color: var(--bs-body-bg);
}

/* ── Chart cards ── */
.card { border-radius: 8px !important; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Detect dark mode for chart theming
  var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
  var labelColor = isDark ? '#94a3b8' : '#64748b';
  var gridColor  = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';

  // Chart Pendidikan (Donut)
  var optionsPendidikan = {
    series: [{{ $qualificationStats['S1'] }}, {{ $qualificationStats['S2'] }}, {{ $qualificationStats['S3'] }}],
    chart: {
      type: 'donut',
      height: 300,
      fontFamily: 'inherit',
      background: 'transparent'
    },
    labels: ['S1', 'S2', 'S3'],
    colors: ['#cbd5e1', '#3b82f6', '#10b981'],
    plotOptions: {
      pie: {
        donut: { size: '65%' }
      }
    },
    dataLabels: { enabled: true },
    stroke: { width: 0 },
    legend: {
      position: 'bottom',
      labels: { colors: labelColor }
    },
    theme: { mode: isDark ? 'dark' : 'light' },
    tooltip: { theme: isDark ? 'dark' : 'light' }
  };

  var chartPendidikan = new ApexCharts(document.querySelector("#chart-pendidikan"), optionsPendidikan);
  chartPendidikan.render();

  // Chart Jabatan (Horizontal Bar)
  var optionsJabatan = {
    series: [{
      name: 'Jumlah Dosen',
      data: [{{ $jabatanStats['Asisten Ahli'] }}, {{ $jabatanStats['Lektor'] }}, {{ $jabatanStats['Lektor Kepala'] }}, {{ $jabatanStats['Guru Besar'] }}]
    }],
    chart: {
      type: 'bar',
      height: 300,
      fontFamily: 'inherit',
      toolbar: { show: false },
      background: 'transparent'
    },
    colors: ['#6366f1'],
    plotOptions: {
      bar: {
        borderRadius: 6,
        horizontal: true,
        barHeight: '55%'
      }
    },
    dataLabels: {
      enabled: true,
      style: { colors: ['#fff'] }
    },
    grid: {
      borderColor: gridColor,
      strokeDashArray: 4
    },
    xaxis: {
      categories: ['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'],
      labels: { style: { colors: labelColor } },
      axisBorder: { show: false },
      axisTicks: { show: false }
    },
    yaxis: {
      labels: { style: { colors: labelColor } }
    },
    theme: { mode: isDark ? 'dark' : 'light' },
    tooltip: { theme: isDark ? 'dark' : 'light' }
  };

  var chartJabatan = new ApexCharts(document.querySelector("#chart-jabatan"), optionsJabatan);
  chartJabatan.render();
});
</script>
@endpush
@endsection

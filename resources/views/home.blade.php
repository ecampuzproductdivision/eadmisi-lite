@extends('layouts.app')

@section('content')
<div class="custom-container">
  <!-- ═══ TOP ROW: 4 STAT CARDS ═══ -->
  <div class="row g-4 mb-6">
    <div class="col-sm-6 col-xl-3">
      <div class="card card-lg border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 52px; height: 52px; background: #667eea15;">
              <i class="ti ti-users fs-3" style="color: #667eea;"></i>
            </div>
            <div>
              <p class="text-muted small mb-1">Total Pendaftar</p>
              <h3 class="fw-bold mb-0">{{ $totalPendaftar }}</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card card-lg border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 52px; height: 52px; background: #05966915;">
              <i class="ti ti-wallet fs-3" style="color: #059669;"></i>
            </div>
            <div>
              <p class="text-muted small mb-1">Keuangan Formulir (Lunas)</p>
              <h3 class="fw-bold mb-0 text-success">{{ $totalLunas }}</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card card-lg border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 52px; height: 52px; background: #d9770615;">
              <i class="ti ti-message-dots fs-3" style="color: #d97706;"></i>
            </div>
            <div>
              <p class="text-muted small mb-1">Antrean Wawancara</p>
              <h3 class="fw-bold mb-0 text-warning">{{ $pendingWawancara }}</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card card-lg border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 52px; height: 52px; background: #dc262615;">
              <i class="ti ti-brand-whatsapp fs-3" style="color: #dc2626;"></i>
            </div>
            <div>
              <p class="text-muted small mb-1">Total Prospek CRM</p>
              <h3 class="fw-bold mb-0 text-danger">{{ $totalCrmLeads }}</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══ MIDDLE ROW: CHARTS ═══ -->
  <div class="row g-6 mb-6">
    <!-- Left: Registration Trend Bar Chart -->
    <div class="col-lg-7">
      <div class="card card-lg border-0 shadow-sm">
        <div class="card-body">
          <div class="mb-2">
            <h6 class="fw-bold mb-0"><i class="ti ti-trending-up me-2 text-primary"></i>Pendaftar Harian (14 Hari Terakhir)</h6>
          </div>
          <div class="chart-wrapper" style="position: relative; width: 100%; height: 160px; max-height: 160px; overflow: hidden;">
            <canvas id="trendChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <!-- Right: Path Distribution Donut Chart -->
    <div class="col-lg-5">
      <div class="card card-lg border-0 shadow-sm">
        <div class="card-body">
          <div class="mb-2">
            <h6 class="fw-bold mb-0"><i class="ti ti-chart-pie me-2 text-info"></i>Distribusi Jalur Pendaftaran</h6>
          </div>
          <div class="chart-wrapper" style="position: relative; width: 100%; height: 200px; max-height: 200px; overflow: hidden;">
            <canvas id="pathChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══ BOTTOM ROW: RECENT TABLES ═══ -->
  <div class="row g-6 mb-6">
    <!-- Left: Pendaftar Terbaru -->
    <div class="col-lg-6">
      <div class="card card-lg border-0 shadow-sm">
        <div class="card-header border-bottom-0 bg-transparent">
          <h5 class="mb-0 fw-bold"><i class="ti ti-user-plus me-2 text-primary"></i>Pendaftar Terbaru</h5>
        </div>
        <div class="table-responsive">
          <table class="table mb-0 text-nowrap table-centered">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Jalur</th>
                <th>Tanggal</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentRegistrations as $reg)
              <tr>
                <td class="fw-semibold">{{ $reg->nama_lengkap ?: ($reg->user->name ?? '-') }}</td>
                <td><span class="badge bg-dark-subtle text-dark px-2">{{ $reg->registrationPath->name ?? '-' }}</span></td>
                <td><small>{{ $reg->created_at->format('d/m/Y H:i') }}</small></td>
                <td>
                  <a href="{{ route('pendaftaran.show', $reg->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Detail">
                    <i class="ti ti-arrow-right"></i>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Belum ada pendaftar.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer border-top-0 bg-transparent">
          <a href="{{ route('pendaftaran.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua Pendaftar</a>
        </div>
      </div>
    </div>
    <!-- Right: Prospek CRM Belum Ditangani -->
    <div class="col-lg-6">
      <div class="card card-lg border-0 shadow-sm">
        <div class="card-header border-bottom-0 bg-transparent">
          <h5 class="mb-0 fw-bold"><i class="ti ti-alert-triangle me-2 text-danger"></i>Prospek CRM Belum Ditangani</h5>
        </div>
        <div class="table-responsive">
          <table class="table mb-0 text-nowrap table-centered">
            <thead>
              <tr>
                <th>Nama</th>
                <th>WhatsApp</th>
                <th>Masuk</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentLeads as $lead)
              <tr>
                <td class="fw-semibold">{{ $lead->nama }}</td>
                <td>
                  <a href="https://wa.me/{{ $lead->whatsapp }}?text=Halo%20{{ urlencode($lead->nama) }},%20saya%20Admin%20PMB..." target="_blank" class="text-success text-decoration-none">
                    <i class="ti ti-brand-whatsapp me-1"></i>{{ $lead->whatsapp }}
                  </a>
                </td>
                <td><small>{{ $lead->created_at->format('d/m/Y H:i') }}</small></td>
                <td>
                  <a href="{{ route('crm-leads.index') }}" class="btn btn-sm btn-outline-danger border-0" title="Kelola CRM">
                    <i class="ti ti-arrow-right"></i>
                  </a>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">Semua lead sudah ditangani.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer border-top-0 bg-transparent">
          <a href="{{ route('crm-leads.index') }}" class="btn btn-sm btn-outline-danger">Kelola CRM Leads</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const baseColor = getComputedStyle(document.documentElement).getPropertyValue('--bs-primary')?.trim() || '#667eea';

  // ── Trend Bar Chart ──
  const trendCtx = document.getElementById('trendChart').getContext('2d');
  new Chart(trendCtx, {
    type: 'bar',
    data: {
      labels: @json($trendLabels),
      datasets: [{
        label: 'Pendaftar',
        data: @json($trendData),
        backgroundColor: 'rgba(102, 126, 234, 0.6)',
        borderColor: 'rgba(102, 126, 234, 1)',
        borderWidth: 1,
        borderRadius: 4,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1, precision: 0 },
          grid: { color: 'rgba(0,0,0,0.05)' }
        },
        x: {
          grid: { display: false }
        }
      }
    }
  });

  // ── Path Distribution Donut Chart ──
  const pathCtx = document.getElementById('pathChart').getContext('2d');
  const colors = [
    '#667eea', '#f63a4c', '#059669', '#d97706', '#7c3aed',
    '#0891b2', '#db2777', '#65a30d', '#ea580c', '#9333ea'
  ];

  new Chart(pathCtx, {
    type: 'doughnut',
    data: {
      labels: @json($pathLabels),
      datasets: [{
        data: @json($pathData),
        backgroundColor: colors.slice(0, @json($pathLabels).length),
        borderWidth: 2,
        borderColor: '#fff',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 12,
            usePointStyle: true,
            font: { size: 11 }
          }
        }
      },
      cutout: '60%',
    }
  });
});
</script>
@endpush
@extends('layouts.app')

@section('content')
@if($isCalonMahasiswa ?? false)
  {{-- ═══════════════════════════════════════════════════ --}}
  {{── STUDENT DASHBOARD: Multi-path application cards  --}}
  {{-- ═══════════════════════════════════════════════════ --}}
  <main class="p-6">
    <div class="row mb-4">
      <div class="col-12">
        <h4 class="fw-bold mb-1">Dashboard Saya</h4>
        <p class="text-muted mb-0">Ringkasan pendaftaran PMB Anda</p>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check fs-4 me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($registrationCards->isEmpty())
      {{-- Empty State: no registrations yet --}}
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body text-center py-5">
          <i class="ti ti-school text-muted" style="font-size: 4rem;"></i>
          <h5 class="mt-3 fw-bold">Selamat Datang Calon Mahasiswa!</h5>
          <p class="text-muted mb-3">Anda belum memilih jalur pendaftaran. Silakan mulai pilih program studi impian Anda sekarang.</p>
          <a href="{{ route('daftar-pmb') }}" class="btn btn-primary btn-lg">
            <i class="ti ti-plus me-2"></i> Mulai Pilih Jalur Pendaftaran Sekarang
          </a>
        </div>
      </div>
    @else
      <div class="row g-4">
        @foreach($registrationCards as $card)
          <div class="col-lg-6 col-xl-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <div>
                    <h5 class="fw-bold mb-1">{{ $card->pathName }}</h5>
                    <small class="text-muted">Didaftarkan {{ $card->createdAt->diffForHumans() }}</small>
                  </div>
                  <span class="badge {{ $card->badgeBg }} {{ $card->badgeText }} rounded-pill px-3 py-1 fw-semibold">
                    {{ $card->statusLabel }}
                  </span>
                </div>

                <hr class="my-2">

                <div class="mb-3">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="ti ti-bookmark text-primary"></i>
                    <span class="fw-medium">Pilihan 1:</span>
                    <span>{{ $card->prodi1 }}</span>
                  </div>
                  @if($card->prodi2 && $card->prodi2 !== '-')
                    <div class="d-flex align-items-center gap-2">
                      <i class="ti ti-bookmark-off text-muted"></i>
                      <span class="fw-medium">Pilihan 2:</span>
                      <span>{{ $card->prodi2 }}</span>
                    </div>
                  @endif
                </div>

                @if($card->actionUrl && $card->actionLabel)
                  <a href="{{ $card->actionUrl }}" class="btn btn-primary w-100">
                    <i class="ti ti-arrow-right me-1"></i> {{ $card->actionLabel }}
                  </a>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </main>

@else
  {{-- ═══════════════════════════════════════════════════ --}}
  {{── ADMIN DASHBOARD: statistics, charts, recent data  --}}
  {{-- ═══════════════════════════════════════════════════ --}}
  @component('components.data-page-layout')
    @slot('breadcrumbs', [
        ['label' => 'Home', 'active' => true],
    ])
    @slot('title', 'Dashboard')
    @slot('description', 'Ringkasan data penerimaan mahasiswa baru.')
    @slot('cards')
        <div class="container-fluid px-0 pt-2">
        <!-- TOP ROW: 4 STAT CARDS -->
        <div class="row g-4 mb-6">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-lg border border-1 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 52px; height: 52px; background: #667eea15;">
                                <i class="ti ti-users fs-3" style="color: #667eea;"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-bold mb-1">Total Pendaftar</h6>
                                <h2 class="fw-bold mb-0">{{ $totalPendaftar }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-lg border border-1 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 52px; height: 52px; background: #05966915;">
                                <i class="ti ti-wallet fs-3" style="color: #059669;"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-bold mb-1">Keuangan Formulir (Lunas)</h6>
                                <h2 class="fw-bold mb-0 text-success">{{ $totalLunas }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-lg border border-1 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 52px; height: 52px; background: #d9770615;">
                                <i class="ti ti-message-dots fs-3" style="color: #d97706;"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-bold mb-1">Antrean Wawancara</h6>
                                <h2 class="fw-bold mb-0 text-warning">{{ $pendingWawancara }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-lg border border-1 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 52px; height: 52px; background: #dc262615;">
                                <i class="ti ti-brand-whatsapp fs-3" style="color: #dc2626;"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-bold mb-1">Total Prospek CRM</h6>
                                <h2 class="fw-bold mb-0 text-danger">{{ $totalCrmLeads }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MIDDLE ROW: CHARTS -->
        <div class="row g-6 mb-6">
            <div class="col-lg-6">
                <div class="card card-lg border border-1 shadow-sm">
                    <div class="card-header border-bottom-1 bg-transparent">
                        <h6 class="fw-bold mb-0"><i class="ti ti-trending-up me-2 text-primary"></i>Pendaftar Harian (14 Hari Terakhir)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper" style="position: relative; width: 100%; height: 200px; max-height: 200px; overflow: hidden;">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-lg border border-1 shadow-sm">
                    <div class="card-header border-bottom-1 bg-transparent">
                        <h6 class="fw-bold mb-0"><i class="ti ti-chart-pie me-2 text-info"></i>Distribusi Jalur Pendaftaran</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper" style="position: relative; width: 100%; height: 200px; max-height: 200px; overflow: hidden;">
                            <canvas id="pathChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTTOM ROW: RECENT TABLES -->
        <div class="row g-6 mb-6">
            <div class="col-lg-6">
                <div class="card card-lg border border-1 shadow-sm">
                    <div class="card-header border-bottom-1 bg-transparent">
                        <h6 class="mb-0 fw-bold"><i class="ti ti-user-plus me-2 text-primary"></i>Pendaftar Terbaru</h6>
                    </div>
                    <div class="card-body">
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
                                        <td><span class="badge bg-secondary-subtle text-dark px-2">{{ $reg->registrationPath->name ?? '-' }}</span></td>
                                        <td>{{ $reg->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('pendaftaran.show', $reg->id) }}" class="btn btn-outline-primary border-0" title="Detail">
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
                    </div>
                    <div class="card-footer border-top-1 bg-transparent">
                        <a href="{{ route('pendaftaran.index') }}" class="btn btn-subtle-secondary">Lihat Semua Pendaftar</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-lg border border-1 shadow-sm">
                    <div class="card-header border-bottom-1 bg-transparent">
                        <h6 class="mb-0 fw-bold"><i class="ti ti-alert-triangle me-2 text-danger"></i>Prospek CRM Belum Ditangani</h6>
                    </div>
                    <div class="card-body">
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
                                        <td>{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('crm-leads.index') }}" class="btn btn-outline-danger border-0" title="Kelola CRM">
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
                    </div>
                    <div class="card-footer border-top-1 bg-transparent">
                        <a href="{{ route('crm-leads.index') }}" class="btn btn-subtle-secondary">Kelola CRM Leads</a>
                    </div>
                </div>
            </div>
        </div>
    @endslot
  @endcomponent
@endif
@endsection

@push('scripts')
@if(!($isCalonMahasiswa ?? false))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
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
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(0,0,0,0.05)' } },
        x: { grid: { display: false } }
      }
    }
  });

  const pathCtx = document.getElementById('pathChart').getContext('2d');
  const colors = ['#667eea','#f63a4c','#059669','#d97706','#7c3aed','#0891b2','#db2777','#65a30d','#ea580c','#9333ea'];
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
        legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true, font: { size: 11 } } }
      },
      cutout: '60%',
    }
  });
});
</script>
@endif
@endpush
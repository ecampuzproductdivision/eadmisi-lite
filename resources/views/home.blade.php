@extends('layouts.app')

@section('content')
@if($isCalonMahasiswa ?? false)
  {{-- ================================================= --}}
  {{-- STUDENT DASHBOARD: Multi-path application cards   --}}
  {{-- ================================================= --}}
  <main class="p-6">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ti ti-circle-check fs-4 me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($hasRegistrations)
      {{-- ========== CONDITION A: Has active registrations ========== --}}
      {{-- Welcome Banner --}}
      <div class="card border-0 shadow-sm rounded-3 mb-5 overflow-hidden" style="background: linear-gradient(135deg, #0d6efd15 0%, #667eea08 100%);">
        <div class="card-body p-5">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <h3 class="fw-bold mb-2">Selamat Datang di Portal eAdmisi!</h3>
              <p class="text-muted mb-0 fs-5">Halo <strong>{{ auth()->user()->name }}</strong>, pantau terus perkembangan status pendaftaran Anda secara berkala di sini.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
              <a href="{{ route('daftar-pmb') }}" class="btn btn-primary btn-lg px-4">
                <i class="ti ti-plus me-2"></i> Pendaftaran Baru
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-9">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
              <h5 class="fw-bold mb-0">Pendaftaran Aktif</h5>
              <p class="text-muted small mb-0">{{ $registrationCount }} jalur pendaftaran</p>
            </div>
            <a href="{{ route('daftar-pmb') }}" class="btn btn-sm btn-outline-primary">
              <i class="ti ti-plus me-1"></i> Tambah Pendaftaran
            </a>
          </div>
          <div class="row g-4">
            @foreach($registrationCards as $card)
              <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                  <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                      <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #0d6efd12;">
                        <i class="ti ti-road text-primary fs-4"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="fw-bold mb-0">{{ $card->pathName }}</h6>
                        <small class="text-muted">{{ $card->createdAt->format('d/m/Y') }}</small>
                      </div>
                    </div>
                    {{-- Status badge on its own line --}}
                    <div class="mb-3">
                      <span class="badge {{ $card->badgeBg }} {{ $card->badgeText }} rounded-pill px-3 py-1 fw-semibold fs-6">
                        {{ $card->statusLabel }}
                      </span>
                      @if(!empty($card->subBadge))
                        {!! $card->subBadge !!}
                      @endif
                    </div>

                    <div class="bg-light rounded-3 p-3 mb-3">
                      <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="ti ti-bookmark-filled text-primary" style="font-size: 0.9rem;"></i>
                        <span class="text-muted small" style="min-width: 70px;">Pilihan 1</span>
                        <span class="fw-medium">{{ $card->prodi1 }}</span>
                      </div>
                      @if($card->prodi2 && $card->prodi2 !== '-')
                        <div class="d-flex align-items-center gap-2">
                          <i class="ti ti-bookmark text-muted" style="font-size: 0.9rem;"></i>
                          <span class="text-muted small" style="min-width: 70px;">Pilihan 2</span>
                          <span class="fw-medium">{{ $card->prodi2 }}</span>
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
        </div>

        {{-- Help Desk Sidebar --}}
        <div class="col-lg-3">
          <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4 text-center">
              <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: #0d6efd12;">
                <i class="ti ti-headset text-primary fs-3"></i>
              </div>
              <h6 class="fw-bold mb-2">Butuh Bantuan?</h6>
              <p class="text-muted small mb-3">Hubungi layanan Helpdesk PMB kami melalui WhatsApp atau kunjungi halaman panduan.</p>
              <div class="d-grid gap-2">
                <a href="https://wa.me/6281234567890?text=Halo%20saya%20butuh%20bantuan%20terkait%20pendaftaran%20PMB" target="_blank" class="btn btn-success btn-sm">
                  <i class="ti ti-brand-whatsapp me-1"></i> WhatsApp Helpdesk
                </a>
                <a href="{{ route('daftar-pmb') }}" class="btn btn-outline-primary btn-sm">
                  <i class="ti ti-book me-1"></i> Panduan Pendaftaran
                </a>
              </div>
            </div>
          </div>
          <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
              <h6 class="fw-bold mb-3"><i class="ti ti-info-circle text-primary me-2"></i>Informasi</h6>
              <ul class="list-unstyled small mb-0">
                <li class="mb-2 d-flex gap-2">
                  <i class="ti ti-circle-check text-success mt-1"></i>
                  <span>Pastikan data pribadi sudah benar sebelum submit</span>
                </li>
                <li class="mb-2 d-flex gap-2">
                  <i class="ti ti-circle-check text-success mt-1"></i>
                  <span>Upload dokumen dalam format PDF/JPG max 5MB</span>
                </li>
                <li class="d-flex gap-2">
                  <i class="ti ti-circle-check text-success mt-1"></i>
                  <span>Pembayaran dapat dilakukan melalui menu Tagihan</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    @else
      {{-- ========== CONDITION B: No registrations — show path selection ========== --}}
      <div class="row justify-content-center mb-6">
        <div class="col-lg-8 text-center">
          <h2 class="fw-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
          <p class="text-muted fs-5 mb-0">Anda belum memiliki pendaftaran aktif. Silakan pilih jalur pendaftaran yang sesuai dengan minat Anda.</p>
        </div>
      </div>

      @php
        $activePaths = \App\Models\RegistrationPath::with('kategori')
            ->byActivePeriode()
            ->where('is_active', true)
            ->orderBy('code')
            ->get();
      @endphp

      <div class="row g-4">
        @forelse($activePaths as $path)
          @php
            $isOpen = $path->registration_start && $path->registration_end
                ? now()->between($path->registration_start, $path->registration_end)
                : true;
          @endphp
          <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
              <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <div>
                    <span class="badge bg-{{ $path->color ?? 'secondary' }}-subtle text-{{ $path->color ?? 'secondary' }} px-3 py-2 mb-2">
                      {{ $path->kategori->nama ?? 'Jalur' }}
                    </span>
                    <h5 class="fw-bold mb-0 mt-2">{{ $path->name }}</h5>
                  </div>
                  @if($isOpen)
                    <span class="badge bg-success-subtle text-success px-3 py-2"><i class="ti ti-circle-check me-1"></i>Buka</span>
                  @else
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><i class="ti ti-clock me-1"></i>Tutup</span>
                  @endif
                </div>
                @if($path->description)
                  <p class="text-muted small mb-3">{{ Str::limit($path->description, 100) }}</p>
                @endif
                <div class="mt-auto">
                  <div class="border-top pt-3 mb-2 small text-muted d-flex justify-content-between">
                    <span><i class="ti ti-calendar me-1"></i>
                      @if($path->registration_start && $path->registration_end)
                        {{ $path->registration_start->format('d/m/Y') }} - {{ $path->registration_end->format('d/m/Y') }}
                      @else
                        Sepanjang Tahun
                      @endif
                    </span>
                    <span class="fw-bold text-danger fs-5">Rp {{ number_format($path->fee, 0, ',', '.') }}</span>
                  </div>
                  <a href="{{ route('daftar-pmb.steps', $path->code) }}" class="btn btn-primary w-100 mt-2">
                    <i class="ti ti-arrow-right me-1"></i> Pilih Jalur Ini
                  </a>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12 text-center py-5">
            <i class="ti ti-road-off text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Belum ada jalur pendaftaran yang tersedia saat ini.</p>
          </div>
        @endforelse
      </div>
    @endif
  </main>

@else
  {{-- ================================================= --}}
  {{-- ADMIN DASHBOARD: statistics, charts, recent data   --}}
  {{-- ================================================= --}}
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
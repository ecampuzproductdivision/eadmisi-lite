@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-1 shadow-sm mb-6">
    <div class="card-body p-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Laporan Kesesuaian CPL — SN-Dikti</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.index') }}">KKNI SNDikti</a></li>
              <li class="breadcrumb-item active" aria-current="page">Laporan Kesesuaian CPL</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
          <a href="{{ route('kkni-sndikti.index') }}" class="btn btn-light border fw-semibold d-inline-flex align-items-center gap-2">
            <i class="ti ti-arrow-left fs-4"></i> Kembali
          </a>
        </div>
      </div>

      <!-- Filter Jenjang -->
      <div class="card border-1 bg-light mb-4">
        <div class="card-body">
          <form method="GET" action="{{ route('kkni-sndikti.laporan') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
              <label class="form-label fw-semibold small">Pilih Jenjang</label>
              <select name="jenjang" class="form-select" onchange="this.form.submit()">
                @foreach($jenjangList as $j)
                  <option value="{{ $j }}" {{ $selectedJenjang == $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-9">
              @if($regulasiSndikti)
                <small class="text-muted">
                  <i class="ti ti-file-text me-1"></i>
                  Berdasarkan regulasi: <span class="fw-semibold">{{ $regulasiSndikti->nomor_peraturan }}</span>
                </small>
              @endif
            </div>
          </form>
        </div>
      </div>

      <!-- Ringkasan Statistik -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body text-center py-4">
              <h6 class="text-muted mb-2">Total Butir Wajib</h6>
              <h2 class="fw-bold mb-0 text-primary">{{ $totalWajib }}</h2>
              <small class="text-muted">butir wajib SN-Dikti untuk {{ $selectedJenjang }}</small>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body text-center py-4">
              <h6 class="text-muted mb-2">Butir Sikap Wajib</h6>
              <h2 class="fw-bold mb-0 text-danger">{{ isset($butirWajib['Sikap']) ? count($butirWajib['Sikap']) : 0 }}</h2>
              <small class="text-muted">wajib diadopsi semua prodi</small>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-1 shadow-sm h-100">
            <div class="card-body text-center py-4">
              <h6 class="text-muted mb-2">Butir KU Wajib</h6>
              <h2 class="fw-bold mb-0 text-success">{{ isset($butirWajib['Keterampilan Umum']) ? count($butirWajib['Keterampilan Umum']) : 0 }}</h2>
              <small class="text-muted">minimal untuk jenjang {{ $selectedJenjang }}</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabel Cross-Check per Kategori -->
      @foreach($butirWajib as $kategori => $butirs)
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
          <h5 class="fw-bold mb-0">
            @php
              $kategoriIcons = [
                'Sikap' => ['icon' => 'ti ti-heart-handshake', 'color' => 'danger'],
                'Pengetahuan' => ['icon' => 'ti ti-brain', 'color' => 'info'],
                'Keterampilan Umum' => ['icon' => 'ti ti-tools', 'color' => 'primary'],
                'Keterampilan Khusus' => ['icon' => 'ti ti-star', 'color' => 'warning'],
              ];
              $kIcon = $kategoriIcons[$kategori] ?? ['icon' => 'ti ti-file', 'color' => 'secondary'];
            @endphp
            <i class="ti {{ $kIcon['icon'] }} text-{{ $kIcon['color'] }} me-2"></i>
            {{ $kategori }}
          </h5>
          <span class="badge bg-{{ $kIcon['color'] }}">{{ count($butirs) }} butir</span>
        </div>
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-hover">
            <thead class="table-light">
              <tr>
                <th width="50" class="text-center">No</th>
                <th width="100">Kode</th>
                <th>Deskripsi Butir</th>
                <th width="100" class="text-center">Wajib</th>
                <th width="100" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($butirs as $butir)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                  <span class="badge bg-{{ $kIcon['color'] }} bg-opacity-10 text-{{ $kIcon['color'] }} fw-bold fs-6 px-2">
                    {{ $butir->kode_butir }}
                  </span>
                </td>
                <td>
                  <span class="text-wrap">{{ $butir->deskripsi }}</span>
                  @if($butir->kata_kunci)
                    <div class="text-muted small mt-1">
                      <i class="ti ti-tag me-1"></i> {{ $butir->kata_kunci }}
                    </div>
                  @endif
                </td>
                <td class="text-center">
                  @if($butir->is_wajib)
                    <span class="badge bg-success">Wajib</span>
                  @else
                    <span class="badge bg-secondary">Opsional</span>
                  @endif
                </td>
                <td class="text-center">
                  <!-- Status placeholder — akan diintegrasikan dg CPL module -->
                  <span class="badge bg-warning text-dark">
                    <i class="ti ti-hourglass me-1"></i> Menunggu
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endforeach

      <!-- Badge Status Keseluruhan -->
      <div class="card border-1 shadow-sm bg-light">
        <div class="card-body p-4 text-center">
          <h5 class="fw-bold mb-2">Status Keseluruhan</h5>
          <p class="text-muted mb-0">
            Berdasarkan data referensi yang tersimpan, terdapat <strong class="text-primary">{{ $totalWajib }}</strong> butir wajib SN-Dikti 
            untuk jenjang <span class="badge bg-dark">{{ $selectedJenjang }}</span>.
          </p>
          <p class="text-muted mt-2 mb-0">
            <small>
              <i class="ti ti-info-circle me-1"></i>
              Status adopsi akan tersedia setelah modul CPL Program Studi terintegrasi.
            </small>
          </p>
          <div class="mt-3">
            <span class="badge bg-warning text-dark fs-6 px-4 py-2">
              <i class="ti ti-hourglass me-1"></i> Menunggu Integrasi CPL
            </span>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>
@endsection
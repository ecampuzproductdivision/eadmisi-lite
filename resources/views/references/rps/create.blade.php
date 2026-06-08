@extends('layouts.app')

@section('content')
<main class="p-4">
  <div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10 col-12">
      <!-- Back button & Breadcrumb -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('rps.index') }}" class="btn btn-light d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
          <i class="ti ti-arrow-left"></i> Kembali ke Daftar
        </a>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rps.index') }}">Penyusunan RPS</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Baru</li>
          </ol>
        </nav>
      </div>

      <!-- Main Creation Form Card -->
      <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header bg-dark text-white p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
          <h4 class="fw-bold mb-1"><i class="ti ti-circle-plus text-warning me-2"></i> Inisialisasi RPS Baru</h4>
          <p class="text-white-50 mb-0">Lengkapi data awal untuk mulai menyusun Rencana Pembelajaran Semester.</p>
        </div>
        <div class="card-body p-4">
          <form action="{{ route('rps.store') }}" method="POST" id="create-rps-form">
            @csrf
            <input type="hidden" name="id_kmk" value="{{ $kmk->id }}">
            <input type="hidden" name="id_tahun_akademik" value="{{ $taActive->id_tahun_akademik }}">

            <!-- Section 1: Ringkasan Mata Kuliah -->
            <div class="p-3 mb-4 bg-light rounded" style="border-left: 4px solid #1e293b;">
              <h5 class="fw-bold text-dark mb-3"><i class="ti ti-book-2 me-1"></i> Data Mata Kuliah & Kurikulum</h5>
              <div class="row g-3">
                <div class="col-md-6 col-12">
                  <div class="small text-muted mb-1">Mata Kuliah</div>
                  <div class="fw-bold text-dark fs-6">{{ $kmk->mataKuliah->mk_nama }}</div>
                  <div class="text-slate-500 font-monospace mt-1" style="font-size: 0.8rem;">Kode: {{ $kmk->mataKuliah->mk_code ?? $kmk->mataKuliah->mk_kode }}</div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="small text-muted mb-1">Bobot SKS</div>
                  <div class="fw-bold text-dark fs-6">{{ $kmk->mataKuliah->sks_total }} SKS</div>
                  <div class="text-slate-500 mt-1" style="font-size: 0.8rem;">(T:{{ $kmk->mataKuliah->sks_teori ?? 0 }}-P:{{ $kmk->mataKuliah->sks_praktikum ?? 0 }})</div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="small text-muted mb-1">Kurikulum</div>
                  <div class="fw-bold text-dark fs-6">{{ $kmk->kurikulum->kurNama }}</div>
                  <div class="text-slate-500 mt-1" style="font-size: 0.8rem;">Smt Anjuran: {{ $kmk->semester_anjuran }}</div>
                </div>
              </div>
            </div>

            <!-- Section 2: Input Parameter -->
            <div class="row g-3">
              <!-- Tahun Akademik (Readonly) -->
              <div class="col-md-6 col-12">
                <label class="form-label fw-bold small text-muted">Tahun Akademik Berlaku</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="ti ti-calendar"></i></span>
                  <input type="text" class="form-control bg-light border-start-0" value="{{ $taActive->nama_ta }}" readonly>
                </div>
              </div>

              <!-- Dosen Koordinator -->
              <div class="col-md-6 col-12">
                <label for="id_dosen_koordinator" class="form-label fw-bold small text-muted">Dosen Koordinator RPS <span class="text-danger">*</span></label>
                <select name="id_dosen_koordinator" id="id_dosen_koordinator" class="form-select @error('id_dosen_koordinator') is-invalid @enderror" required>
                  <option value="">-- Pilih Dosen Koordinator --</option>
                  @foreach($dosenList as $dosen)
                    <option value="{{ $dosen->id_dosen }}" {{ old('id_dosen_koordinator') == $dosen->id_dosen ? 'selected' : '' }}>
                      {{ $dosen->nama_lengkap }} ({{ $dosen->nidn ?? $dosen->nip ?? 'Tanpa NIDN' }})
                    </option>
                  @endforeach
                </select>
                @error('id_dosen_koordinator')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Copy From (Opsi menyalin) -->
              <div class="col-12 mt-4">
                <div class="card border border-warning" style="background: rgba(255, 193, 7, 0.03); border-radius: 12px;">
                  <div class="card-body p-3">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-1 mb-2">
                      <i class="ti ti-copy text-warning"></i>
                      Salin dari RPS Sebelumnya? (Opsional)
                    </h6>
                    <p class="text-muted small mb-3">Jika Anda ingin menggunakan rancangan RPS dari semester sebelumnya sebagai acuan dasar (tim dosen, 16 pertemuan, pustaka, dll.), silakan pilih di bawah. Jika dilewati, RPS akan dibuat kosong / default.</p>
                    <select name="copy_from_rps_id" class="form-select border-warning">
                      <option value="">-- Buat RPS Baru dari Nol (Kosong) --</option>
                      @foreach($previousRpsList as $prevRps)
                        <option value="{{ $prevRps->id_rps }}">
                          RPS TA {{ $prevRps->tahunAkademik->nama_ta }} - Versi {{ $prevRps->versi }} (Status: {{ $prevRps->status }})
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
              <a href="{{ route('rps.index') }}" class="btn btn-light" style="border-radius: 8px;">Batal</a>
              <button type="submit" class="btn btn-dark d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                <i class="ti ti-rocket"></i> Mulai Penyusunan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

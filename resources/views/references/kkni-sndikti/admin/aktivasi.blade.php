@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-1 shadow-sm mb-6">
    <div class="card-body p-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Preview & Aktivasi Regulasi</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.index') }}">KKNI SNDikti</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}">{{ $regulasi->nomor_peraturan }}</a></li>
              <li class="breadcrumb-item active">Aktivasi</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6 col-12 text-md-end mt-3 mt-md-0">
          <a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}" class="btn btn-light border fw-semibold">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Panel
          </a>
        </div>
      </div>

      <!-- Ringkasan Regulasi Baru -->
      <div class="card border-1 shadow-sm mb-4" style="border-left: 4px solid #198754 !important;">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <i class="ti ti-file-text fs-4 text-success me-2"></i>
            <h5 class="fw-bold mb-0">Regulasi Baru: {{ $regulasi->nomor_peraturan }}</h5>
            <span class="badge bg-warning text-dark ms-2">{{ $regulasi->versi }}</span>
          </div>
          <p>{{ $regulasi->judul_peraturan }}</p>
          <small class="text-muted">Status saat ini: <span class="badge bg-warning text-dark">Draft</span> → akan menjadi <span class="badge bg-success">Aktif</span></small>
        </div>
      </div>

      <!-- Perbandingan dengan Regulasi Lama -->
      @if($regulasiLama)
      <div class="card border-1 shadow-sm mb-4" style="border-left: 4px solid #dc3545 !important;">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <i class="ti ti-history fs-4 text-danger me-2"></i>
            <h5 class="fw-bold mb-0">Regulasi Lama: {{ $regulasiLama->nomor_peraturan }}</h5>
            <span class="badge bg-secondary ms-2">{{ $regulasiLama->versi }}</span>
          </div>
          <p class="text-muted">Regulasi ini akan <strong>dinonaktifkan</strong> secara otomatis saat regulasi baru diaktifkan.</p>
        </div>
      </div>
      @endif

      <!-- Preview Diff Butir SN-Dikti -->
      @if(isset($diffButir) && count($diffButir) > 0)
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
          <h5 class="fw-bold mb-0"><i class="ti ti-diff me-2"></i> Perbandingan Butir SN-Dikti</h5>
        </div>
        <div class="card-body p-4">

          @if(isset($diffButir['added']) && $diffButir['added']->count() > 0)
          <div class="mb-4">
            <h6 class="fw-bold text-success">
              <i class="ti ti-plus-circle me-1"></i> Butir Baru ({{ $diffButir['added']->count() }})
            </h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered">
                <thead class="table-success">
                  <tr><th>Kode</th><th>Jenjang</th><th>Kategori</th><th>Deskripsi</th><th>Wajib</th></tr>
                </thead>
                <tbody>
                  @foreach($diffButir['added'] as $butir)
                  <tr>
                    <td><span class="badge bg-success">{{ $butir->kode_butir }}</span></td>
                    <td>{{ $butir->jenjang }}</td>
                    <td>{{ $butir->kategori }}</td>
                    <td class="text-wrap" style="max-width: 400px;">{{ $butir->deskripsi }}</td>
                    <td>@if($butir->is_wajib)<span class="badge bg-success">Wajib</span>@else<span class="badge bg-secondary">Opsional</span>@endif</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          @endif

          @if(isset($diffButir['removed']) && $diffButir['removed']->count() > 0)
          <div class="mb-4">
            <h6 class="fw-bold text-danger">
              <i class="ti ti-minus-circle me-1"></i> Butir Dihapus ({{ $diffButir['removed']->count() }})
            </h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered">
                <thead class="table-danger">
                  <tr><th>Kode</th><th>Jenjang</th><th>Kategori</th><th>Deskripsi</th></tr>
                </thead>
                <tbody>
                  @foreach($diffButir['removed'] as $butir)
                  <tr>
                    <td><span class="badge bg-danger">{{ $butir->kode_butir }}</span></td>
                    <td>{{ $butir->jenjang }}</td>
                    <td>{{ $butir->kategori }}</td>
                    <td class="text-wrap" style="max-width: 400px;">{{ $butir->deskripsi }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          @endif

          @if(isset($diffButir['modified']) && $diffButir['modified']->count() > 0)
          <div class="mb-4">
            <h6 class="fw-bold text-warning">
              <i class="ti ti-edit-circle me-1"></i> Butir Diubah ({{ $diffButir['modified']->count() }})
            </h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered">
                <thead class="table-warning">
                  <tr><th>Kode</th><th>Jenjang</th><th>Deskripsi Lama</th><th>Deskripsi Baru</th></tr>
                </thead>
                <tbody>
                  @foreach($diffButir['modified'] as $diff)
                  <tr>
                    <td><span class="badge bg-warning text-dark">{{ $diff['baru']->kode_butir }}</span></td>
                    <td>{{ $diff['baru']->jenjang }}</td>
                    <td class="text-wrap text-danger" style="max-width: 350px;"><small>{{ $diff['lama']->deskripsi }}</small></td>
                    <td class="text-wrap text-success" style="max-width: 350px;"><small>{{ $diff['baru']->deskripsi }}</small></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          @endif

        </div>
      </div>
      @endif

      <!-- Dampak Prodi -->
      <div class="card border-1 shadow-sm mb-4">
        <div class="card-body">
          <h5 class="fw-bold mb-3"><i class="ti ti-users me-2"></i> Daftar Prodi Terdampak</h5>
          <p>{{ $dampakProdi['pesan'] }}</p>
          @if(count($dampakProdi['prodi_perlu_update']) > 0)
          <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="table table-sm table-bordered mb-0">
              <thead class="table-light">
                <tr><th>No</th><th>Kode</th><th>Nama Prodi</th><th>Jenjang</th></tr>
              </thead>
              <tbody>
                @foreach($dampakProdi['prodi_perlu_update'] as $prodi)
                <tr><td>{{ $loop->iteration }}</td><td>{{ $prodi->kode_prodi }}</td><td>{{ $prodi->nama_prodi }}</td><td><span class="badge bg-dark">{{ $prodi->jenjang }}</span></td></tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @else
          <p class="text-muted">Tidak ada program studi yang terdampak.</p>
          @endif
        </div>
      </div>

      <!-- Form Aktivasi -->
      <div class="card border-1 shadow-sm" style="border-left: 4px solid #198754 !important;">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3 text-success">
            <i class="ti ti-toggle-left me-2"></i> Konfirmasi Aktivasi Regulasi
          </h5>
          <form action="{{ route('kkni-sndikti.admin.aktivasi', $regulasi->id_regulasi) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold">Alasan Aktivasi <span class="text-danger">*</span></label>
              <textarea name="alasan_aktivasi" class="form-control @error('alasan_aktivasi') is-invalid @enderror" rows="3" required minlength="10" placeholder="Jelaskan alasan aktivasi regulasi baru ini..."></textarea>
              <small class="text-muted">Alasan ini akan tercatat di riwayat perubahan (changelog).</small>
              @error('alasan_aktivasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="alert alert-warning border-0 d-flex align-items-center">
              <i class="ti ti-alert-triangle fs-4 me-2 text-warning"></i>
              <div>
                <strong>Perhatian!</strong> 
                @if($regulasiLama)
                  Regulasi <strong>{{ $regulasiLama->nomor_peraturan }}</strong> akan dinonaktifkan dan tidak dapat digunakan lagi untuk kurikulum baru. 
                @endif
                Tindakan ini akan dicatat di riwayat perubahan sistem.
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-success px-5" onclick="return confirmAction(event, 'Yakin akan mengaktifkan regulasi ini? Tindakan ini tidak dapat dibatalkan.')">
                <i class="ti ti-toggle-left me-1"></i> Aktifkan Regulasi
              </button>
              <a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}" class="btn btn-light border fw-semibold px-4">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

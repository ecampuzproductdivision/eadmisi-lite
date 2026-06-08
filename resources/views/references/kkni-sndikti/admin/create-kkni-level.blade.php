@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="card border-0 shadow-sm mb-6">
    <div class="card-body p-4">
      <div class="row mb-4 align-items-center">
        <div class="col-md-6 col-12">
          <h3 class="mb-1 mt-2 fw-bold">Tambah Level KKNI</h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Data Master</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.index') }}">KKNI SNDikti</a></li>
              <li class="breadcrumb-item"><a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}">{{ $regulasi->nomor_peraturan }}</a></li>
              <li class="breadcrumb-item active">Tambah Level KKNI</li>
            </ol>
          </nav>
        </div>
      </div>

      <div class="alert alert-info border-0 d-flex align-items-center mb-4">
        <i class="ti ti-certificate-2 fs-4 me-2 text-info"></i>
        <div>Regulasi: <strong>{{ $regulasi->nomor_peraturan }}</strong> ({{ $regulasi->versi }}) — Status: <span class="badge bg-warning text-dark">Draft</span></div>
      </div>

      <form action="{{ route('kkni-sndikti.admin.kkni-level.store', $regulasi->id_regulasi) }}" method="POST" class="row g-4">
        @csrf

        <div class="col-md-3">
          <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
          <select name="level" class="form-select @error('level') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            @for($i = 1; $i <= 9; $i++)
              <option value="{{ $i }}" {{ old('level') == $i ? 'selected' : '' }}>Level {{ $i }}</option>
            @endfor
          </select>
          @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Urutan Tampil <span class="text-danger">*</span></label>
          <input type="number" name="urutan_tampil" class="form-control @error('urutan_tampil') is-invalid @enderror" value="{{ old('urutan_tampil', 1) }}" min="1" max="9" required>
          @error('urutan_tampil') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Nama Level <span class="text-danger">*</span></label>
          <input type="text" name="nama_level" class="form-control @error('nama_level') is-invalid @enderror" value="{{ old('nama_level') }}" placeholder="Misal: KKNI Level 6" required maxlength="50">
          @error('nama_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Jenjang Pendidikan</label>
          <input type="text" name="jenjang_pendidikan" class="form-control @error('jenjang_pendidikan') is-invalid @enderror" value="{{ old('jenjang_pendidikan') }}" placeholder="Misal: S1,D4 (pisahkan dengan koma)">
          @error('jenjang_pendidikan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Deskripsi Umum <span class="text-danger">*</span></label>
          <textarea name="deskripsi_umum" class="form-control @error('deskripsi_umum') is-invalid @enderror" rows="2" required>{{ old('deskripsi_umum') }}</textarea>
          @error('deskripsi_umum') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
          <h6 class="fw-bold mb-3">Deskriptor per Area Kompetensi</h6>
          <div class="row g-3">
            @foreach($areaList as $index => $area)
            @php
              $fieldMap = [
                'Sikap & Tata Nilai' => 'deskriptor_sikap',
                'Kemampuan Kerja' => 'deskriptor_kk',
                'Pengetahuan' => 'deskriptor_p',
                'Tanggung Jawab & Hak' => 'deskriptor_tr',
              ];
              $iconMap = [
                'Sikap & Tata Nilai' => 'ti ti-heart-handshake',
                'Kemampuan Kerja' => 'ti ti-tools',
                'Pengetahuan' => 'ti ti-brain',
                'Tanggung Jawab & Hak' => 'ti ti-shield-check',
              ];
              $colorMap = [
                'Sikap & Tata Nilai' => 'danger',
                'Kemampuan Kerja' => 'primary',
                'Pengetahuan' => 'success',
                'Tanggung Jawab & Hak' => 'warning',
              ];
              $field = $fieldMap[$area];
            @endphp
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="ti {{ $iconMap[$area] }} text-{{ $colorMap[$area] }} me-1"></i>
                {{ $area }}
              </label>
              <textarea name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" rows="2" placeholder="Deskripsi {{ $area }}...">{{ old($field) }}</textarea>
              @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            @endforeach
          </div>
        </div>

        <div class="col-md-12">
          <label class="form-label fw-semibold">Alasan Perubahan <span class="text-danger">*</span></label>
          <textarea name="alasan_perubahan" class="form-control @error('alasan_perubahan') is-invalid @enderror" rows="2" required minlength="10" placeholder="Jelaskan alasan penambahan level KKNI ini...">{{ old('alasan_perubahan') }}</textarea>
          <small class="text-muted">Alasan ini akan tercatat di riwayat perubahan (changelog).</small>
          @error('alasan_perubahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12 border-top pt-4">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark px-5">
              <i class="ti ti-device-floppy me-1"></i> Simpan Level KKNI
            </button>
            <a href="{{ route('kkni-sndikti.admin.regulasi.edit', $regulasi->id_regulasi) }}" class="btn btn-light border fw-semibold px-4">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>
@endsection
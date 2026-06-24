@extends('layouts.app')

@section('content')
<main class="p-2">
  <div class="row mb-3 align-items-center">
    <div class="col">
      <h3 class="mb-1 fw-bold text-dark">Ubah Negara</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Master Data</a></li>
          <li class="breadcrumb-item"><a href="{{ route('country.index') }}">Negara</a></li>
          <li class="breadcrumb-item active" aria-current="page">Ubah</li>
        </ol>
      </nav>
    </div>
    <div class="col-auto">
      <a href="{{ route('country.index') }}" class="btn btn-light border fw-semibold text-dark">
        <i class="ti ti-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-8 col-12">
      <div class="card border-1 shadow-sm">
        <div class="card-body p-5">
          <form action="{{ route('country.update', $country->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            @if($errors->any())
              <div class="alert alert-danger mb-4 py-2 small border-start border-danger border-4">
                <ul class="mb-0 ps-3">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="row g-4">
              <!-- Nama Negara -->
              <div class="col-md-12 col-12">
                <label for="name" class="form-label fw-semibold">Nama Negara <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Indonesia, Malaysia" value="{{ old('name', $country->name) }}" required>
                <div class="invalid-feedback">Nama negara wajib diisi.</div>
              </div>

              <!-- Kode ISO 2 -->
              <div class="col-md-6 col-12">
                <label for="iso2" class="form-label fw-semibold">Kode Negara (ISO 2) <span class="text-danger">*</span></label>
                <input type="text" name="iso2" id="iso2" class="form-control" maxlength="2" placeholder="Contoh: ID, MY" value="{{ old('iso2', $country->iso2) }}" style="text-transform: uppercase;" required>
                <small class="text-muted">Maksimal 2 karakter huruf kapital.</small>
                <div class="invalid-feedback">Kode ISO 2 wajib diisi dan tepat 2 karakter.</div>
              </div>

              <!-- Kode ISO 3 -->
              <div class="col-md-6 col-12">
                <label for="iso3" class="form-label fw-semibold">Kode Negara (ISO 3) <span class="text-danger">*</span></label>
                <input type="text" name="iso3" id="iso3" class="form-control" maxlength="3" placeholder="Contoh: IDN, MYS" value="{{ old('iso3', $country->iso3) }}" style="text-transform: uppercase;" required>
                <small class="text-muted">Maksimal 3 karakter huruf kapital.</small>
                <div class="invalid-feedback">Kode ISO 3 wajib diisi dan tepat 3 karakter.</div>
              </div>

              <!-- Kode Telepon -->
              <div class="col-md-6 col-12">
                <label for="phone_code" class="form-label fw-semibold">Kode Telepon</label>
                <input type="text" name="phone_code" id="phone_code" class="form-control" placeholder="Contoh: +62, +60" value="{{ old('phone_code', $country->phone_code) }}">
                <small class="text-muted">Format diawali simbol + diikuti angka kode area.</small>
              </div>

              <!-- Status -->
              <div class="col-md-6 col-12">
                <label class="form-label fw-semibold d-block">Status</label>
                <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="status_toggle" id="status_toggle" {{ $country->status == 'active' ? 'checked' : '' }} role="switch">
                  <label class="form-check-label fw-semibold {{ $country->status == 'active' ? 'text-success' : 'text-danger' }} ms-1" for="status_toggle" id="status_label">
                    {{ $country->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                  </label>
                  <input type="hidden" name="status" id="status_value" value="{{ $country->status }}">
                </div>
              </div>

              <!-- Tombol Simpan / Batal -->
              <div class="col-12 d-flex gap-2 justify-content-end mt-5">
                <a href="{{ route('country.index') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary px-4" style="background-color: #f63a4c; border-color: #f63a4c;">Simpan</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Input transformers (force uppercase on code elements)
  const iso2 = document.getElementById('iso2');
  const iso3 = document.getElementById('iso3');
  
  iso2.addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '');
  });
  
  iso3.addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '');
  });

  // Switch labels toggler
  const toggle = document.getElementById('status_toggle');
  const label = document.getElementById('status_label');
  const hiddenInput = document.getElementById('status_value');
  
  toggle.addEventListener('change', function() {
    if (this.checked) {
      label.textContent = 'Aktif';
      label.className = 'form-check-label fw-semibold text-success ms-1';
      hiddenInput.value = 'active';
    } else {
      label.textContent = 'Nonaktif';
      label.className = 'form-check-label fw-semibold text-danger ms-1';
      hiddenInput.value = 'inactive';
    }
  });
});
</script>
@endsection

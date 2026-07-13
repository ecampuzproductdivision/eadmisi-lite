@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6">
    <div class="col-12">
      <a href="{{ route('registration-paths.index') }}" class="btn btn-soft-secondary mb-3 d-inline-flex align-items-center gap-2">
        <i class="ti ti-arrow-left fs-4"></i> Kembali
      </a>
      <h1 class="mb-1 fw-bold">Detail Jalur Pendaftaran</h1>
      <p class="mb-0 text-muted">Informasi lengkap jalur pendaftaran: {{ $registrationPath->name }}.</p>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-1 shadow-sm">
        <div class="card-body p-5">
          <div class="d-flex align-items-center gap-3 mb-4">
            <div>
              <h3 class="fw-bold mb-1">{{ $registrationPath->name }}</h3>
              <div class="d-flex gap-2 align-items-center mt-2">
                <span class="badge bg-{{ $registrationPath->color ?? 'secondary' }}-subtle text-{{ $registrationPath->color ?? 'secondary' }} px-3 py-2 fs-6">
                  {{ $registrationPath->code }}
                </span>
                @if($registrationPath->kategori)
                  <span class="badge bg-dark-subtle text-dark px-3 py-2 fs-6">
                    <i class="ti ti-tag me-1"></i> {{ $registrationPath->kategori->nama }}
                  </span>
                @endif
              </div>
            </div>
          </div>

          <div class="row g-4 mt-2">
            <div class="col-md-6">
              <div class="border rounded p-3">
                <small class="text-muted text-uppercase fw-semibold">Biaya Pendaftaran</small>
                <h4 class="fw-bold mt-1 mb-0">Rp {{ number_format($registrationPath->fee, 0, ',', '.') }}</h4>
              </div>
            </div>
            <div class="col-md-3">
              <div class="border rounded p-3">
                <small class="text-muted text-uppercase fw-semibold">Kuota</small>
                <h4 class="fw-bold mt-1 mb-0">{{ $registrationPath->quota ?? '∞' }}</h4>
              </div>
            </div>
            <div class="col-md-3">
              <div class="border rounded p-3">
                <small class="text-muted text-uppercase fw-semibold">Status</small>
                <div class="mt-1">
                  @if($registrationPath->is_active)
                    <span class="badge bg-success-subtle text-success px-3 py-2 fs-6">Aktif</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 fs-6">Nonaktif</span>
                  @endif
                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="border rounded p-3">
                <small class="text-muted text-uppercase fw-semibold">Periode Pendaftaran</small>
                <p class="fw-semibold mt-1 mb-0">
                  @if($registrationPath->registration_start && $registrationPath->registration_end)
                    {{ $registrationPath->registration_start->format('d F Y') }} — {{ $registrationPath->registration_end->format('d F Y') }}
                  @else
                    <span class="text-muted">Tidak ditentukan (selalu terbuka)</span>
                  @endif
                </p>
              </div>
            </div>

            @if($registrationPath->description)
              <div class="col-12">
                <div class="border rounded p-3">
                  <small class="text-muted text-uppercase fw-semibold">Deskripsi</small>
                  <p class="mt-1 mb-0">{{ $registrationPath->description }}</p>
                </div>
              </div>
            @endif

            <div class="col-md-6">
              <div class="border rounded p-3">
                <small class="text-muted text-uppercase fw-semibold">Jumlah Pilihan Program Studi</small>
                <h4 class="fw-bold mt-1 mb-0">{{ $registrationPath->jumlah_pilihan_prodi ?? 1 }} Pilihan</h4>
              </div>
            </div>
            <div class="col-md-6">
              <div class="border rounded p-3">
                <small class="text-muted text-uppercase fw-semibold">Program Studi Ditawarkan</small>
                @if($registrationPath->relationLoaded('programStudis') && $registrationPath->programStudis->isNotEmpty())
                  <div class="mt-2 d-flex flex-wrap gap-2">
                    @foreach($registrationPath->programStudis as $prodi)
                      <span class="badge bg-primary-subtle text-primary px-3 py-2 fs-6">
                        <i class="ti ti-building-community me-1"></i> {{ $prodi->nama_prodi ?: $prodi->nama }}
                        @if($prodi->jenjang_akademik ?? $prodi->jenjang)
                          <span class="opacity-75">({{ $prodi->jenjang_akademik ?? $prodi->jenjang }})</span>
                        @endif
                      </span>
                    @endforeach
                  </div>
                @else
                  <p class="text-muted mt-1 mb-0">Belum ada program studi yang ditawarkan.</p>
                @endif
              </div>
            </div>

            <div class="col-12">
              <div class="border rounded p-3">
                <small class="text-muted text-uppercase fw-semibold">Informasi Sistem</small>
                <div class="row mt-2">
                  <div class="col-md-6">
                    <small class="text-muted">Dibuat:</small>
                    <p class="mb-0">{{ $registrationPath->created_at->format('d/m/Y H:i') }}</p>
                  </div>
                  <div class="col-md-6">
                    <small class="text-muted">Diperbarui:</small>
                    <p class="mb-0">{{ $registrationPath->updated_at->format('d/m/Y H:i') }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card border-1 shadow-sm">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3">Aksi</h5>
          <div class="d-flex flex-column gap-2">
            <a href="{{ route('registration-paths.edit', $registrationPath) }}" class="btn btn-warning d-inline-flex align-items-center gap-2">
              <i class="ti ti-edit fs-4"></i> Edit Jalur
            </a>
            <form action="{{ route('registration-paths.destroy', $registrationPath) }}" method="POST" onsubmit="return confirmSubmit(event, 'Hapus jalur {{ $registrationPath->name }}?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-2 justify-content-center">
                <i class="ti ti-trash fs-4"></i> Hapus Jalur
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

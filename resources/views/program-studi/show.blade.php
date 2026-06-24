@extends('layouts.app')

@section('content')
<main class="p-6">
  <div class="row mb-6">
    <div class="col-12">
      <a href="{{ route('program-studi.index') }}" class="btn btn-soft-secondary mb-3 d-inline-flex align-items-center gap-2">
        <i class="ti ti-arrow-left fs-4"></i> Kembali
      </a>
      <h1 class="mb-1 fw-bold">Detail Program Studi</h1>
      <p class="mb-0 text-muted">Informasi lengkap program studi: {{ $programStudi->nama_prodi }}.</p>
    </div>
  </div>

  <div class="card border-1 shadow-sm">
    <div class="card-body p-5">
      <div class="row g-4">
        <div class="col-md-4">
          <label class="form-label fw-semibold text-muted">Kode Program Studi</label>
          <p class="fw-bold fs-5">{{ $programStudi->kode_prodi }}</p>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold text-muted">Label NIM</label>
          <p class="fw-bold">{{ $programStudi->label_nim }}</p>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold text-muted">Label Prodi No Pendaftaran</label>
          <p class="fw-bold">{{ $programStudi->label_prodi_no_pendaftaran ?? '-' }}</p>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold text-muted">Nama Program Studi</label>
          <p class="fw-bold">{{ $programStudi->nama_prodi }}</p>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold text-muted">Jurusan</label>
          <p class="fw-bold">{{ $programStudi->jurusan }}</p>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold text-muted">Jenjang Akademik</label>
          <p class="fw-bold">{{ $programStudi->jenjang_akademik }}</p>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold text-muted">Program</label>
          <p class="fw-bold">{{ $programStudi->program }}</p>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold text-muted">Kelompok</label>
          <p class="fw-bold">{{ $programStudi->kelompok }}</p>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold text-muted">Status Aktif</label>
          <p>
            @if($programStudi->status_aktif)
              <span class="badge bg-success-subtle text-success fs-6"><i class="ti ti-circle-check-filled me-1"></i> Aktif</span>
            @else
              <span class="badge bg-danger-subtle text-danger fs-6"><i class="ti ti-circle-x-filled me-1"></i> Non Aktif</span>
            @endif
          </p>
        </div>
      </div>

      <div class="mt-5 d-flex gap-3">
        <a href="{{ route('program-studi.edit', $programStudi) }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-5">
          <i class="ti ti-edit fs-4"></i> Edit
        </a>
        <a href="{{ route('program-studi.index') }}" class="btn btn-soft-secondary d-inline-flex align-items-center gap-2">
          Kembali
        </a>
      </div>
    </div>
  </div>
</main>
@endsection
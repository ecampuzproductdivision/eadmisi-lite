@extends('layouts.app')

@section('content')
@php
    $syaratBerkas = collect();
    if ($path && $path->templateBerkas && $path->templateBerkas->syaratDokumens) {
        $syaratBerkas = $path->templateBerkas->syaratDokumens;
    }
    $hasDocuments = $syaratBerkas->isNotEmpty();
@endphp

<main class="p-6">
  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12">
      <h2 class="fw-bold mb-1">Unggah Persyaratan</h2>
      <p class="text-muted mb-0">Upload dokumen yang diperlukan untuk proses seleksi. Pastikan dokumen dalam kondisi jelas dan sesuai ketentuan.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check fs-4 me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-triangle fs-4 me-2"></i>
      <strong>Terjadi kesalahan:</strong>
      <ul class="mb-0 mt-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <form action="{{ route('daftar-pmb.document.store', $path?->code) }}" method="POST" enctype="multipart/form-data" id="documentForm">
    @csrf

    <div class="row">
      <div class="col-lg-8">

        @if(!$hasDocuments)
        <div class="alert alert-info d-flex align-items-center gap-3 mb-4 border-0">
          <i class="ti ti-info-circle fs-4"></i>
          <div>
            <h6 class="fw-bold mb-1">Tidak ada persyaratan dokumen</h6>
            <p class="mb-0 text-muted small">Jalur pendaftaran ini tidak memerlukan unggahan dokumen tambahan. Silakan lanjut ke tahap berikutnya.</p>
          </div>
        </div>
        @else
        <!-- Document Upload Grid - Dynamic dari BO -->
        <div class="row g-4 mb-4">
          @foreach($syaratBerkas as $berkas)
          <div class="col-md-6">
            <div class="card card-lg h-100 document-card">
              <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="document-icon bg-primary-subtle">
                    <i class="ti ti-file-text text-primary"></i>
                  </div>
                  @if(isset($existingDocuments[$berkas->nama_dokumen]))
                    <span class="badge bg-success-subtle text-success px-2 py-1">
                      <i class="ti ti-check me-1"></i> Terunggah
                    </span>
                  @endif
                </div>
                <h6 class="fw-bold mb-1">{{ $berkas->nama_dokumen }}</h6>
                <p class="text-muted mb-2" style="font-size: 0.82rem;">
                  Silakan unggah scan {{ $berkas->nama_dokumen }} asli sesuai dengan ketentuan.
                </p>
                <small class="text-muted d-block mb-3">
                  <i class="ti ti-info-circle me-1"></i>
                  Max: {{ $berkas->max_size ?? 2048 }} KB,
                  Format: {{ $berkas->ekstensi_diizinkan ?? 'PDF/JPG/PNG' }}
                  @if($berkas->status_wajib)
                    <span class="text-danger fw-bold"> *Wajib</span>
                  @endif
                </small>
                
                <div class="upload-zone" id="berkasZone_{{ $berkas->id }}">
                  <input type="file" name="berkas[{{ $berkas->id }}]" id="berkasInput_{{ $berkas->id }}" class="d-none"
                    accept="{{ $berkas->ekstensi_diizinkan ? '.' . str_replace(',', ',.', $berkas->ekstensi_diizinkan) : '.pdf,.jpg,.jpeg,.png' }}"
                    {{ $berkas->status_wajib ? 'required' : '' }}>
                  <div class="upload-placeholder" id="berkasPlaceholder_{{ $berkas->id }}">
                    <i class="ti ti-cloud-upload text-primary"></i>
                    <span>Pilih File</span>
                  </div>
                  <div class="upload-preview d-none" id="berkasPreview_{{ $berkas->id }}">
                    <i class="ti ti-file text-success"></i>
                    <span class="upload-filename" id="berkasName_{{ $berkas->id }}"></span>
                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeBerkas({{ $berkas->id }})">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @endif

        <!-- Important Note -->
        <div class="alert alert-info d-flex align-items-start gap-3 mb-4 border-0" style="background: #f0f7ff;">
          <div class="rounded-circle bg-info d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
            <i class="ti ti-info-circle text-white"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Penting</h6>
            <p class="mb-0 text-muted" style="font-size: 0.85rem;">Sistem kami hanya menerima dokumen dengan kualitas scan yang baik (tidak buram). Pastikan semua sudut dokumen terlihat dalam file yang diunggah.</p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-3 mb-4">
          <a href="{{ route('daftar-pmb.steps', $path?->code) }}" class="btn btn-outline-secondary px-4">
            <i class="ti ti-arrow-left me-1"></i> Kembali
          </a>
          <button type="submit" class="btn btn-primary fw-semibold px-4" {{ !$hasDocuments ? 'disabled' : '' }}>
            Unggah & Lanjutkan <i class="ti ti-arrow-right ms-1"></i>
          </button>
        </div>

      </div>

      <!-- Help Sidebar -->
      <div class="col-lg-4">
        <div class="sticky-sidebar">
          <!-- Card Bantuan -->
          <div class="card card-lg mb-3" style="background: linear-gradient(135deg, #e8f0fe 0%, #f0f4ff 100%);">
            <div class="card-body p-4">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px;">
                  <i class="ti ti-headset text-white fs-5"></i>
                </div>
                <div>
                  <h6 class="fw-bold mb-0">Butuh Bantuan?</h6>
                  <small class="text-muted">Tim helpdesk kami siap membantu</small>
                </div>
              </div>
              <p class="text-muted mb-3" style="font-size: 0.85rem;">Jika mengalami kendala dalam proses upload dokumen, jangan ragu untuk menghubungi kami.</p>
              <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2">
                <i class="ti ti-brand-whatsapp"></i> Chat Admin WhatsApp
              </a>
            </div>
          </div>

          <!-- Card Tips Upload -->
          <div class="card card-lg">
            <div class="card-body p-4">
              <h6 class="fw-bold mb-3"><i class="ti ti-lightbulb text-warning me-2"></i>Tips Upload Dokumen</h6>
              <ul class="list-unstyled mb-0" style="font-size: 0.85rem;">
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="ti ti-check text-success mt-1 flex-shrink-0"></i>
                  <span>Pastikan dokumen terlihat jelas dan tidak buram</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="ti ti-check text-success mt-1 flex-shrink-0"></i>
                  <span>Semua sudut dokumen harus terlihat</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                  <i class="ti ti-check text-success mt-1 flex-shrink-0"></i>
                  <span>Ukuran file sesuai batas yang ditentukan</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-0">
                  <i class="ti ti-check text-success mt-1 flex-shrink-0"></i>
                  <span>Format file sesuai ketentuan (JPG/PDF)</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<style>
  .document-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
  }

  .document-card {
    transition: all 0.3s ease;
    border: 2px solid transparent !important;
  }

  .document-card:hover {
    border-color: #0d6efd !important;
    transform: translateY(-2px);
  }

  .upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
  }

  .upload-zone:hover {
    border-color: #0d6efd;
    background: #f0f7ff;
  }

  .upload-zone.has-file {
    border-color: #198754;
    background: #f8fdf9;
    border-style: solid;
  }

  .upload-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: #6c757d;
    font-weight: 500;
  }

  .upload-placeholder i {
    font-size: 1.2rem;
  }

  .upload-preview {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .upload-filename {
    font-weight: 500;
    color: #333;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
  }

  .d-none {
    display: none !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Setup upload zones for all dynamic berkas
    @if($hasDocuments)
      @foreach($syaratBerkas as $berkas)
        setupBerkasZone({{ $berkas->id }}, {
          maxSize: {{ ($berkas->max_size ?? 2048) * 1024 }},
        });
      @endforeach
    @endif
  });

  function setupBerkasZone(id, options) {
    const zone = document.getElementById('berkasZone_' + id);
    const input = document.getElementById('berkasInput_' + id);
    const placeholder = document.getElementById('berkasPlaceholder_' + id);
    const preview = document.getElementById('berkasPreview_' + id);
    const filename = document.getElementById('berkasName_' + id);

    if (!zone || !input) return;

    zone.addEventListener('click', function(e) {
      if (e.target.closest('.btn-outline-danger')) return;
      input.click();
    });

    input.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const file = this.files[0];
        if (file.size > options.maxSize) {
          alert('Ukuran file melebihi batas maksimal (' + formatSize(options.maxSize) + ')');
          this.value = '';
          return;
        }
        placeholder.classList.add('d-none');
        preview.classList.remove('d-none');
        filename.textContent = file.name;
        zone.classList.add('has-file');
      }
    });

    zone.addEventListener('dragover', function(e) {
      e.preventDefault();
      this.style.borderColor = '#0d6efd';
      this.style.background = '#f0f7ff';
    });

    zone.addEventListener('dragleave', function(e) {
      e.preventDefault();
      this.style.borderColor = '';
      this.style.background = '';
    });

    zone.addEventListener('drop', function(e) {
      e.preventDefault();
      this.style.borderColor = '';
      this.style.background = '';
      if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event('change'));
      }
    });
  }

  function removeBerkas(id) {
    const input = document.getElementById('berkasInput_' + id);
    const placeholder = document.getElementById('berkasPlaceholder_' + id);
    const preview = document.getElementById('berkasPreview_' + id);
    const zone = document.getElementById('berkasZone_' + id);

    if (input) input.value = '';
    if (placeholder) placeholder.classList.remove('d-none');
    if (preview) preview.classList.add('d-none');
    if (zone) zone.classList.remove('has-file');
  }

  function formatSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }
</script>
@endsection
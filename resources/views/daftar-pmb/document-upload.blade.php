@extends('layouts.app')

@section('content')
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

        <!-- Document Upload Grid -->
        <div class="row g-4 mb-4">

          <!-- Foto Formal -->
          <div class="col-md-6">
            <div class="card card-lg h-100 document-card">
              <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="document-icon bg-primary-subtle">
                    <i class="ti ti-user text-primary"></i>
                  </div>
                  @if(old('foto_formal_status') == 'uploaded')
                    <span class="badge bg-success-subtle text-success px-2 py-1"><i class="ti ti-check me-1"></i> Uploaded</span>
                  @endif
                </div>
                <h6 class="fw-bold mb-1">Foto Formal</h6>
                <p class="text-muted mb-2" style="font-size: 0.82rem;">Foto studio latar belakang biru atau merah. Wajah terlihat jelas.</p>
                <small class="text-muted d-block mb-3"><i class="ti ti-info-circle me-1"></i> Max 2MB, JPG/PNG</small>
                
                <div class="upload-zone" id="fotoFormalZone">
                  <input type="file" name="foto_formal" id="fotoFormalInput" class="d-none" accept=".jpg,.jpeg,.png">
                  <div class="upload-placeholder" id="fotoFormalPlaceholder">
                    <i class="ti ti-cloud-upload text-primary"></i>
                    <span>Pilih File</span>
                  </div>
                  <div class="upload-preview d-none" id="fotoFormalPreview">
                    <i class="ti ti-photo text-success"></i>
                    <span class="upload-filename" id="fotoFormalName"></span>
                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeFile('fotoFormal')">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Ijazah / SKHUN -->
          <div class="col-md-6">
            <div class="card card-lg h-100 document-card">
              <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="document-icon bg-info-subtle">
                    <i class="ti ti-school text-info"></i>
                  </div>
                </div>
                <h6 class="fw-bold mb-1">Ijazah / SKHUN</h6>
                <p class="text-muted mb-2" style="font-size: 0.82rem;">Unggah scan ijazah atau Surat Keterangan Hasil Ujian Nasional asli.</p>
                <small class="text-muted d-block mb-3"><i class="ti ti-info-circle me-1"></i> Max 5MB, PDF</small>
                
                <div class="upload-zone" id="ijazahZone">
                  <input type="file" name="ijazah" id="ijazahInput" class="d-none" accept=".pdf">
                  <div class="upload-placeholder" id="ijazahPlaceholder">
                    <i class="ti ti-cloud-upload text-primary"></i>
                    <span>Pilih File</span>
                  </div>
                  <div class="upload-preview d-none" id="ijazahPreview">
                    <i class="ti ti-file text-success"></i>
                    <span class="upload-filename" id="ijazahName"></span>
                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeFile('ijazah')">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Kartu Keluarga -->
          <div class="col-md-6">
            <div class="card card-lg h-100 document-card">
              <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="document-icon bg-warning-subtle">
                    <i class="ti ti-users text-warning"></i>
                  </div>
                </div>
                <h6 class="fw-bold mb-1">Kartu Keluarga</h6>
                <p class="text-muted mb-2" style="font-size: 0.82rem;">Scan Kartu Keluarga terbaru yang mencantumkan nama calon mahasiswa.</p>
                <small class="text-muted d-block mb-3"><i class="ti ti-info-circle me-1"></i> Max 5MB, PDF/JPG</small>
                
                <div class="upload-zone" id="kkZone">
                  <input type="file" name="kartu_keluarga" id="kkInput" class="d-none" accept=".pdf,.jpg,.jpeg">
                  <div class="upload-placeholder" id="kkPlaceholder">
                    <i class="ti ti-cloud-upload text-primary"></i>
                    <span>Pilih File</span>
                  </div>
                  <div class="upload-preview d-none" id="kkPreview">
                    <i class="ti ti-file text-success"></i>
                    <span class="upload-filename" id="kkName"></span>
                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeFile('kk')">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Akta Kelahiran -->
          <div class="col-md-6">
            <div class="card card-lg h-100 document-card">
              <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                  <div class="document-icon bg-success-subtle">
                    <i class="ti ti-certificate text-success"></i>
                  </div>
                </div>
                <h6 class="fw-bold mb-1">Akta Kelahiran</h6>
                <p class="text-muted mb-2" style="font-size: 0.82rem;">Scan Akta Kelahiran asli atau yang sudah dilegalisir.</p>
                <small class="text-muted d-block mb-3"><i class="ti ti-info-circle me-1"></i> Max 5MB, PDF/JPG</small>
                
                <div class="upload-zone" id="aktaZone">
                  <input type="file" name="akta_kelahiran" id="aktaInput" class="d-none" accept=".pdf,.jpg,.jpeg">
                  <div class="upload-placeholder" id="aktaPlaceholder">
                    <i class="ti ti-cloud-upload text-primary"></i>
                    <span>Pilih File</span>
                  </div>
                  <div class="upload-preview d-none" id="aktaPreview">
                    <i class="ti ti-file text-success"></i>
                    <span class="upload-filename" id="aktaName"></span>
                    <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeFile('akta')">
                      <i class="ti ti-trash"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

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
          <button type="submit" class="btn btn-primary fw-semibold px-4">
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

  /* Mini stepper */
  .stepper-item-sm {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
  }

  .stepper-circle-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
  }

  .stepper-label-sm {
    font-size: 0.7rem;
    white-space: nowrap;
  }

  .stepper-line-sm {
    width: 50px;
    height: 3px;
    background: #dee2e6;
    margin-bottom: 20px;
  }

  .stepper-item-sm.current .stepper-circle-sm {
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
  }

  .d-none {
    display: none !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Setup upload zones
    setupUploadZone('fotoFormal', {
      accept: '.jpg,.jpeg,.png',
      maxSize: 2 * 1024 * 1024, // 2MB
      allowedTypes: ['image/jpeg', 'image/png']
    });

    setupUploadZone('ijazah', {
      accept: '.pdf',
      maxSize: 5 * 1024 * 1024, // 5MB
      allowedTypes: ['application/pdf']
    });

    setupUploadZone('kk', {
      accept: '.pdf,.jpg,.jpeg',
      maxSize: 5 * 1024 * 1024, // 5MB
      allowedTypes: ['application/pdf', 'image/jpeg']
    });

    setupUploadZone('akta', {
      accept: '.pdf,.jpg,.jpeg',
      maxSize: 5 * 1024 * 1024, // 5MB
      allowedTypes: ['application/pdf', 'image/jpeg']
    });
  });

  function setupUploadZone(id, options) {
    const zone = document.getElementById(id + 'Zone');
    const input = document.getElementById(id + 'Input');
    const placeholder = document.getElementById(id + 'Placeholder');
    const preview = document.getElementById(id + 'Preview');
    const filename = document.getElementById(id + 'Name');

    if (!zone || !input) return;

    // Click to upload
    zone.addEventListener('click', function(e) {
      if (e.target.closest('.btn-outline-danger')) return;
      input.click();
    });

    // File selected
    input.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const file = this.files[0];

        // Validate size
        if (file.size > options.maxSize) {
          alert('Ukuran file melebihi batas maksimal (' + formatSize(options.maxSize) + ')');
          this.value = '';
          return;
        }

        // Validate type
        if (options.allowedTypes && !options.allowedTypes.includes(file.type)) {
          alert('Format file tidak valid');
          this.value = '';
          return;
        }

        // Show preview
        placeholder.classList.add('d-none');
        preview.classList.remove('d-none');
        filename.textContent = file.name;
        zone.classList.add('has-file');
      }
    });

    // Drag & drop
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

  function removeFile(id) {
    const input = document.getElementById(id + 'Input');
    const placeholder = document.getElementById(id + 'Placeholder');
    const preview = document.getElementById(id + 'Preview');
    const zone = document.getElementById(id + 'Zone');

    input.value = '';
    placeholder.classList.remove('d-none');
    preview.classList.add('d-none');
    zone.classList.remove('has-file');
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
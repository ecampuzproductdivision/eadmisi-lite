@extends('layouts.app')

@section('content')
<main class="p-6">
  <!-- Title and Subtitle -->
  <div class="row mb-6">
    <div class="col-12">
      <h1 class="mb-1 fw-bold">Account Settings</h1>
      <p class="mb-0 text-muted">Kelola informasi akun dan keamanan Anda.</p>
    </div>
  </div>

  <!-- Alert Notifications -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-3 p-4 mb-6" role="alert" style="background-color: #d1e7dd; border-left: 5px solid #0f5132 !important;">
      <div class="text-success fs-3">
        <i class="ti ti-circle-check-filled"></i>
      </div>
      <div>
        <h5 class="alert-heading mb-1 fw-bold text-success">Berhasil!</h5>
        <p class="mb-0 text-success-emphasis small">{{ session('success') }}</p>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error') || $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-3 p-4 mb-6" role="alert" style="background-color: #f8d7da; border-left: 5px solid #842029 !important;">
      <div class="text-danger fs-3">
        <i class="ti ti-alert-triangle-filled"></i>
      </div>
      <div>
        <h5 class="alert-heading mb-1 fw-bold text-danger">Gagal!</h5>
        <p class="mb-0 text-danger-emphasis small">
          @if(session('error'))
            {{ session('error') }}
          @else
            Terjadi kesalahan, silakan periksa kembali inputan Anda.
          @endif
        </p>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Tab Navigation -->
  <div class="row mb-6">
    <div class="col-12">
      <div class="card border-1">
        <div class="card-body p-0">
          <ul class="nav nav-line-bottom" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active px-6 py-4 fw-semibold" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                <i class="ti ti-user me-2"></i> Profile
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link px-6 py-4 fw-semibold" id="password-tab" data-bs-toggle="pill" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                <i class="ti ti-lock me-2"></i> Password
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link px-6 py-4 fw-semibold" id="avatar-tab" data-bs-toggle="pill" data-bs-target="#avatar" type="button" role="tab" aria-controls="avatar" aria-selected="false">
                <i class="ti ti-photo me-2"></i> Avatar
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Tab Content Panels -->
  <div class="tab-content" id="settingsTabContent">
    
    <!-- Tab 1: Profile -->
    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      <div class="row g-6">
        <!-- Left Side: Profile Form -->
        <div class="col-lg-8 col-12">
          <div class="card border-1 shadow-sm">
            <div class="card-header bg-white border-bottom border-light py-4 px-5">
              <h4 class="mb-0 fw-bold">Informasi Profil</h4>
            </div>
            <div class="card-body p-5">
              <form action="{{ route('account.settings.profile') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                  <label for="profile_name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="profile_name" name="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap Anda" required>
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="profile_email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="profile_email" name="email" value="{{ old('email', $user->email) }}" placeholder="Masukkan email Anda" required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="profile_phone" class="form-label fw-semibold">Telepon</label>
                  <input type="text" class="form-control @error('phone') is-invalid @enderror" id="profile_phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 0812-3456-7890">
                  @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="profile_bio" class="form-label fw-semibold">Bio</label>
                  <textarea class="form-control @error('bio') is-invalid @enderror" id="profile_bio" name="bio" rows="4" placeholder="Tuliskan deskripsi singkat mengenai diri Anda">{{ old('bio', $user->bio) }}</textarea>
                  @error('bio')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mt-5 text-end">
                  <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy fs-4"></i> Simpan
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Right Side: Preview -->
        <div class="col-lg-4 col-12">
          <div class="card border-1 shadow-sm text-center py-6 px-5 h-100 d-flex flex-column align-items-center justify-content-center">
            <h4 class="mb-4 fw-bold">Preview</h4>
            <div class="position-relative mb-4">
              <img src="{{ $user->avatar_url ?? '/assets/images/avatar/avatar-1.jpg' }}" alt="Profile Avatar" class="rounded-circle shadow" style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #fff;">
              <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle p-2" style="width: 20px; height: 20px;"></span>
            </div>
            <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
            <p class="text-muted small mb-3">{{ $user->email }}</p>
            @if($user->roles->isNotEmpty())
              <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                {{ $user->roles->first()->role_name }}
              </span>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 2: Password -->
    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
      <div class="row">
        <div class="col-lg-8 col-12 mx-auto">
          <div class="card border-1 shadow-sm">
            <div class="card-header bg-white border-bottom border-light py-4 px-5 d-flex justify-content-between align-items-center">
              <h4 class="mb-0 fw-bold">Ubah Password</h4>
              <i class="ti ti-shield-lock text-muted fs-3"></i>
            </div>
            <div class="card-body p-5">
              @if($user->auth_provider === 'google')
                <div class="alert alert-info border-0 p-4 d-flex align-items-start gap-3" role="alert" style="background-color: #e2f0fe;">
                  <i class="ti ti-info-circle text-primary fs-3 mt-1"></i>
                  <div>
                    <h5 class="alert-heading mb-1 fw-bold text-primary">Login via Google</h5>
                    <p class="mb-0 text-primary-emphasis small">Akun Anda terhubung dengan Google Auth. Anda tidak memerlukan password lokal untuk masuk.</p>
                  </div>
                </div>
              @endif

              <form action="{{ route('account.settings.password') }}" method="POST">
                @csrf
                @method('PUT')

                @if($user->auth_provider === 'local')
                  <div class="mb-4">
                    <label for="current_password" class="form-label fw-semibold">Password Saat Ini <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Masukkan password saat ini" required>
                      <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                        <i class="ti ti-eye"></i>
                      </button>
                      @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                @endif

                <div class="mb-4">
                  <label for="new_password" class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" placeholder="Masukkan password baru (min. 8 karakter)" required>
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                      <i class="ti ti-eye"></i>
                    </button>
                    @error('new_password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-text text-muted small mt-2">
                    <i class="ti ti-info-circle me-1"></i> Password harus minimal 8 karakter, kombinasi dari huruf & angka.
                  </div>
                </div>

                <div class="mb-4">
                  <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ulangi password baru Anda" required>
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                      <i class="ti ti-eye"></i>
                    </button>
                  </div>
                </div>

                <div class="mt-5 d-flex justify-content-end gap-2">
                  <a href="{{ route('account.settings') }}" class="btn btn-white border">Batal</a>
                  <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy fs-4"></i> Simpan
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab 3: Avatar -->
    <div class="tab-pane fade" id="avatar" role="tabpanel" aria-labelledby="avatar-tab">
      <div class="row">
        <div class="col-lg-8 col-12 mx-auto">
          <div class="card border-1 shadow-sm">
            <div class="card-header bg-white border-bottom border-light py-4 px-5 d-flex justify-content-between align-items-center">
              <h4 class="mb-0 fw-bold">Ubah Avatar</h4>
              <i class="ti ti-camera text-muted fs-3"></i>
            </div>
            <div class="card-body p-5">
              <form action="{{ route('account.settings.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Drag and drop zone -->
                <div class="upload-zone border border-dashed rounded-3 p-6 text-center mb-4 position-relative bg-light-subtle" id="uploadZone" style="cursor: pointer; border-color: #dee2e6 !important;">
                  <input type="file" name="avatar" id="avatarInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" accept="image/png, image/jpeg, image/jpg">
                  
                  <div class="upload-zone-content">
                    <i class="ti ti-cloud-upload text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold mb-1">Klik atau drag file ke sini</h5>
                    <p class="text-muted small mb-0">Format file PNG, JPG maksimal 2MB</p>
                  </div>
                  
                  <!-- File preview container inside zone -->
                  <div class="upload-zone-preview d-none">
                    <img id="uploadPreviewImage" class="rounded-circle mb-3 shadow" style="width: 100px; height: 100px; object-fit: cover;" src="#">
                    <h5 class="fw-bold mb-1 text-primary" id="uploadPreviewFilename">Filename.png</h5>
                    <p class="text-muted small mb-0" id="uploadPreviewFilesize">0 KB</p>
                  </div>
                </div>

                @error('avatar')
                  <div class="alert alert-danger border-0 p-3 mb-4 small">{{ $message }}</div>
                @enderror

                <!-- Current Avatar Action -->
                @if($user->avatar_url)
                  <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 mb-5">
                    <div class="d-flex align-items-center gap-3">
                      <img src="{{ $user->avatar_url }}" alt="Current Avatar" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                      <div>
                        <h6 class="mb-0 fw-bold">Foto Profil Saat Ini</h6>
                        <span class="text-muted small">Aktif digunakan di seluruh sistem</span>
                      </div>
                    </div>
                    <div>
                      <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1" onclick="document.getElementById('deleteAvatarForm').submit();">
                        <i class="ti ti-trash"></i> Hapus Avatar
                      </button>
                    </div>
                  </div>
                @endif

                <div class="d-flex justify-content-end gap-2">
                  <a href="{{ route('account.settings') }}" class="btn btn-white border">Batal</a>
                  <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy fs-4"></i> Simpan
                  </button>
                </div>
              </form>

              <!-- Hidden form for deleting avatar -->
              @if($user->avatar_url)
                <form id="deleteAvatarForm" action="{{ route('account.settings.avatar.delete') }}" method="POST" class="d-none">
                  @csrf
                  @method('DELETE')
                </form>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Interactive scripts for show/hide password, and file drag & drop preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // 1. Password Visibility Toggle
  const toggleButtons = document.querySelectorAll('.toggle-password');
  toggleButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const icon = this.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'ti ti-eye-off';
      } else {
        input.type = 'password';
        icon.className = 'ti ti-eye';
      }
    });
  });

  // 2. Avatar Drag & Drop zone dynamic highlights and file preview
  const avatarInput = document.getElementById('avatarInput');
  const uploadZone = document.getElementById('uploadZone');
  const zoneContent = document.querySelector('.upload-zone-content');
  const zonePreview = document.querySelector('.upload-zone-preview');
  const previewImage = document.getElementById('uploadPreviewImage');
  const previewFilename = document.getElementById('uploadPreviewFilename');
  const previewFilesize = document.getElementById('uploadPreviewFilesize');

  if (avatarInput && uploadZone) {
    // Add dragover styling
    uploadZone.addEventListener('dragover', () => {
      uploadZone.style.borderColor = '#0d6efd';
      uploadZone.style.backgroundColor = '#f8f9fa';
    });

    uploadZone.addEventListener('dragleave', () => {
      uploadZone.style.borderColor = '#dee2e6';
      uploadZone.style.backgroundColor = 'transparent';
    });

    uploadZone.addEventListener('drop', () => {
      uploadZone.style.borderColor = '#dee2e6';
      uploadZone.style.backgroundColor = 'transparent';
    });

    // Preview change event
    avatarInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Validation check for size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
          alert('Ukuran berkas melebihi batas maksimal 2MB!');
          avatarInput.value = '';
          return;
        }

        // Reader to load image
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;
          previewFilename.textContent = file.name;
          
          // Formatted size
          let sizeStr = '';
          if (file.size < 1024) {
            sizeStr = file.size + ' B';
          } else if (file.size < 1024 * 1024) {
            sizeStr = (file.size / 1024).toFixed(1) + ' KB';
          } else {
            sizeStr = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
          }
          previewFilesize.textContent = sizeStr;

          // Switch preview visibility
          zoneContent.classList.add('d-none');
          zonePreview.classList.remove('d-none');
          uploadZone.style.borderColor = '#198754'; // success green border
        };
        reader.readAsDataURL(file);
      }
    });
  }
  
  // 3. Keep active tab on refresh using URL Hash
  const hash = window.location.hash;
  if (hash) {
    const triggerEl = document.querySelector(`#settingsTab button[data-bs-target="${hash}"]`);
    if (triggerEl) {
      bootstrap.Tab.getOrCreateInstance(triggerEl).show();
    }
  }

  // Save hash to browser history on tab click
  const tabButtons = document.querySelectorAll('#settingsTab button');
  tabButtons.forEach(button => {
    button.addEventListener('shown.bs.tab', function (event) {
      const targetHash = event.target.getAttribute('data-bs-target');
      window.location.hash = targetHash;
    });
  });
});
</script>
@endsection

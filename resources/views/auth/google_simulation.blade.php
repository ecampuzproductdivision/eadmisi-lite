<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Google Identity Sandbox - Portal Calon Mahasiswa</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  
  <style>
    :root {
      --primary-color: #0b5ed7;
      --primary-hover: #0a58ca;
    }
    body {
      background-color: #f8fafc;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .sandbox-card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      background-color: #ffffff;
    }
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      font-weight: 600;
    }
    .btn-primary:hover, .btn-primary:focus {
      background-color: var(--primary-hover);
      border-color: var(--primary-hover);
    }
    .option-card {
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 16px;
      cursor: pointer;
      transition: all 0.2s ease-in-out;
    }
    .option-card:hover {
      border-color: var(--primary-color);
      background-color: #f0f7ff;
      transform: translateY(-2px);
    }
    .option-card.active {
      border-color: var(--primary-color);
      background-color: #f0f7ff;
      box-shadow: 0 0 0 2px rgba(11, 94, 215, 0.15);
    }
    .badge-sandbox {
      background-color: #e7f1ff;
      color: var(--primary-color);
      border: 1px solid #b6d4fe;
      font-size: 0.75rem;
      padding: 4px 10px;
      border-radius: 20px;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5">

  <div class="container" style="max-width: 580px;">
    <div class="text-center mb-4">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0b5ed7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"></path>
          <path d="M12 8v4l3 3"></path>
        </svg>
        <span class="fs-4 fw-bold text-dark">Google Auth Sandbox (Calon Mahasiswa)</span>
      </div>
      <p class="text-muted">Simulasi Pendaftaran Akun Google khusus untuk Portal Calon Mahasiswa PMB.</p>
    </div>

    <div class="card sandbox-card">
      <div class="card-body p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="mb-0 fw-bold text-dark">Pilih Akun Google Calon Mahasiswa</h5>
          <span class="badge-sandbox fw-semibold">STUDENT PORTAL ONLY</span>
        </div>

        <form action="{{ route('auth.google.callback') }}" method="GET" id="simulationForm">
          <input type="hidden" name="mock_login" value="true">
          <input type="hidden" name="google_id" id="google_id" value="">
          <input type="hidden" name="name" id="name_input" value="">
          <input type="hidden" name="email" id="email_input" value="">
          <input type="hidden" name="avatar_url" id="avatar_input" value="">

          <div class="d-flex flex-column gap-3 mb-4">
            
            <!-- Opsi 1: Calon Mahasiswa Baru (Google Register Pertama Kali) -->
            <div class="option-card active" onclick="selectOption('new_student', 'Budi Santoso', 'budi.santoso.camaba@gmail.com', 'https://lh3.googleusercontent.com/a/default-user', 'mock_id_camaba_budi_101')">
              <div class="d-flex align-items-center gap-3">
                <img src="https://lh3.googleusercontent.com/a/default-user" alt="Budi" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                <div class="flex-grow-1">
                  <h6 class="mb-0 fw-bold text-dark">Budi Santoso (Calon Mahasiswa Baru)</h6>
                  <p class="mb-0 text-muted small">budi.santoso.camaba@gmail.com</p>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill py-1 px-2 small">Profil Belum Lengkap</span>
              </div>
            </div>

            <!-- Opsi 2: Calon Mahasiswa Terdaftar -->
            <div class="option-card" onclick="selectOption('existing_student', 'Siti Rahma', 'siti.rahma.camaba@gmail.com', 'https://lh3.googleusercontent.com/a/default-user', 'mock_id_camaba_siti_102')">
              <div class="d-flex align-items-center gap-3">
                <img src="https://lh3.googleusercontent.com/a/default-user" alt="Siti" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                <div class="flex-grow-1">
                  <h6 class="mb-0 fw-bold text-dark">Siti Rahma (Calon Mahasiswa Terdaftar)</h6>
                  <p class="mb-0 text-muted small">siti.rahma.camaba@gmail.com</p>
                </div>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill py-1 px-2 small">Role: Calon Mahasiswa</span>
              </div>
            </div>

            <!-- Opsi 3: Kustom Profil Calon Mahasiswa -->
            <div class="option-card" id="customOptionCard" onclick="activateCustomOption()">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="avatar avatar-md bg-light text-muted rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px;">
                  <i class="ti ti-user-plus fs-4"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0 fw-bold text-dark">Profil Kustom Calon Mahasiswa</h6>
                  <p class="mb-0 text-muted small">Tentukan nama dan email Google Calon Mahasiswa sendiri.</p>
                </div>
              </div>
              <div id="customFormFields" class="mt-3 p-3 bg-light rounded d-none">
                <div class="mb-3">
                  <label class="form-label small fw-semibold text-dark">Nama Lengkap Calon Mahasiswa</label>
                  <input type="text" id="customName" class="form-control form-control-sm" placeholder="Contoh: Rian Hidayat" value="Rian Hidayat">
                </div>
                <div>
                  <label class="form-label small fw-semibold text-dark">Email Google Calon Mahasiswa</label>
                  <input type="email" id="customEmail" class="form-control form-control-sm" placeholder="Contoh: rian.hidayat@gmail.com" value="rian.hidayat@gmail.com">
                </div>
              </div>
            </div>

          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg py-2 font-weight-bold">
              <i class="ti ti-brand-google me-2"></i> Masuk / Daftar sebagai Calon Mahasiswa
            </button>
            <a href="{{ route('daftar-pmb') }}" class="btn btn-link text-muted small">Kembali ke Portal PMB</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    let selectedMode = 'new_student';

    function selectOption(mode, name, email, avatar, id) {
      if (mode === 'custom') return;
      selectedMode = mode;
      
      document.querySelectorAll('.option-card').forEach(card => card.classList.remove('active'));
      document.getElementById('customFormFields').classList.add('d-none');

      event.currentTarget.classList.add('active');

      document.getElementById('google_id').value = id;
      document.getElementById('name_input').value = name;
      document.getElementById('email_input').value = email;
      document.getElementById('avatar_input').value = avatar;
    }

    function activateCustomOption() {
      selectedMode = 'custom';
      
      document.querySelectorAll('.option-card').forEach(card => card.classList.remove('active'));
      document.getElementById('customOptionCard').classList.add('active');
      document.getElementById('customFormFields').classList.remove('d-none');
      
      syncCustomInputs();
    }

    function syncCustomInputs() {
      const name = document.getElementById('customName').value;
      const email = document.getElementById('customEmail').value;
      
      document.getElementById('google_id').value = 'mock_camaba_custom_' + Date.now();
      document.getElementById('name_input').value = name;
      document.getElementById('email_input').value = email;
      document.getElementById('avatar_input').value = 'https://lh3.googleusercontent.com/a/default-user';
    }

    document.getElementById('customName').addEventListener('input', syncCustomInputs);
    document.getElementById('customEmail').addEventListener('input', syncCustomInputs);

    window.addEventListener('DOMContentLoaded', () => {
      document.getElementById('google_id').value = 'mock_id_camaba_budi_101';
      document.getElementById('name_input').value = 'Budi Santoso';
      document.getElementById('email_input').value = 'budi.santoso.camaba@gmail.com';
      document.getElementById('avatar_input').value = 'https://lh3.googleusercontent.com/a/default-user';
    });
  </script>
</body>
</html>

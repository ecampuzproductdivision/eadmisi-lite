<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Google Identity Sandbox - eAkademik</title>
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/style.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  
  <style>
    :root {
      --primary-color: #f63a4c;
      --primary-hover: #d82939;
    }
    body {
      background-color: #f8fafc;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .sandbox-card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
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
      background-color: #fdf2f2;
      transform: translateY(-2px);
    }
    .option-card.active {
      border-color: var(--primary-color);
      background-color: #fdf2f2;
      box-shadow: 0 0 0 2px rgba(246, 58, 76, 0.15);
    }
    .badge-sandbox {
      background-color: #fef2f2;
      color: var(--primary-color);
      border: 1px solid #fecaca;
      font-size: 0.75rem;
      padding: 4px 8px;
      border-radius: 20px;
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5">

  <div class="container" style="max-width: 580px;">
    <div class="text-center mb-4">
      <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="text-danger">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <path d="M12 9v2m0 4v.01"></path>
          <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"></path>
        </svg>
        <span class="fs-4 fw-bold text-dark">Google Sign-in Sandbox</span>
      </div>
      <p class="text-muted">Simulasi otentikasi Google OAuth untuk lingkungan pengembangan lokal.</p>
    </div>

    <div class="card sandbox-card">
      <div class="card-body p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="mb-0 fw-bold text-dark">Pilih Akun Google</h4>
          <span class="badge-sandbox fw-semibold">SANDBOX ACTIVE</span>
        </div>

        <form action="{{ route('auth.google.callback') }}" method="GET" id="simulationForm">
          <input type="hidden" name="mock_login" value="true">
          <input type="hidden" name="google_id" id="google_id" value="">
          <input type="hidden" name="name" id="name_input" value="">
          <input type="hidden" name="email" id="email_input" value="">
          <input type="hidden" name="avatar_url" id="avatar_input" value="">

          <div class="d-flex flex-column gap-3 mb-4">
            
            <!-- Opsi 1: Staf Baru -->
            <div class="option-card active" onclick="selectOption('new_staff', 'Budi Google', 'budi.google@gmail.com', 'https://lh3.googleusercontent.com/a/default-user', 'mock_id_budi_123')">
              <div class="d-flex align-items-center gap-3">
                <img src="https://lh3.googleusercontent.com/a/default-user" alt="Budi" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                <div class="flex-grow-1">
                  <h6 class="mb-0 fw-bold text-dark">Budi Google (Pengguna Baru)</h6>
                  <p class="mb-0 text-muted small">budi.google@gmail.com</p>
                </div>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill py-1 px-2 small">Peran: Staff</span>
              </div>
            </div>

            <!-- Opsi 2: Super Admin Terdaftar -->
            <div class="option-card" onclick="selectOption('registered_admin', 'Super Admin', '{{ $adminEmail }}', 'https://lh3.googleusercontent.com/a/default-user', 'mock_id_admin_456')">
              <div class="d-flex align-items-center gap-3">
                <img src="https://lh3.googleusercontent.com/a/default-user" alt="Admin" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                <div class="flex-grow-1">
                  <h6 class="mb-0 fw-bold text-dark">Super Admin (Tautkan Akun)</h6>
                  <p class="mb-0 text-muted small">{{ $adminEmail }}</p>
                </div>
                <span class="badge bg-dark-subtle text-dark border border-dark-subtle rounded-pill py-1 px-2 small">Peran: Super Admin</span>
              </div>
            </div>

            <!-- Opsi 3: Kustom Profil -->
            <div class="option-card" id="customOptionCard" onclick="activateCustomOption()">
              <div class="d-flex align-items-center gap-3 mb-2">
                <div class="avatar avatar-md bg-light text-muted rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px;">
                  <i class="ti ti-user-cog fs-4"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-0 fw-bold text-dark">Profil Kustom Lainnya</h6>
                  <p class="mb-0 text-muted small">Tentukan nama dan email Anda sendiri.</p>
                </div>
              </div>
              <div id="customFormFields" class="mt-3 p-3 bg-light rounded d-none">
                <div class="mb-3">
                  <label class="form-label small fw-semibold text-dark">Nama Lengkap</label>
                  <input type="text" id="customName" class="form-control form-control-sm" placeholder="Contoh: Ahmad Fauzi" value="Ahmad Fauzi">
                </div>
                <div>
                  <label class="form-label small fw-semibold text-dark">Email Akun Google</label>
                  <input type="email" id="customEmail" class="form-control form-control-sm" placeholder="Contoh: ahmad.fauzi@gmail.com" value="ahmad.fauzi@gmail.com">
                </div>
              </div>
            </div>

          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg py-2">
              <i class="ti ti-brand-google me-2"></i> Lanjutkan Masuk dengan Google
            </button>
            <a href="{{ route('login') }}" class="btn btn-link text-muted small">Kembali ke Halaman Login Utama</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    let selectedMode = 'new_staff';

    function selectOption(mode, name, email, avatar, id) {
      if (mode === 'custom') return;
      selectedMode = mode;
      
      // Deactivate all cards
      document.querySelectorAll('.option-card').forEach(card => card.classList.remove('active'));
      // Hide custom fields
      document.getElementById('customFormFields').classList.add('d-none');

      // Activate clicked card
      event.currentTarget.classList.add('active');

      // Set input values
      document.getElementById('google_id').value = id;
      document.getElementById('name_input').value = name;
      document.getElementById('email_input').value = email;
      document.getElementById('avatar_input').value = avatar;
    }

    function activateCustomOption() {
      selectedMode = 'custom';
      
      // Deactivate all cards
      document.querySelectorAll('.option-card').forEach(card => card.classList.remove('active'));
      
      // Activate custom card
      document.getElementById('customOptionCard').classList.add('active');
      
      // Show custom fields
      document.getElementById('customFormFields').classList.remove('d-none');
      
      // Synchronize inputs
      syncCustomInputs();
    }

    function syncCustomInputs() {
      const name = document.getElementById('customName').value;
      const email = document.getElementById('customEmail').value;
      
      document.getElementById('google_id').value = 'mock_custom_' + Date.now();
      document.getElementById('name_input').value = name;
      document.getElementById('email_input').value = email;
      document.getElementById('avatar_input').value = 'https://lh3.googleusercontent.com/a/default-user';
    }

    // Attach listeners on custom inputs
    document.getElementById('customName').addEventListener('input', syncCustomInputs);
    document.getElementById('customEmail').addEventListener('input', syncCustomInputs);

    // Initial load setup (select first option by default)
    window.addEventListener('DOMContentLoaded', () => {
      document.getElementById('google_id').value = 'mock_id_budi_123';
      document.getElementById('name_input').value = 'Budi Google';
      document.getElementById('email_input').value = 'budi.google@gmail.com';
      document.getElementById('avatar_input').value = 'https://lh3.googleusercontent.com/a/default-user';
    });
  </script>
</body>
</html>

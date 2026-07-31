<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Codescandy" name="author">
    <title>eAdmisi | Platform Sistem Penerimaan Mahasiswa</title>
    <!-- Favicon icon-->
    <link rel="icon" href="/assets/images/favicon/favicon.ico" />

    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="msapplication-TileImage" content="{{ asset('assets/images/favicon/ms-icon-144x144.png') }}" />
    <meta name="theme-color" content="#ffffff" />
    <!-- Color modes -->
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
    <script>
      if (localStorage.getItem('sidebarExpanded') === 'false') {
        document.documentElement.classList.add('collapsed');
        document.documentElement.classList.remove('expanded');
      } else {
        document.documentElement.classList.remove('collapsed');
        document.documentElement.classList.add('expanded');
      }

      function updateBrandLogo() {
        const htmlEl = document.documentElement;
        let theme = htmlEl.getAttribute('data-bs-theme');
        if (!theme || theme === 'auto') {
          theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        
        const logoImgs = document.querySelectorAll('.brand-logo-img');
        logoImgs.forEach(img => {
          if (theme === 'dark') {
            img.src = "{{ asset('assets/images/brand/logo/logo-dark.png') }}";
          } else {
            img.src = "{{ asset('assets/images/brand/logo/logo-light.png') }}";
          }
        });
      }

      const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
          if (mutation.type === 'attributes' && mutation.attributeName === 'data-bs-theme') {
            updateBrandLogo();
          }
        });
      });
      observer.observe(document.documentElement, { attributes: true });

      document.addEventListener('DOMContentLoaded', updateBrandLogo);
    </script>
    <!-- Libs CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/dist/simplebar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/@tabler/icons-webfont/tabler-icons.min.css') }}" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">
    <style>
      body {
        font-family: 'Inter', sans-serif;
        background-image: url('{{ App\Models\LandingSetting::getValue("login_register_background") ? asset(App\Models\LandingSetting::getValue("login_register_background")) : "" }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }
      [data-bs-theme="dark"] body::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        z-index: 0;
      }
      [data-bs-theme="dark"] main {
        position: relative;
        z-index: 1;
      }
      .btn-primary {
        --ds-btn-hover-bg: #d82939;
        --ds-btn-hover-border-color: #d82939;
        --ds-btn-active-bg: #c82635;
        --ds-btn-active-border-color: #c82635;
      }
      .site-logo-text {
        color: #1c252e !important;
      }
      [data-bs-theme="dark"] .site-logo-text {
        color: #cfd1d2 !important;
      }
    </style>
  </head>

  <body>
    <main class="d-flex flex-column justify-content-center vh-100">
      <!--Sign up start-->
      <section>
        <div class="container">
          <div class="row mb-8">
            <div class="col-xl-4 offset-xl-4 col-md-12 col-12">
              <div class="text-center">
                <a href="/" class="fs-2 fw-bold d-flex align-items-center gap-2 justify-content-center mb-6 text-decoration-none">
                  <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" class="brand-logo-img" alt="" />
                  <span class="site-logo-text">Admisi</span>
                </a>
                <h1 class="fw-semibold mb-1">Welcome Back</h1>
                <p class="mb-0">
                  Don’t have an account yet?
                  <a href="{{ route('register') }}" class="text-primary">Register here</a>
                </p>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-12">
              <div class="card border card-lg mb-6">
                <div class="card-body p-6">
                  <form action="{{ route('login.post') }}" method="POST" class="needs-validation mb-6" novalidate>
                    @csrf

                    @if ($errors->any())
                      <div class="alert alert-danger mb-4 py-2 small">
                        <ul class="mb-0 ps-3">
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif

                    <div class="mb-3">
                      <label for="signinEmailInput" class="form-label">
                        Email
                        <span class="text-danger">*</span>
                      </label>
                      <input type="email" class="form-control" id="signinEmailInput" name="email" value="{{ old('email') }}" required />
                      <div class="invalid-feedback">Please enter email.</div>
                    </div>
                    <div class="mb-3">
                      <label for="formSignUpPassword" class="form-label">Password</label>
                      <div class="password-field position-relative">
                        <input type="password" class="form-control fakePassword" id="formSignUpPassword" name="password" required />
                        <span><i class="ti ti-eye-off passwordToggler"></i></span>
                        <div class="invalid-feedback">Please enter password.</div>
                      </div>
                    </div>

                    <div class="mb-4 d-flex align-items-center justify-content-between">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMeCheckbox" name="remember" />
                        <label class="form-check-label" for="rememberMeCheckbox">Remember me</label>
                      </div>

                      <div><a href="#" class="text-primary">Forgot Password</a></div>
                    </div>

                    <div class="d-grid">
                      <button class="btn btn-primary" type="submit">Sign In</button>
                    </div>
                  </form>

                  <center class="text-secondary mt-4">Or sign in with your social network.</center>
                  <div class="mt-3 d-flex gap-2 justify-content-between">
                    <a href="{{ route('auth.google.login') }}" class="btn btn-white w-100 d-flex align-items-center justify-content-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                      </svg>
                      Continue with Google
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!--Sign up end-->
      <div class="position-absolute end-0 bottom-0 m-4">
        <div class="dropdown">
          <button class="btn btn-light btn-icon rounded-circle d-flex align-items-center" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
            <i class="bi theme-icon-active lh-1"><i class="bi theme-icon bi-sun-fill"></i></i>
            <span class="visually-hidden bs-theme-text">Toggle theme</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true">
                <i class="ti theme-icon ti ti-sun"></i>
                <span class="ms-2">Light</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                <i class="ti theme-icon ti-moon-stars"></i>
                <span class="ms-2">Dark</span>
              </button>
            </li>
            <li>
              <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                <i class="ti theme-icon ti-circle-half-2"></i>
                <span class="ms-2">Auto</span>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </main>

    <!-- Libs JS -->
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>

    <!-- Theme JS -->
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/password.js') }}"></script>
  </body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP | Penerimaan Mahasiswa Baru</title>
    <link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}" />
    <meta name="theme-color" content="#ffffff" />
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/dist/simplebar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/@tabler/icons-webfont/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/theme.min.css') }}">
    <style>
        .btn-primary { --ds-btn-hover-bg: #d82939; --ds-btn-hover-border-color: #d82939; --ds-btn-active-bg: #c82635; --ds-btn-active-border-color: #c82635; }
        .site-logo-text { color: #1c252e !important; }
        [data-bs-theme="dark"] .site-logo-text { color: #cfd1d2 !important; }
        .otp-input { width: 60px; height: 70px; text-align: center; font-size: 28px; font-weight: 700; border: 2px solid #d9dee3; border-radius: 10px; transition: border-color 0.2s; }
        .otp-input:focus { border-color: #dc3545; outline: none; box-shadow: 0 0 0 3px rgba(220,53,69,0.15); }
    </style>
</head>
<body>
    <main class="d-flex flex-column justify-content-center py-6">
        <section>
            <div class="container">
                <div class="row mb-6">
                    <div class="col-xl-4 offset-xl-4 col-md-12 col-12">
                        <div class="text-center">
                            <a href="/" class="fs-2 fw-bold d-flex align-items-center gap-2 justify-content-center mb-4 text-decoration-none">
                                <img src="{{ asset('assets/images/brand/logo/logo-light.png') }}" class="brand-logo-img" width="36" alt="" />
                                <span class="site-logo-text">Akademik</span>
                            </a>
                            <h1 class="mb-1">Verifikasi Email</h1>
                            <p class="mb-0">Kode OTP telah dikirim ke <strong>{{ $email }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-7 col-12">
                        <div class="card card-lg mb-6">
                            <div class="card-body p-6 text-center">

                                @if(config('app.debug') && session('otp_code'))
                                    <div class="alert alert-info py-2 small mb-3">
                                        <strong>🔑 DEBUG MODE:</strong> Kode OTP Anda: 
                                        <span class="fw-bold fs-4 text-danger">{{ session('otp_code') }}</span>
                                        <br><small class="text-muted">(Hanya terlihat saat APP_DEBUG=true)</small>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger mb-4 py-2 small">
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <div class="icon-shape icon-xl bg-danger-subtle text-danger rounded-circle mx-auto" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                        <i class="ti ti-mail-opened fs-1"></i>
                                    </div>
                                </div>

                                <p class="text-muted small mb-4">Masukkan kode 6 digit yang dikirim ke email Anda</p>

                                <form action="{{ route('otp.verify') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $email }}">

                                    <div class="d-flex justify-content-center gap-2 mb-4">
                                        @for ($i = 0; $i < 6; $i++)
                                            <input type="text" class="otp-input form-control d-inline-block" name="otp_digit[]" maxlength="1" autofocus="{{ $i === 0 ? '' : false }}" style="width: 50px;">
                                        @endfor
                                    </div>
                                    <input type="hidden" name="otp_code" id="otp_code">

                                    <div class="d-grid mb-3">
                                        <button class="btn btn-primary" type="submit" id="verifyBtn">Verifikasi Akun</button>
                                    </div>
                                </form>

                                <form action="{{ route('otp.resend') }}" method="POST" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $email }}">
                                    <p class="text-muted small mb-0">Tidak menerima kode?
                                        <button type="submit" class="btn btn-link p-0 text-primary fw-semibold" style="vertical-align: baseline;">Kirim Ulang</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script>
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('otp_code');

        inputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateHidden();
            });
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
                updateHidden();
            });
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                if (paste.length === 6) {
                    inputs.forEach((inp, i) => {
                        inp.value = paste[i] || '';
                    });
                    inputs[5].focus();
                    updateHidden();
                }
            });
        });

        function updateHidden() {
            let code = '';
            inputs.forEach(inp => code += inp.value);
            hiddenInput.value = code;

            document.getElementById('verifyBtn').disabled = code.length !== 6;
        }
        updateHidden();
    </script>
</body>
</html>

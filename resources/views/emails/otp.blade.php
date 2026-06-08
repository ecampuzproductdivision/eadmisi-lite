<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode OTP Verifikasi</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7fa; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: #1c252e; padding: 30px 40px; text-align: center; }
        .header h1 { margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; }
        .body { padding: 40px; }
        .body h2 { color: #1c252e; font-size: 20px; margin: 0 0 10px; }
        .body p { color: #6c757d; line-height: 1.6; margin: 0 0 20px; }
        .otp-box { background: #f8f9fa; border-radius: 10px; padding: 20px; text-align: center; margin: 20px 0; border: 2px dashed #dee2e6; }
        .otp-code { font-size: 42px; font-weight: 800; letter-spacing: 10px; color: #dc3545; font-family: 'Courier New', monospace; }
        .footer { background: #f8f9fa; padding: 20px 40px; text-align: center; }
        .footer p { color: #adb5bd; font-size: 13px; margin: 0; }
        .alert { background: #fff3cd; border-radius: 8px; padding: 12px 16px; border-left: 4px solid #ffc107; font-size: 14px; color: #856404; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>eAkademik</h1>
        </div>
        <div class="body">
            <h2>Halo, {{ $userName }}!</h2>
            <p>Terima kasih telah mendaftar di eAkademik. Untuk menyelesaikan proses pendaftaran, silakan gunakan kode OTP berikut:</p>

            <div class="otp-box">
                <div class="otp-code">{{ $otpCode }}</div>
            </div>

            <p>Masukkan kode OTP di atas pada halaman verifikasi untuk mengaktifkan akun Anda.</p>

            <div class="alert">
                <strong>⏰ Perhatian:</strong> Kode OTP ini berlaku selama <strong>5 menit</strong>. Jangan bagikan kode ini kepada siapa pun.
            </div>

            <p style="margin-top: 20px;">Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} eAkademik. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
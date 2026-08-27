<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi 2FA - PT Buku & ATK Nusantara</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f1f5f9;
            padding: 30px 15px;
        }
        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            padding: 32px 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #bfdbfe;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .content {
            padding: 36px 32px 30px 32px;
        }
        .greeting {
            font-size: 17px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .otp-box-wrapper {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px dashed #93c5fd;
            border-radius: 14px;
            padding: 24px;
            text-align: center;
            margin: 24px 0;
        }
        .otp-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e40af;
            margin-bottom: 8px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 38px;
            font-weight: 900;
            letter-spacing: 8px;
            color: #1d4ed8;
            margin: 0;
            user-select: all;
        }
        .otp-expiry {
            margin-top: 10px;
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }
        .security-badge {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 14px 16px;
            border-radius: 6px;
            margin: 24px 0 16px 0;
        }
        .security-badge p {
            margin: 0;
            font-size: 13px;
            color: #991b1b;
            line-height: 1.5;
        }
        .meta-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
            margin-top: 20px;
        }
        .meta-info strong {
            color: #334155;
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 22px 30px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .footer a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>ERP PT Buku & ATK Nusantara</h1>
                <p>Otentikasi Dua Langkah (2FA / MFA)</p>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">
                    Halo, {{ $user->name }}
                </div>
                <div class="message">
                    Kami menerima permintaan autentikasi untuk masuk ke akun Anda. Gunakan kode <strong>One-Time Password (OTP)</strong> berikut untuk menyelesaikan proses verifikasi masuk:
                </div>

                <!-- OTP Display -->
                <div class="otp-box-wrapper">
                    <div class="otp-label">Kode Verifikasi Keamanan</div>
                    <div class="otp-code">{{ $otp }}</div>
                    <div class="otp-expiry">
                        ⏱️ Berlaku selama <strong>{{ $expiresInMinutes }} Menit</strong>
                    </div>
                </div>

                <!-- Security Note -->
                <div class="security-badge">
                    <p>
                        <strong>Peringatan Keamanan:</strong> Jangan berikan kode ini kepada siapa pun, termasuk staf PT Buku & ATK Nusantara. Pihak kami tidak akan pernah meminta kode OTP Anda.
                    </p>
                </div>

                <!-- Meta Details -->
                <div class="meta-info">
                    <strong>Rincian Permintaan:</strong><br>
                    • Akun: {{ $user->email }} ({{ $user->role ?? 'User' }})<br>
                    • Waktu: {{ date('d M Y, H:i:s') }} WIB<br>
                    @if(!empty($ipAddress))
                    • Alamat IP: {{ $ipAddress }}<br>
                    @endif
                </div>

                <div class="message" style="margin-top: 20px; font-size: 13px; color: #64748b;">
                    Jika Anda tidak merasa melakukan upaya login ini, abaikan email ini dan segera ubah kata sandi akun Anda untuk mengamankan akses.
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 6px 0;">
                    Email ini dibuat secara otomatis oleh Sistem ERP PT Distribusi Buku & ATK Nusantara.
                </p>
                <p style="margin: 0;">
                    &copy; {{ date('Y') }} PT Distribusi Buku dan Alat Tulis Nusantara. Seluruh Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </div>
</body>
</html>

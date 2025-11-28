<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - SPMB SMK Bakti Nusantara 666</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #1e3a8a; color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .button { display: inline-block; background: #f59e0b; color: #1e3a8a; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0; }
        .footer { background: #f1f5f9; padding: 20px; text-align: center; color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
            <p>SMK Bakti Nusantara 666</p>
        </div>
        
        <div class="content">
            <h2>Halo!</h2>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di sistem SPMB SMK Bakti Nusantara 666.</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Reset Password Sekarang</a>
            </div>
            
            <p><strong>Link ini akan kedaluwarsa dalam 60 menit.</strong></p>
            
            <p>Jika Anda tidak meminta reset password, abaikan email ini. Tidak ada tindakan lebih lanjut yang diperlukan.</p>
            
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #e2e8f0;">
            
            <p style="font-size: 14px; color: #64748b;">
                Jika Anda mengalami masalah dengan tombol "Reset Password", salin dan tempel URL berikut ke browser web Anda:
            </p>
            <p style="font-size: 12px; word-break: break-all; color: #64748b;">{{ $url }}</p>
        </div>
        
        <div class="footer">
            <p><strong>SMK Bakti Nusantara 666</strong></p>
            <p>Sistem Penerimaan Murid Baru Online (SPMB)</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas.</p>
        </div>
    </div>
</body>
</html>
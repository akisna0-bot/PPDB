<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reminder Pembayaran PPDB</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; background: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏫 SMK BAKTI NUSANTARA 666</h1>
            <p>Reminder Pembayaran PPDB</p>
        </div>
        
        <div class="content">
            <h2>Halo {{ $user->name }},</h2>
            
            <p>Kami ingin mengingatkan bahwa pembayaran pendaftaran Anda akan segera berakhir:</p>
            
            <div style="background: #fff; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <strong>Detail Pembayaran:</strong><br>
                No. Invoice: {{ $payment->invoice_number }}<br>
                Jumlah: Rp {{ number_format($payment->amount, 0, ',', '.') }}<br>
                Batas Waktu: {{ $payment->expired_at->format('d F Y, H:i') }} WIB
            </div>
            
            <p>Silakan segera lakukan pembayaran untuk melanjutkan proses pendaftaran Anda.</p>
            
            <a href="{{ route('payment.show', $payment->id) }}" class="button">
                💳 Bayar Sekarang
            </a>
            
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami di:</p>
            <ul>
                <li>📞 Telepon: (021) 123-4567</li>
                <li>📱 WhatsApp: 0812-3456-7890</li>
                <li>📧 Email: ppdb@smkbn666.sch.id</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>&copy; 2025 SMK BAKTI NUSANTARA 666. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
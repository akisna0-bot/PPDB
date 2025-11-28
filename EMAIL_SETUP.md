# Setup Email untuk OTP PPDB

## Opsi 1: Gmail SMTP (Untuk Production)

1. **Buat App Password di Gmail:**
   - Masuk ke Google Account Settings
   - Security → 2-Step Verification → App passwords
   - Generate password untuk "Mail"

2. **Update .env:**
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-16-digit-app-password
   MAIL_ENCRYPTION=tls
   ```

## Opsi 2: Mailtrap (Untuk Testing)

1. **Daftar di mailtrap.io**
2. **Buat inbox baru**
3. **Copy credentials ke .env:**
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-mailtrap-username
   MAIL_PASSWORD=your-mailtrap-password
   MAIL_ENCRYPTION=tls
   ```

## Opsi 3: Log Driver (Development)

Untuk development, email akan ditulis ke `storage/logs/laravel.log`:
```
MAIL_MAILER=log
```

## Test Email

Jalankan command untuk test:
```bash
php artisan test:email your-email@example.com
```

## Troubleshooting

1. **Gmail "Less secure app access"** - Gunakan App Password
2. **Port blocked** - Coba port 465 dengan SSL
3. **Firewall** - Pastikan port 587/465 tidak diblokir

## Fallback untuk Testing

Jika email gagal, sistem akan menampilkan OTP di response untuk testing.
Lihat browser console atau alert untuk kode OTP.
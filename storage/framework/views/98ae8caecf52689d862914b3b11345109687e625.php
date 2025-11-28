<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - PPDB SMK BAKTI NUSANTARA 666</title>
    <style>
        body{font-family: Arial, Helvetica, sans-serif;background:#f4f6f8;margin:0;padding:0;}
        .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center}
        .card{background:#fff;padding:28px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.08);width:360px}
        .logo{display:block;margin:0 auto 12px;width:86px;height:86px;object-fit:contain;border-radius:8px}
        h1{font-size:18px;margin:6px 0 18px;text-align:center;color:#0b3d91}
        label{display:block;font-size:13px;margin-bottom:6px;color:#333}
        input[type="email"],input[type="password"]{width:100%;padding:10px;border:1px solid #d6dbe0;border-radius:6px;margin-bottom:12px}
        .btn{display:block;width:100%;padding:10px;background:#0b3d91;color:#fff;border:0;border-radius:6px;cursor:pointer;font-weight:600}
        .meta{margin-top:12px;text-align:center;font-size:13px}
        .meta a{color:#0b3d91;text-decoration:none}
        .error{background:#ffe6e6;color:#900;padding:8px;border-radius:6px;margin-bottom:12px;font-size:13px}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <img src="<?php echo e(asset('images/logo-new.png.png')); ?>" alt="Logo SMK" style="width: 60px; height: 60px; margin-right: 15px;">
                <div>
                    <h1 style="margin: 0; font-size: 18px;">PPDB SMK BAKTI NUSANTARA 666</h1>
                    <p style="margin: 5px 0 0 0; font-size: 14px; color: #666;">Login Siswa</p>
                </div>
            </div>

            <?php if($errors->any()): ?>
                <div class="error"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <div>
                    <button class="btn" type="submit">Masuk</button>
                </div>
            </form>

            <div class="meta">
                <div><a href="<?php echo e(route('register')); ?>">Belum punya akun? Daftar</a></div>
                <div style="margin-top:6px"><a href="/">Kembali ke Beranda</a></div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ppdb\resources\views/auth/login.blade.php ENDPATH**/ ?>
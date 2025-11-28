<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - PPDB SMK BAKTI NUSANTARA 666</title>
    <style>
        body{font-family: Arial, Helvetica, sans-serif;background:#f4f6f8;margin:0;padding:0;}
        .wrap{min-height:100vh;display:flex;align-items:center;justify-content:center}
        .card{background:#fff;padding:28px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.08);width:360px}

        h1{font-size:18px;margin:6px 0 18px;text-align:center;color:#0b3d91}
        label{display:block;font-size:13px;margin-bottom:6px;color:#333}
        input[type="text"],input[type="email"],input[type="password"]{width:100%;padding:10px;border:1px solid #d6dbe0;border-radius:6px;margin-bottom:12px;box-sizing:border-box}
        .btn{display:block;width:100%;padding:10px;background:#0b3d91;color:#fff;border:0;border-radius:6px;cursor:pointer;font-weight:600}
        .btn:hover{background:#0a2d6b}
        .meta{margin-top:12px;text-align:center;font-size:13px}
        .meta a{color:#0b3d91;text-decoration:none}
        .error{background:#ffe6e6;color:#900;padding:8px;border-radius:6px;margin-bottom:12px;font-size:13px}
        .success{background:#e6ffe6;color:#090;padding:8px;border-radius:6px;margin-bottom:12px;font-size:13px}
        .btn:disabled{background:#ccc;cursor:not-allowed}
        .verified{color:#090;font-weight:bold}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <img src="{{ asset('images/logo-new.png.png') }}" alt="Logo SMK" style="width: 60px; height: 60px; margin-right: 15px;">
                <div>
                    <h1 style="margin: 0; font-size: 18px;">PPDB SMK BAKTI NUSANTARA 666</h1>
                    <p style="margin: 5px 0 0 0; font-size: 14px; color: #666;">Pendaftaran Siswa Baru</p>
                </div>
            </div>
            
            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                
                <button type="submit" class="btn">Daftar</button>
            </form>
            
            <div class="meta">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>
    </div>

</body>
</html>
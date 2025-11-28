<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PPDB SMK Bakti Nusantara 666</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .header { background: #2563eb; color: white; padding: 1rem 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .space-x-4 > * + * { margin-left: 1rem; }

        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-weight: bold; display: inline-block; }
        .btn-yellow { background: #eab308; color: black; }
        .btn-outline { border: 2px solid white; color: white; }
        .hero { background: linear-gradient(135deg, #2563eb, #1e40af); color: white; padding: 5rem 0; text-align: center; }
        .hero h1 { font-size: 3rem; margin-bottom: 1rem; }
        .hero p { font-size: 1.2rem; margin-bottom: 2rem; }
        .btn-green { background: #16a34a; color: white; padding: 1rem 2rem; border-radius: 8px; font-size: 1.1rem; text-decoration: none; display: inline-block; }
        .cards { padding: 3rem 0; background: white; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
        .card { text-align: center; padding: 2rem; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; }
        .card-icon { font-size: 3rem; margin-bottom: 1rem; }
        .timeline { padding: 3rem 0; background: #f1f5f9; }
        .timeline h2 { text-align: center; font-size: 2rem; margin-bottom: 2rem; }
        .timeline-item { background: white; padding: 1.5rem; margin-bottom: 1rem; border-radius: 8px; border-left: 4px solid #16a34a; }
        .footer { background: #1f2937; color: white; padding: 2rem 0; text-align: center; }
        .jurusan { padding: 3rem 0; background: white; }
        .jurusan h2 { text-align: center; font-size: 2rem; margin-bottom: 2rem; }
        .jurusan-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
        .jurusan-card { padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; }
        .jurusan-card.pplg { background: #dbeafe; border-color: #3b82f6; }
        .jurusan-card.akt { background: #dcfce7; border-color: #16a34a; }
        .jurusan-card.dkv { background: #f3e8ff; border-color: #8b5cf6; }
        .jurusan-card.anm { background: #fef3c7; border-color: #f59e0b; }
        .jurusan-card.pemasaran { background: #fce7f3; border-color: #ec4899; }
        .logo { 
            width: 50px; 
            height: 50px; 
            border-radius: 12px; 
            object-fit: cover;
            background: white;
            padding: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: 2px solid rgba(255,255,255,0.2);
            transition: transform 0.3s ease;
        }
        .logo:hover {
            transform: scale(1.05);
        }
        .nav { display: none; }
        @media (min-width: 768px) { .nav { display: flex; } }
        .nav a { color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; }
        .nav a:hover { background: rgba(255,255,255,0.1); }
        .mobile-menu { display: none; background: #1e40af; }
        .mobile-btn { display: block; }
        @media (min-width: 768px) { .mobile-btn { display: none; } }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="<?php echo e(asset('images/logo-new.png.png')); ?>" alt="SMK Bakti Nusantara 666" class="logo" style="margin-right: 1rem;">
                    <div>
                        <h1 style="font-size: 1.25rem; font-weight: bold;">SMK Bakti Nusantara 666</h1>
                        <p style="font-size: 0.9rem;">PPDB Online 2025</p>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="nav space-x-4">
                    <a href="#home">Beranda</a>
                    <a href="#timeline">Jadwal</a>
                    <a href="#jurusan">Jurusan</a>
                    <a href="#panduan">Panduan</a>
                    <a href="#kontak">Kontak</a>
                </nav>
                
                <!-- Mobile Menu Button -->
                <button class="mobile-btn" onclick="toggleMenu()" style="background: none; border: none; color: white; font-size: 1.5rem;">
                    ☰
                </button>
                
                <div class="flex space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-yellow">
                            Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-yellow">
                            Login
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-outline">
                            Daftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu">
            <div style="padding: 1rem;">
                <a href="#home" style="display: block; color: white; text-decoration: none; padding: 0.5rem 0;">Beranda</a>
                <a href="#timeline" style="display: block; color: white; text-decoration: none; padding: 0.5rem 0;">Jadwal</a>
                <a href="#jurusan" style="display: block; color: white; text-decoration: none; padding: 0.5rem 0;">Jurusan</a>
                <a href="#panduan" style="display: block; color: white; text-decoration: none; padding: 0.5rem 0;">Panduan</a>
                <a href="#kontak" style="display: block; color: white; text-decoration: none; padding: 0.5rem 0;">Kontak</a>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section id="home" class="hero">
        <div class="container">
            <h1>SMK Bakti Nusantara 666</h1>
            <p>Penerimaan Peserta Didik Baru 2025</p>
            
            <?php if(auth()->guard()->guest()): ?>
            <div style="margin-top: 2rem;">
                <a href="<?php echo e(route('register')); ?>" class="btn-green" style="margin-right: 1rem;">🚀 Daftar Sekarang</a>
            </div>
            <?php else: ?>
            <div style="margin-top: 2rem;">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-green">Dashboard Saya</a>
            </div>
            <?php endif; ?>
        </div>
    </section>



    <!-- Timeline -->
    <section id="timeline" class="timeline">
        <div class="container">
            <h2>Timeline PPDB 2025</h2>
            <div class="timeline-item">
                <h3 style="font-weight: bold; color: #16a34a;">🌊 Gelombang 1</h3>
                <p><strong>Pendaftaran:</strong> 1 Jan - 31 Mar 2025 (Biaya Rp 150.000)</p>
                <p><strong>Verifikasi:</strong> 1 - 4 Apr 2025</p>
                <p><strong>Pengumuman:</strong> 5 Apr 2025</p>
            </div>
            <div class="timeline-item" style="border-left-color: #2563eb;">
                <h3 style="font-weight: bold; color: #2563eb;">🌊 Gelombang 2</h3>
                <p><strong>Pendaftaran:</strong> 1 Apr - 30 Jun 2025 (Biaya Rp 200.000)</p>
                <p><strong>Verifikasi:</strong> 1 - 8 Jul 2025</p>
                <p><strong>Pengumuman:</strong> 10 Jul 2025</p>
            </div>
        </div>
    </section>

    <!-- Program Keahlian -->
    <section id="jurusan" class="jurusan">
        <div class="container">
            <h2>🎯 Program Keahlian</h2>
            <div class="jurusan-grid">
                <div class="jurusan-card pplg">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">💻</div>
                    <h3 style="font-weight: bold; color: #1e40af; margin-bottom: 0.5rem;">Pengembangan Perangkat Lunak & Gim</h3>
                    <p style="color: #1e40af;">Programming, Web Development, Mobile Apps, Game Development</p>
                </div>
                <div class="jurusan-card akt">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
                    <h3 style="font-weight: bold; color: #16a34a; margin-bottom: 0.5rem;">Akuntansi & Keuangan Lembaga</h3>
                    <p style="color: #16a34a;">Pembukuan, Laporan Keuangan, Perpajakan, Audit</p>
                </div>
                <div class="jurusan-card dkv">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🎨</div>
                    <h3 style="font-weight: bold; color: #8b5cf6; margin-bottom: 0.5rem;">Desain Komunikasi Visual</h3>
                    <p style="color: #8b5cf6;">Graphic Design, Branding, Ilustrasi, Layout Design</p>
                </div>
                <div class="jurusan-card anm">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🎬</div>
                    <h3 style="font-weight: bold; color: #f59e0b; margin-bottom: 0.5rem;">Animasi</h3>
                    <p style="color: #f59e0b;">2D Animation, 3D Modeling, Motion Graphics, Video Production</p>
                </div>
                <div class="jurusan-card pemasaran">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📈</div>
                    <h3 style="font-weight: bold; color: #ec4899; margin-bottom: 0.5rem;">Pemasaran</h3>
                    <p style="color: #ec4899;">Digital Marketing, Sales, E-commerce, Social Media Marketing</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Panduan -->
    <section id="panduan" class="timeline">
        <div class="container">
            <h2>📋 Panduan Pendaftaran</h2>
            <div class="timeline-item">
                <h3 style="font-weight: bold; color: #16a34a;">📝 Persyaratan</h3>
                <p>• Ijazah SMP/MTs atau Surat Keterangan Lulus</p>
                <p>• Kartu Keluarga dan KTP Orang Tua</p>
                <p>• Akta Kelahiran</p>
                <p>• Pas Foto 3x4 (2 lembar)</p>
            </div>
            <div class="timeline-item" style="border-left-color: #2563eb;">
                <h3 style="font-weight: bold; color: #2563eb;">🚀 Panduan Daftar</h3>
                <p>• Klik tombol "Daftar Sekarang" di atas</p>
                <p>• Isi formulir pendaftaran dengan lengkap</p>
                <p>• Upload dokumen persyaratan</p>
                <p>• Lakukan pembayaran sesuai gelombang</p>
                <p>• Tunggu verifikasi dan pengumuman</p>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="jurusan">
        <div class="container">
            <h2>📞 Hubungi Kami</h2>
            <div class="jurusan-grid">
                <div class="jurusan-card tkj">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📍</div>
                    <h3 style="font-weight: bold; color: #1e40af; margin-bottom: 0.5rem;">Alamat</h3>
                    <p style="color: #1e40af;">Jl. Raya Percobaan No.65<br>Bandung</p>
                </div>
                <div class="jurusan-card mm">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📞</div>
                    <h3 style="font-weight: bold; color: #16a34a; margin-bottom: 0.5rem;">Telepon</h3>
                    <p style="color: #16a34a;">021-1234-5678<br>WhatsApp: 0812-3456-7890</p>
                </div>
                <div class="jurusan-card tkr">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">✉️</div>
                    <h3 style="font-weight: bold; color: #8b5cf6; margin-bottom: 0.5rem;">Email</h3>
                    <p style="color: #8b5cf6;">ppdb@smkbaktinusantara.sch.id<br>info@smkbaktinusantara.sch.id</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 SMK Bakti Nusantara 666</p>
            <p style="font-size: 0.9rem; margin-top: 0.5rem;">Sistem PPDB Online</p>
        </div>
    </footer>
    
    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }
        
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\ppdb\resources\views/welcome.blade.php ENDPATH**/ ?>
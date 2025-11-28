<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan PPDB - SMK Bakti Nusantara 666</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .subtitle { font-size: 14px; margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN PENERIMAAN PESERTA DIDIK BARU</div>
        <div class="subtitle">SMK BAKTI NUSANTARA 666</div>
        <div class="subtitle">Tahun Ajaran 2025/2026</div>
        <div style="margin-top: 10px;">Tanggal Export: {{ $tanggal_export }}</div>
    </div>

    <div class="section">
        <div class="section-title">1. PENDAFTAR VS KUOTA PER JURUSAN</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Jurusan</th>
                    <th>Kuota</th>
                    <th>Pendaftar</th>
                    <th>Diterima</th>
                    <th>% Terisi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($kpi['pendaftar_vs_kuota']) && count($kpi['pendaftar_vs_kuota']) > 0)
                    @foreach($kpi['pendaftar_vs_kuota'] as $jurusan)
                    <tr>
                        <td>{{ $jurusan['code'] ?? '-' }}</td>
                        <td>{{ $jurusan['name'] ?? '-' }}</td>
                        <td>{{ $jurusan['kuota'] ?? 0 }}</td>
                        <td>{{ $jurusan['pendaftar'] ?? 0 }}</td>
                        <td>{{ $jurusan['diterima'] ?? 0 }}</td>
                        <td>{{ $jurusan['persentase_terisi'] ?? 0 }}%</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada data</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">2. STATUS KELULUSAN</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($kpi['status_kelulusan']))
                <tr>
                    <td>Diterima</td>
                    <td>{{ $kpi['status_kelulusan']['diterima'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Tidak Lulus</td>
                    <td>{{ $kpi['status_kelulusan']['tidak_lulus'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Cadangan</td>
                    <td>{{ $kpi['status_kelulusan']['cadangan'] ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Pending</td>
                    <td>{{ $kpi['status_kelulusan']['pending'] ?? 0 }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="2" style="text-align: center;">Belum ada data</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">3. TOTAL DANA TERKUMPUL</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Dana Pendaftaran</td>
                    <td>Rp {{ number_format($kpi['total_dana'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 50px; text-align: right;">
        <div>Bandung, {{ $tanggal_export }}</div>
        <div style="margin-top: 60px;">
            <div>Kepala Sekolah</div>
            <div style="margin-top: 40px;">
                <div>_________________________</div>
                <div>NIP. </div>
            </div>
        </div>
    </div>
</body>
</html>
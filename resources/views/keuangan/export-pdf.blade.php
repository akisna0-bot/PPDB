<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan PPDB</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        .stats { display: flex; justify-content: space-around; margin-bottom: 30px; }
        .stat-box { text-align: center; padding: 15px; border: 1px solid #ddd; }
        .stat-number { font-size: 24px; font-weight: bold; color: #16a34a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .status-paid { color: #16a34a; font-weight: bold; }
        .status-pending { color: #f59e0b; font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN PPDB</h1>
        <p>SMK Bakti Nusantara 666</p>
        <p>Periode: {{ date('d F Y') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-number">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</div>
            <div>Total Pendapatan</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['lunas'] }}</div>
            <div>Sudah Bayar</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['pending'] }}</div>
            <div>Menunggu Bayar</div>
        </div>
    </div>

    <h3>Detail Pembayaran</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pendaftaran</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Gelombang</th>
                <th class="text-right">Biaya</th>
                <th class="text-center">Status</th>
                <th class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applicants as $index => $applicant)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $applicant->no_pendaftaran }}</td>
                <td>{{ $applicant->user->name }}</td>
                <td>{{ $applicant->major->name ?? 'N/A' }}</td>
                <td>{{ $applicant->wave->name ?? 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($applicant->wave->biaya ?? 150000, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($applicant->status == 'PAID')
                        <span class="status-paid">LUNAS</span>
                    @else
                        <span class="status-pending">PENDING</span>
                    @endif
                </td>
                <td class="text-center">{{ $applicant->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</td>
                <td colspan="2" class="text-center">{{ $stats['lunas'] }} dari {{ $applicants->count() }} siswa</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate pada {{ date('d F Y H:i:s') }}</p>
        <p>SMK Bakti Nusantara 666 - Sistem PPDB Online</p>
    </div>
</body>
</html>
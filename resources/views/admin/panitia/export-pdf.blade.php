<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pendaftar SPMB</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .status-verified { color: green; }
        .status-rejected { color: red; }
        .status-pending { color: orange; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA PENDAFTAR</h1>
        <h2>SISTEM PENERIMAAN MAHASISWA BARU (SPMB)</h2>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
        @if($request->status)
            <p>Filter Status: {{ ucfirst($request->status) }}</p>
        @endif
        @if($request->major_id)
            <p>Filter Jurusan: {{ $majors->find($request->major_id)->name ?? 'N/A' }}</p>
        @endif
        @if($request->wave_id)
            <p>Filter Gelombang: {{ $waves->find($request->wave_id)->name ?? 'N/A' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pendaftaran</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applicants as $index => $applicant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $applicant->no_pendaftaran ?? 'Belum ada' }}</td>
                <td>{{ $applicant->nama_lengkap ?? $applicant->user->name ?? 'Belum diisi' }}</td>
                <td>{{ $applicant->user->email ?? 'N/A' }}</td>
                <td>{{ $applicant->major->name ?? 'Belum dipilih' }}</td>
                <td class="
                    @if($applicant->status == 'VERIFIED') status-verified
                    @elseif($applicant->status == 'REJECTED') status-rejected
                    @else status-pending @endif">
                    @if($applicant->status == 'VERIFIED') Terverifikasi
                    @elseif($applicant->status == 'REJECTED') Ditolak
                    @else Menunggu @endif
                </td>
                <td>{{ $applicant->created_at ? $applicant->created_at->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Data: {{ $applicants->count() }} pendaftar</p>
        <p>Dicetak oleh: {{ auth()->user()->name }}</p>
    </div>
</body>
</html>
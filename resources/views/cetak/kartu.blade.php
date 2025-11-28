<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Pendaftaran</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { width: 80px; height: 80px; }
        .title { font-size: 18px; font-weight: bold; margin: 10px 0; }
        .subtitle { font-size: 14px; color: #666; }
        .card { border: 2px solid #333; padding: 20px; margin: 20px 0; }
        .row { display: flex; margin: 10px 0; }
        .label { width: 150px; font-weight: bold; }
        .value { flex: 1; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SMK BAKTI NUSANTARA 666</div>
        <div class="subtitle">KARTU PENDAFTARAN PESERTA DIDIK BARU</div>
        <div class="subtitle">Tahun Pelajaran {{ date('Y') }}/{{ date('Y')+1 }}</div>
    </div>

    <div class="card">
        <div class="row">
            <div class="label">No. Pendaftaran:</div>
            <div class="value">{{ $applicant->no_pendaftaran }}</div>
        </div>
        <div class="row">
            <div class="label">Nama Lengkap:</div>
            <div class="value">{{ $applicant->user->name }}</div>
        </div>
        <div class="row">
            <div class="label">Email:</div>
            <div class="value">{{ $applicant->user->email }}</div>
        </div>
        <div class="row">
            <div class="label">Jurusan Pilihan:</div>
            <div class="value">{{ $applicant->major->nama }}</div>
        </div>
        <div class="row">
            <div class="label">Gelombang:</div>
            <div class="value">{{ $applicant->wave->nama }}</div>
        </div>
        <div class="row">
            <div class="label">Tanggal Daftar:</div>
            <div class="value">{{ $applicant->created_at->format('d F Y') }}</div>
        </div>
        <div class="row">
            <div class="label">Status:</div>
            <div class="value">
                @if($applicant->status == 'ADM_PASS') LOLOS VERIFIKASI
                @elseif($applicant->status == 'SUBMIT') MENUNGGU VERIFIKASI
                @else {{ $applicant->status }} @endif
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Kartu ini adalah bukti sah pendaftaran di SMK BAKTI NUSANTARA 666</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
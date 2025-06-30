<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pengajuan Cuti</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.6; }
        h2 { text-align: center; margin-bottom: 20px; }
        .label { font-weight: bold; }
        .section { margin-bottom: 15px; }
        .box { border: 1px solid #ccc; padding: 12px; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>Detail Pengajuan Cuti</h2>

    <div class="section">
        <div class="label">Nama:</div>
        <div class="box">{{ $leave->user->name ?? 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="label">NIP:</div>
        <div class="box">{{ $leave->nip ?? 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="label">Jenis Cuti:</div>
        <div class="box">{{ $leave->leave_type ?? 'N/A' }}</div>
    </div>

    <div class="section">
        <div class="label">Tanggal Cuti:</div>
        <div class="box">
            {{ \Carbon\Carbon::parse($leave->start_date)->format('d F Y') }} –
            {{ \Carbon\Carbon::parse($leave->end_date)->format('d F Y') }}
        </div>
    </div>

    <div class="section">
        <div class="label">Durasi:</div>
        <div class="box">{{ $leave->duration }} hari</div>
    </div>

    <div class="section">
        <div class="label">Status:</div>
        <div class="box">{{ ucfirst($leave->status) }}</div>
    </div>

    <div class="section">
        <div class="label">Nomor Telepon:</div>
        <div class="box">{{ $leave->phone_number ?? '-' }}</div>
    </div>

    <div class="section">
        <div class="label">Alasan:</div>
        <div class="box">{{ $leave->reason ?? '-' }}</div>
    </div>
</body>
</html>

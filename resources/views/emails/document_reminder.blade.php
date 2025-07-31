<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Dokumen</title>
</head>
<body>
    <h1>Halo, {{ $document->user->name }}</h1>

    {{-- Logika untuk menampilkan pesan dinamis --}}
    @if ($sisa_hari > 0)
        <p>Ini adalah pengingat otomatis bahwa dokumen Anda akan segera jatuh tempo dalam <strong>{{ $sisa_hari }} hari lagi.</strong></p>
    @elseif ($sisa_hari == 0)
        <p><strong>PERINGATAN PENTING:</strong> Dokumen Anda jatuh tempo <strong>HARI INI.</strong></p>
    @else
        <p><strong>PERHATIAN:</strong> Dokumen Anda telah <strong>TERLAMBAT</strong> melewati tanggal deadline.</p>
    @endif

    <p><strong>Detail Dokumen:</strong></p>
    <ul>
        <li><strong>Nama Dokumen:</strong> {{ $document->nama_dokumen }}</li>
        <li><strong>Tanggal Deadline:</strong> {{ \Carbon\Carbon::parse($document->tanggal_deadline)->format('d F Y') }}</li>
    </ul>

    <p>
        Silakan lakukan follow-up sesegera mungkin. Anda bisa melihat detailnya di aplikasi.
    </p>

    <p>Terima kasih!</p>
</body>
</html>
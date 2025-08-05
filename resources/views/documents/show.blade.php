<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Dokumen - {{ $document->nama_dokumen }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <nav class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">← Kembali ke Daftar</a>
            @auth
                <div class="d-flex align-items-center">
                    <span class="me-3">Halo, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                    </form>
                </div>
            @endauth
        </nav>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Dokumen</h5>
                <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning btn-sm">Edit Dokumen</a>
            </div>
            <div class="card-body">
                <h3>{{ $document->nama_dokumen }}</h3>
                <p class="text-muted">
                    Periode:
                    @if ($document->tanggal_mulai)
                        {{ \Carbon\Carbon::parse($document->tanggal_mulai)->format('d/m/Y') }} -
                    @endif
                    {{ \Carbon\Carbon::parse($document->tanggal_deadline)->format('d/m/Y') }}
                </p>

                {{-- TAMBAHKAN BAGIAN INI UNTUK MENAMPILKAN FILE --}}
                <hr>
                @if ($document->file_path)
                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-success">
                        <i class="bi bi-file-earmark-arrow-down"></i> Lihat Dokumen Terlampir
                    </a>
                @else
                    <p class="text-muted">Tidak ada dokumen terlampir.</p>
                @endif
            </div>
        </div>

        {{-- Sisa kode (Activity Log, dll) tetap sama --}}
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
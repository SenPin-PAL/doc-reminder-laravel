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

        {{-- Menampilkan pesan sukses setelah operasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Kartu Detail Dokumen --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Dokumen</h5>
                <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning btn-sm">Edit Dokumen</a>
            </div>
            <div class="card-body">
                <h3>{{ $document->nama_dokumen }}</h3>
                <p class="text-muted">Deadline: {{ \Carbon\Carbon::parse($document->tanggal_deadline)->format('d F Y') }}</p>
            </div>
        </div>

        {{-- Kartu Log Aktivitas --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Log Aktivitas</h5>
    </div>
    <ul class="list-group list-group-flush">
        @forelse ($activities as $activity) {{-- Variabelnya sekarang $activities --}}
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1">{{ $activity->description }}</p>
                    <small class="text-muted">
    Oleh: <strong>{{ $activity->user->name }}</strong> - {{ $activity->updated_at->diffForHumans() }}
    {{-- Tambahkan label '(diedit)' jika waktu update berbeda dari waktu pembuatan --}}
    @if ($activity->created_at->ne($activity->updated_at))
        (diedit)
    @endif
</small>
                </div>
                {{-- Tampilkan tombol HANYA jika user punya izin (hak akses) --}}
                @can('update', $activity)
                    <div class="d-flex">
                        <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-outline-secondary btn-sm me-2">Edit</a>
                        <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                @endcan
            </li>
        @empty
            <li class="list-group-item text-center">Belum ada aktivitas.</li>
        @endforelse
    </ul>
    {{-- Tampilkan link Paginasi --}}
    @if($activities->hasPages())
        <div class="card-footer">
            {{ $activities->links() }}
        </div>
    @endif
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document Reminder (Laravel)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .status-badge { padding: 0.5em 0.75em; color: white; border-radius: 12px; font-weight: bold; }
        .status-aktif { background-color: #2a9d8f; }
        .status-tenggang { background-color: #e9c46a; }
        .status-overdue { background-color: #e76f51; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Aplikasi Pengingat Dokumen</h1>
            @auth
                <div class="d-flex align-items-center">
                    <span class="me-3">Halo, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                    </form>
                </div>
            @endauth
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">Tambah Dokumen Baru</div>
            <div class="card-body">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                            <input type="text" name="nama_dokumen" id="nama_dokumen" class="form-control @error('nama_dokumen') is-invalid @enderror" value="{{ old('nama_dokumen') }}" required>
                            @error('nama_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_deadline" class="form-label">Tanggal Deadline</label>
                            <input type="date" name="tanggal_deadline" id="tanggal_deadline" class="form-control @error('tanggal_deadline') is-invalid @enderror" value="{{ old('tanggal_deadline') }}" required>
                             @error('tanggal_deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="document_file" class="form-label">Upload File</label>
                            <input class="form-control @error('document_file') is-invalid @enderror" type="file" name="document_file" id="document_file">
                            @error('document_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Tambah Dokumen</button>
                </form>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Pencarian & Filter</div>
            <div class="card-body">
                <form action="{{ route('documents.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama dokumen..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tenggang" {{ request('status') == 'Tenggang' ? 'selected' : '' }}>Tenggang</option>
                                <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex">
                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('documents.index') }}" class="btn btn-secondary ms-2">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- BAGIAN YANG HILANG ADA DI BAWAH INI --}}
        <div class="card">
            <div class="card-header">Daftar Dokumen</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4">Nama Dokumen</th>
                            <th scope="col">Periode Valid</th>
                            <th scope="col">Status</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <a href="{{ route('documents.show', $doc->id) }}">{{ $doc->nama_dokumen }}</a>
                                </td>
                                <td>
                                    @if ($doc->tanggal_mulai)
                                        {{ \Carbon\Carbon::parse($doc->tanggal_mulai)->format('d/m/Y') }} -
                                    @endif
                                    {{ \Carbon\Carbon::parse($doc->tanggal_deadline)->format('d/m/Y') }}
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($doc->status) }}">
                                        {{ $doc->status }}
                                    </span>
                                </td>
                                <td>
                                    @if ($doc->sisa_hari < 0)
                                        Terlambat {{ abs($doc->sisa_hari) }} hari
                                    @elseif ($doc->sisa_hari == 0)
                                        Hari ini!
                                    @else
                                        Sisa {{ $doc->sisa_hari }} hari
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('documents.edit', $doc->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
                                        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-4">Belum ada dokumen. Silakan tambahkan dokumen baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
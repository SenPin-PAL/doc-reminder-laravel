<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Dokumen - {{ $document->nama_dokumen }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Mengedit: <strong>{{ $document->nama_dokumen }}</strong></h5>
            </div>
            <div class="card-body">
                <form action="{{ route('documents.update', $document->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                        <input type="text" name="nama_dokumen" id="nama_dokumen" class="form-control @error('nama_dokumen') is-invalid @enderror" 
                               value="{{ old('nama_dokumen', $document->nama_dokumen) }}" required>
                        @error('nama_dokumen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai Berlaku (Opsional)</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                               value="{{ old('tanggal_mulai', $document->tanggal_mulai) }}">
                        @error('tanggal_mulai')
                           <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_deadline" class="form-label">Tanggal Deadline</label>
                        <input type="date" name="tanggal_deadline" id="tanggal_deadline" class="form-control @error('tanggal_deadline') is-invalid @enderror" 
                               value="{{ old('tanggal_deadline', $document->tanggal_deadline) }}" required>
                        @error('tanggal_deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
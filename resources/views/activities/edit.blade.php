<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Aktivitas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Catatan Aktivitas</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('activities.update', $activity->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <textarea name="description" class="form-control" rows="3" required>{{ $activity->description }}</textarea>
                    </div>
                    <a href="{{ route('documents.show', $activity->document_id) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
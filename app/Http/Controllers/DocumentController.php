<?php

namespace App\Http\controllers;

use App\models\document;
use Illuminate\http\request;
use Carbon\carbon;

class DocumentController extends Controller
{
    /**
     * Menampilkan daftar dokumen milik pengguna yang sedang login.
     * Sudah termasuk logika untuk Search & Filter.
     */
    public function index(Request $request)
    {
        // 1. Memulai query HANYA dari dokumen milik pengguna yang sedang login.
        $query = auth()->user()->documents();

        // 2. Menerapkan filter PENCARIAN jika ada input 'search'.
        if ($request->filled('search')) {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }

        // 3. Mengambil data dari database.
        $documents = $query->orderBy('tanggal_deadline', 'asc')->get();

        // 4. Menghitung status untuk setiap dokumen.
        $documentsWithStatus = $documents->map(function ($doc) {
            $deadline = Carbon::parse($doc->tanggal_deadline);
            $sisa_hari = Carbon::now()->startOfDay()->diffInDays($deadline->startOfDay(), false);

            if ($sisa_hari < 0) {
                $doc->status = 'Overdue';
            } elseif ($sisa_hari <= 7) {
                $doc->status = 'Tenggang';
            } else {
                $doc->status = 'Aktif';
            }
            $doc->sisa_hari = $sisa_hari;
            return $doc;
        });
        
        // 5. Menerapkan filter STATUS jika ada input 'status'.
        if ($request->filled('status')) {
            $documentsWithStatus = $documentsWithStatus->filter(function ($document) use ($request) {
                return $document->status == $request->status;
            });
        }

        // 6. Mengirim data akhir ke view.
        return view('documents.index', ['documents' => $documentsWithStatus]);
    }

    /**
     * Menyimpan dokumen baru dan mengaitkannya dengan pengguna yang login.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_deadline' => 'required|date',
        ]);

        // Secara otomatis menambahkan user_id dari pengguna yang sedang login.
        $data['user_id'] = auth()->id();

        Document::create($data);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit dokumen.
     */
    public function edit(Document $document)
    {
        // Pastikan pengguna hanya bisa mengedit dokumen miliknya sendiri.
        abort_if($document->user_id !== auth()->id(), 403);

        return view('documents.edit', ['document' => $document]);
    }

    /**
     * Memperbarui dokumen yang ada di database.
     */
    public function update(Request $request, Document $document)
    {
        // Pastikan pengguna hanya bisa memperbarui dokumen miliknya sendiri.
        abort_if($document->user_id !== auth()->id(), 403);
        
        $data = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_deadline' => 'required|date',
        ]);

        $document->update($data);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diperbarui!');
    }

    /**
     * Menghapus dokumen dari database.
     */
    public function destroy(Document $document)
    {
        // Pastikan pengguna hanya bisa menghapus dokumen miliknya sendiri.
        abort_if($document->user_id !== auth()->id(), 403);
        
        $document->delete();
        
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus!');
    }

    /**
     * Menampilkan halaman detail untuk satu dokumen.
     */
    public function show(Document $document)
    {
        // Pastikan pengguna hanya bisa melihat dokumen miliknya sendiri.
        abort_if($document->user_id !== auth()->id(), 403);

        // Ambil aktivitas dengan paginasi, 5 per halaman.
        $activities = $document->activities()->paginate(5);

        return view('documents.show', [
            'document' => $document,
            'activities' => $activities,
        ]);
    }
}
<?php

namespace App\Http\Controllers; // <-- Perbaikan sintaks

use App\Models\Document; // <-- Perbaikan sintaks
use Illuminate\Http\Request; // <-- Perbaikan sintaks
use Carbon\Carbon; // <-- Perbaikan sintaks
use Illuminate\Support\Facades\Storage; // <-- Tambahan untuk hapus file

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->documents();
        if ($request->filled('search')) {
            $query->where('nama_dokumen', 'like', '%' . $request->search . '%');
        }
        $documents = $query->orderBy('tanggal_deadline', 'asc')->get();
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
        if ($request->filled('status')) {
            $documentsWithStatus = $documentsWithStatus->filter(function ($document) use ($request) {
                return $document->status == $request->status;
            });
        }
        return view('documents.index', ['documents' => $documentsWithStatus]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_deadline' => 'required|date',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);
        $data['user_id'] = auth()->id();
        if ($request->hasFile('document_file')) {
            $data['file_path'] = $request->file('document_file')->store('documents', 'public');
        }
        Document::create($data);
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil ditambahkan!');
    }

    public function show(Document $document)
    {
        abort_if($document->user_id !== auth()->id(), 403);
        $activities = $document->activities()->paginate(5);
        return view('documents.show', [
            'document' => $document,
            'activities' => $activities,
        ]);
    }

    public function edit(Document $document)
    {
        abort_if($document->user_id !== auth()->id(), 403);
        return view('documents.edit', ['document' => $document]);
    }

    public function update(Request $request, Document $document)
    {
        abort_if($document->user_id !== auth()->id(), 403);
        $data = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_deadline' => 'required|date',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // Tambahkan validasi file
        ]);

        // Logika untuk menangani upload file baru saat update
        if ($request->hasFile('document_file')) {
            // Hapus file lama jika ada
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            // Simpan file baru
            $data['file_path'] = $request->file('document_file')->store('documents', 'public');
        }

        $document->update($data);
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diperbarui!');
    }

    public function destroy(Document $document)
    {
        abort_if($document->user_id !== auth()->id(), 403);
        
        // Hapus file dari storage sebelum menghapus record database
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dihapus!');
    }
}
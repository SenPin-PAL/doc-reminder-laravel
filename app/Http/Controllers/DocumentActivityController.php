<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentActivity;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentActivityController extends Controller
{
    use AuthorizesRequests;
    
    public function store(Request $request, Document $document)
    {
        $this->authorize('update', $document);
        $request->validate(['description' => 'required|string']);

        $document->activities()->create([
            'user_id' => auth()->id(),
            'description' => $request->description,
        ]);
        return back()->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit aktivitas.
     */
    public function edit(DocumentActivity $activity)
    {
        $this->authorize('update', $activity); // Cek hak akses
        return view('activities.edit', ['activity' => $activity]);
    }

    /**
     * Memperbarui aktivitas.
     */
    public function update(Request $request, DocumentActivity $activity)
    {
        $this->authorize('update', $activity); // Cek hak akses
        $request->validate(['description' => 'required|string']);
        $activity->update(['description' => $request->description]);
        return redirect()->route('documents.show', $activity->document_id)->with('success', 'Aktivitas berhasil diperbarui!');
    }

    /**
     * Menghapus aktivitas.
     */
    public function destroy(DocumentActivity $activity)
    {
        $this->authorize('delete', $activity); // Cek hak akses
        $documentId = $activity->document_id;
        $activity->delete();
        return redirect()->route('documents.show', $documentId)->with('success', 'Aktivitas berhasil dihapus!');
    }
}
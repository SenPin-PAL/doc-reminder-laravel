<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DocumentActivityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Route untuk Pengguna Tamu (Guest) ---
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});


// --- Route untuk Pengguna yang Sudah Login ---
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('documents.index');
    });

    // Route untuk Dokumen (CRUD & show)
    Route::resource('documents', DocumentController::class);

    // Route untuk Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // --- Route untuk Aktivitas Dokumen ---
    // Menyimpan aktivitas baru
    Route::post('/documents/{document}/activities', [DocumentActivityController::class, 'store'])->name('documents.activities.store');
    // Menampilkan form edit aktivitas
    Route::get('/activities/{activity}/edit', [DocumentActivityController::class, 'edit'])->name('activities.edit');
    // Memproses update aktivitas
    Route::put('/activities/{activity}', [DocumentActivityController::class, 'update'])->name('activities.update');
    // Menghapus aktivitas
    Route::delete('/activities/{activity}', [DocumentActivityController::class, 'destroy'])->name('activities.destroy');
});
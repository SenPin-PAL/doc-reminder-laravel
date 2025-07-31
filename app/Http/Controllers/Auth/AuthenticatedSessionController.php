<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman/view login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Menerima dan memproses percobaan login dari form.
     */
    public function store(Request $request)
    {
        // Validasi input email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Mencoba untuk mengautentikasi pengguna
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerate session untuk keamanan

            // Arahkan ke halaman dokumen jika berhasil
            return redirect()->intended(route('documents.index'));
        }

        // Jika gagal, kembali ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Menghancurkan sesi (logout).
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // Arahkan ke halaman utama setelah logout
    }
}
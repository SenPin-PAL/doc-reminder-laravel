<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman/view registrasi.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Menerima dan memvalidasi data dari form registrasi.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password wajib di-hash!
        ]);

        // 3. Login-kan user yang baru dibuat
        Auth::login($user);

        // 4. Arahkan ke halaman utama setelah berhasil register
        return redirect()->route('documents.index');
    }
}
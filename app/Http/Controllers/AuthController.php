<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman Register
    public function showRegister() {
        return view('auth.register');
    }

    // Proses pendaftaran pengguna
    public function register(Request $request) {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            'max:255',
            'unique:users,email',
            'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.ac\.id$/i',
        ],
        'phone' => 'required|string|max:20',
        'password' => 'required|string|min:8|confirmed',

    ], [
        'email.regex' => 'Email harus menggunakan domain kampus .ac.id.',
        'email.unique' => 'Email sudah terdaftar.',
        'phone.required' => 'Nomor WhatsApp wajib diisi.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password.min' => 'Password minimal 8 karakter.',
    ]);

    $user = User::create($validated);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect('/catalog')->with(
        'success',
        'Registrasi berhasil! Selamat datang di OperinSini.'
    );

    }

    //  halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // proses Login pengguna
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.ac\.id$/i',
            ],
            'password' => 'required|string',
        ], [
            'email.regex' => 'Gunakan email kampus dengan domain .ac.id.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/catalog');
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password yang dimasukkan salah.',
            ])
            ->onlyInput('email');
    }

    // proses Logout pengguna
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Kamu berhasil logout.');
    }
}

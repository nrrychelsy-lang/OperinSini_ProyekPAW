<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\EmailKampus;
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
        $data = $request->validate ([
        'name' => ['required', 'string', 'max:100'],
        'email' => [
            'required',
            'email',
            'max:150',
            'unique:users,email',
            new EmailKampus
        ],
        'phone' => ['required', 'string', 'max:20'],
        'password' => ['required', 'string', 'min:8'],
    ], [
        'email.unique' => 'Email sudah terdaftar.',
        'phone.regex' => 'Nomor WhatsApp tidak valid (contoh: 081234567890).',
        'password.min' => 'Password minimal 8 karakter.',
    ]);

    $user = User::create ([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'],
            'password' => $data['password'],
            'role' => 'mahasiswa',            // ditetapkan sistem, bukan dari form
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('catalog.index')->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    //  halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // proses Login pengguna
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required',  'email', new EmailKampus],
            'password' => ['required'],
        ]); 
        $ok = Auth::attempt(
            ['email' => strtolower($data['email']), 'password' => $data['password']],
            $request->boolean('remember')
        );

        if (! $ok) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->route(Auth::user()->homeRoute());
    }

    // proses Logout pengguna
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('/login');
    }
}
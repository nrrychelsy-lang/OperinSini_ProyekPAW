<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\EmailKampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', new EmailKampus],
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

        // Role dideteksi otomatis: admin -> dashboard admin, mahasiswa -> katalog
        return redirect()->route(Auth::user()->homeRoute());
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email', new EmailKampus],
            'phone' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
            'phone.regex' => 'Nomor WhatsApp tidak valid (contoh: 081234567890).',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = User::create([
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

@extends('layouts.guest')
@section('title', 'Masuk')
@section('content')
<div class="auth-card">
    <div>
        <h2 class="text-xl font-extrabold text-slate-900">
            Masuk ke OperinSini
        </h2>
        <p class="text-sm text-slate-500">
            Silakan masuk menggunakan email kampus.
        </p>
    </div>

    @include('partials.alert')

    <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="lbl">Email Mahasiswa</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                class="input"
                placeholder="nama@student.kampus.ac.id"
                required
            >
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="lbl">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                class="input"
                placeholder="Masukkan password"
                required
            >
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>
        </div>

        <button type="submit" class="btn-primary w-full">
            Masuk
        </button>
    </form>

    <div class="auth-footer">
        <p>
            Belum memiliki akun?
            <a href="{{ route('register') }}">Daftar di sini</a>
        </p>
    </div>
</div>
@endsection

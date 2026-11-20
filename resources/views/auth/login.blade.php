
@extends('layouts.guest')

@section('title', 'Masuk - OperinSini')

@section('content')
<div class="w-full max-w-sm mx-auto space-y-5">

    <!-- Logo dan Tagline -->
    <div class="text-center space-y-1">
        <h1 class="text-2xl font-extrabold text-blue-700">
            OperinSini
        </h1>
        <p class="text-xs text-slate-400">
            Temukan dan berbagi barang antar mahasiswa
        </p>
    </div>

    <!-- Card Login -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-5">

        <!-- Menu Login dan Register -->
        <div class="grid grid-cols-2 gap-1 rounded-lg bg-slate-100 p-1">
            <a href="{{ route('login') }}"
               class="rounded-md bg-white py-2 text-center text-sm font-semibold text-blue-700 shadow-sm">
                Masuk
            </a>

            <a href="{{ route('register') }}"
               class="rounded-md py-2 text-center text-sm text-slate-500 hover:text-blue-700">
                Daftar Akun
            </a>
        </div>

        <!-- Judul -->
        <div>
            <h2 class="text-lg font-bold text-slate-800">
                Masuk Akun
            </h2>
            <p class="text-xs text-slate-500">
                Gunakan email kampus (.ac.id) kamu.
            </p>
        </div>

        @include('partials.alert')

        <!-- Form Login -->
        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf

            <!-- Email Mahasiswa -->
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

            <!-- Password -->
            <div>
                <label for="password" class="lbl">Password</label>

                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="input pr-12"
                        placeholder="Masukkan password"
                        required
                    >

                    <button
                        type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-blue-600"
                        aria-label="Tampilkan password"
                    >
                        <svg class="w-5 h-5" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ingat Saya -->
            <div>
                <label class="flex items-center gap-2 text-xs text-slate-500">
                    <input type="checkbox" name="remember" value="1">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <!-- Tombol Login -->
            <button type="submit" class="btn-primary w-full">
                Masuk Sekarang
            </button>
        </form>
    </div>
</div>

<!-- Tampilkan/Sembunyikan Password -->
<script>
    function togglePassword(id, button) {
        const input = document.getElementById(id);

        if (input.type === 'password') {
            input.type = 'text';

            button.innerHTML = `
                <svg class="w-5 h-5" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M9.9 5.2A10.8 10.8 0 0112 5c6.25 0 9.75 7 9.75 7a16 16 0 01-3.1 3.9M6.2 6.2C3.6 8 2.25 12 2.25 12s3.5 7 9.75 7c1.3 0 2.5-.3 3.5-.8"/>
                </svg>
            `;

            button.setAttribute('aria-label', 'Sembunyikan password');

        } else {
            input.type = 'password';

            button.innerHTML = `
                <svg class="w-5 h-5" fill="none"
                     stroke="currentColor" viewBox="0 0 24 24"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 12s3.5-7 9.75-7 9.75 7 9.75 7-3.5 7-9.75 7-9.75-7-9.75-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            `;

            button.setAttribute('aria-label', 'Tampilkan password');
        }
    }
</script>
@endsection

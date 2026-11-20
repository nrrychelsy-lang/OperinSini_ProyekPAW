@extends('layouts.guest')
@section('title', 'Daftar Akun')
@section('content')

<div class="auth-card">

    <div>
        <h2 class="text-xl font-extrabold text-slate-900">Daftar Akun</h2>
        <p class="text-sm text-slate-500">
            Khusus mahasiswa dengan email kampus berdomain .ac.id.
        </p>
    </div>

    @include('partials.alert')

    <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="lbl">Nama Lengkap</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                class="input"
                placeholder="Masukkan nama lengkap"
                required
            >
            @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

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
            <label for="phone" class="lbl">Nomor WhatsApp</label>
            <input
                id="phone"
                name="phone"
                type="tel"
                value="{{ old('phone') }}"
                class="input"
                placeholder="081234567890"
                required
            >
            <p class="mt-1 text-xs text-slate-500">
                Digunakan untuk komunikasi serah terima barang.
            </p>
            @error('phone')
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
                placeholder="Minimal 8 karakter"
                required
            >
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="lbl">
                Konfirmasi Password
            </label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="input"
                placeholder="Masukkan ulang password"
                required
            >
        </div>

        <div>
            <label for="role" class="lbl">Daftar Sebagai</label>
            <select id="role" name="role" class="input" required>
                <option value="">Pilih peran pengguna</option>
                <option value="donatur" {{ old('role') == 'donatur' ? 'selected' : '' }}>
                    Donatur
                </option>
                <option value="penerima" {{ old('role') == 'penerima' ? 'selected' : '' }}>
                    Penerima
                </option>
            </select>
            @error('role')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-primary w-full">
            Daftar Sekarang
        </button>
    </form>

    <div class="auth-footer">
        <p>
            Sudah memiliki akun?
            <a href="{{ route('login') }}">Masuk di sini</a>
        </p>
    </div>

</div>

@endsection

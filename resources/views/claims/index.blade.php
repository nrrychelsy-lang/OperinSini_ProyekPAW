@extends('layouts.app')
@section('title', 'Riwayat Klaim')
@section('content')
<div class="mb-5">
    <h1 class="text-2xl font-extrabold text-slate-900">Riwayat Pengajuan Klaim</h1>
    <p class="text-sm text-slate-500">Pantau status permohonan barang yang Anda ajukan.</p>
</div>

<div class="mb-5 grid gap-4 sm:grid-cols-3">
    <div class="card p-4"><p class="text-sm text-slate-500">Pending</p><p class="text-3xl font-extrabold text-amber-600">{{ $counts['pending'] }}</p></div>
    <div class="card p-4"><p class="text-sm text-slate-500">Diterima</p><p class="text-3xl font-extrabold text-emerald-600">{{ $counts['diterima'] }}</p></div>
    <div class="card p-4"><p class="text-sm text-slate-500">Ditolak</p><p class="text-3xl font-extrabold text-red-600">{{ $counts['ditolak'] }}</p></div>
</div>

<form method="GET" class="card mb-4 flex flex-wrap gap-2 p-3">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama barang atau alasan..." aria-label="Cari" class="input min-w-[200px] flex-1">
    <select name="status" aria-label="Filter status" class="input w-auto">
        <option value="">Semua status</option>
        @foreach(['pending' => 'Pending', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $v => $l)
            <option value="{{ $v }}" @selected(request('status') === $v)>{{ $l }}</option>
        @endforeach
    </select>
    <button class="btn-primary">Cari</button>
    @if(request()->hasAny(['q', 'status']))<a href="{{ route('claims.index') }}" class="btn-light">Reset</a>@endif
</form>

<div class="space-y-3">
@forelse($claims as $c)
    <article class="card flex flex-wrap items-start gap-4 p-4">
        @include('partials.thumb', ['item' => $c->item, 'class' => 'h-16 w-16'])
        <div class="min-w-[220px] flex-1">
            <a href="{{ route('catalog.show', $c->item) }}" class="font-bold text-slate-900 hover:text-brand-700">{{ $c->item->name }}</a>
            <p class="text-xs text-slate-500">Donatur: {{ $c->item->donor->name }} - Diajukan {{ $c->created_at->translatedFormat('d M Y') }}</p>
            <p class="mt-2 rounded-lg bg-slate-50 p-2 text-sm text-slate-700">{{ $c->reason }}</p>
        </div>
        <div class="flex flex-col items-end gap-2">
            @include('partials.badge', ['s' => $c->status])
            <div class="flex flex-wrap justify-end gap-1.5">
            @if($c->status === 'pending')
                <button type="button" data-open="edit{{ $c->id }}" class="btn-light btn-sm">Edit</button>
                <form method="POST" action="{{ route('claims.destroy', $c) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">@csrf @method('DELETE')
                    <button class="btn-danger btn-sm">Batalkan</button></form>
            @elseif($c->status === 'diterima')
                @if($wa = $c->item->donor->whatsappLink('Halo ' . $c->item->donor->name . ', klaim saya untuk "' . $c->item->name . '" di OperinSini sudah diterima. Kapan bisa saya ambil?'))
                    <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn-warn btn-sm">Hubungi Donatur</a>
                @else
                    <span class="text-xs text-slate-500">Donatur belum mengisi nomor WhatsApp.</span>
                @endif
            @endif
            </div>
        </div>
    </article>

    @if($c->status === 'pending')
    <dialog id="edit{{ $c->id }}" class="w-full max-w-md rounded-2xl p-0 backdrop:bg-slate-900/50">
        <form method="POST" action="{{ route('claims.update', $c) }}" class="p-5">@csrf @method('PUT')
            <h2 class="text-lg font-bold">Edit alasan pengajuan</h2>
            <div class="mt-3 flex items-center justify-between"><label for="r{{ $c->id }}" class="lbl">Alasan</label><span id="wc{{ $c->id }}" class="text-xs text-slate-500"></span></div>
            <textarea id="r{{ $c->id }}" name="reason" rows="5" class="input" data-words="wc{{ $c->id }}" data-max="500" required>{{ $c->reason }}</textarea>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" data-close class="btn-light">Batal</button>
                <button class="btn-primary">Simpan</button>
            </div>
        </form>
    </dialog>
    @endif
@empty
    <div class="card p-10 text-center text-slate-500">Belum ada pengajuan. <a href="{{ route('catalog.index') }}" class="font-semibold text-brand-700 underline">Cari barang di katalog</a>.</div>
@endforelse
</div>

<div class="mt-4">{{ $claims->links() }}</div>
<p class="mt-3 rounded-lg bg-brand-50 p-3 text-xs text-brand-900"><b>Ketentuan:</b> Edit alasan dan Batalkan hanya aktif selama donatur belum menentukan pilihan (status Pending).</p>
@endsection

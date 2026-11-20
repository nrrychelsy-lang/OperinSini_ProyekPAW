@props([
    'src' => null,
    'alt' => 'Foto barang',
])

<div {{ $attributes->merge(['class' => 'item-thumb']) }}>
    @if ($src)
        <img src="{{ $src }}" alt="{{ $alt }}">
    @else
        <div class="item-thumb-placeholder">
            Foto belum tersedia
        </div>
    @endif
</div>

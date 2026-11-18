@props(['status'])

@php
    $class = match ($status) {
        'Pending' => 'badge-pending',
        'Diterima' => 'badge-accepted',
        'Ditolak' => 'badge-rejected',
        default => 'badge-default',
    };
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $class]) }}>
    {{ $status }}
</span>

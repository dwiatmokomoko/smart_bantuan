{{-- Status Badge Component for SmartPBI --}}
@props(['status' => null, 'value' => null])

@php
    $statusMap = [
        'layak' => ['class' => 'badge-layak', 'icon' => '✓', 'text' => 'Layak'],
        'tidak_layak' => ['class' => 'badge-tidak-layak', 'icon' => '✗', 'text' => 'Tidak Layak'],
        'diupayakan' => ['class' => 'badge-diupayakan', 'icon' => '⚠', 'text' => 'Diupayakan'],
    ];
    
    $displayStatus = $statusMap[$status] ?? $statusMap['tidak_layak'];
@endphp

<span class="{{ $displayStatus['class'] }}" {{ $attributes }}>
    {{ $displayStatus['icon'] }} {{ $displayStatus['text'] }}
</span>

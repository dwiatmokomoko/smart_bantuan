{{-- SmartPBI Header Component --}}
@props(['title' => 'SmartPBI: BPJS PBI', 'subtitle' => 'Sistem Pendukung Keputusan untuk Penerima Bantuan Iuran BPJS'])

<div class="smartpbi-header" {{ $attributes }}>
    <h1>{{ $title }}</h1>
    @if($subtitle)
        <p>{{ $subtitle }}</p>
    @endif
    {{ $slot }}
</div>

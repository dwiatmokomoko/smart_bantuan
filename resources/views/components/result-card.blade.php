{{-- Result Card Component for SmartPBI Penilaian --}}
@props(['peserta' => null, 'status' => null, 'score' => null, 'ticket' => null])

@php
    $statusClass = match($status) {
        'layak' => 'layak',
        'tidak_layak' => 'tidak-layak',
        'diupayakan' => 'diupayakan',
        default => 'tidak-layak'
    };
@endphp

<div class="card smartpbi-result-card {{ $statusClass }}" {{ $attributes }}>
    <div class="card-header">
        <h5 class="mb-0">Hasil Penilaian</h5>
    </div>
    <div class="card-body">
        @if($peserta)
            <h5 class="card-title">{{ $peserta->nama ?? 'N/A' }}</h5>
            <p class="card-text">
                <strong>NIK:</strong> {{ $peserta->nik ?? 'N/A' }}
            </p>
            @if($ticket)
                <p class="card-text">
                    <strong>Ticket:</strong> <code>{{ $ticket }}</code>
                </p>
            @endif
        @endif
        
        @if($status)
            <p class="card-text">
                <strong>Status:</strong> 
                <x-status-badge :status="$status" />
            </p>
        @endif
        
        @if($score !== null)
            <p class="card-text">
                <strong>Skor Kelayakan:</strong> {{ number_format($score, 2) }}
            </p>
            <div class="progress mt-2">
                <div class="progress-bar" style="width: {{ $score * 100 }}%"></div>
            </div>
        @endif
        
        {{ $slot }}
    </div>
</div>

{{-- SmartPBI BPJS Theme Helper Components --}}

{{-- Status Badge Component --}}
@component('components.status-badge', ['status' => $status ?? null])
@endcomponent

{{-- Stat Box Component --}}
@component('components.stat-box', ['value' => $value ?? 0, 'label' => $label ?? ''])
@endcomponent

{{-- Result Card Component --}}
@component('components.result-card', ['peserta' => $peserta ?? null])
@endcomponent

{{-- SmartPBI Header Component --}}
@component('components.smartpbi-header', ['title' => $title ?? 'SmartPBI: BPJS PBI', 'subtitle' => $subtitle ?? ''])
@endcomponent

{{-- Alert Component --}}
@component('components.alert', ['type' => $type ?? 'info', 'message' => $message ?? ''])
@endcomponent

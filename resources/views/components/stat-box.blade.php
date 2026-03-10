{{-- Stat Box Component for SmartPBI Dashboard --}}
@props(['value' => 0, 'label' => '', 'icon' => null])

<div class="stat-box" {{ $attributes }}>
    @if($icon)
        <div class="stat-icon mb-2">
            <i class="{{ $icon }}"></i>
        </div>
    @endif
    <div class="stat-value">{{ $value }}</div>
    <div class="stat-label">{{ $label }}</div>
</div>

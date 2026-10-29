@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[color:var(--event-gold)]']) }}>
        {{ $status }}
    </div>
@endif

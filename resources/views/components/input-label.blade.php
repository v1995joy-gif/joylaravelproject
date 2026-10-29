@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-[color:var(--event-ink)]']) }}>
    {{ $value ?? $slot }}
</label>

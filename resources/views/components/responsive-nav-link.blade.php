@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[color:var(--event-gold)] text-start text-base font-medium text-[color:var(--event-ink)] bg-[color:var(--neo-hover)] focus:outline-none focus:text-[color:var(--event-ink)] focus:bg-[color:var(--neo-hover)] focus:border-[color:var(--event-gold)] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-[color:var(--event-muted)] hover:text-[color:var(--event-ink)] hover:bg-[color:var(--neo-hover)] hover:border-[#c9a35f] focus:outline-none focus:text-[color:var(--event-ink)] focus:bg-[color:var(--neo-hover)] focus:border-[#c9a35f] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

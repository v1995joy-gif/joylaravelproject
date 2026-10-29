@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[color:var(--event-gold)] text-sm font-medium leading-5 text-[color:var(--event-ink)] focus:outline-none focus:border-[color:var(--event-gold)] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-[color:var(--event-muted)] hover:text-[color:var(--event-ink)] hover:border-[#c9a35f] focus:outline-none focus:text-[color:var(--event-ink)] focus:border-[#c9a35f] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

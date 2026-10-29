@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'ea bg-[color:var(--neo-panel)] border-none rounded-xl shadow-[var(--neo-inset-sm)] text-[color:var(--event-ink)] placeholder-[color:var(--event-faint)] focus:ring-2 focus:ring-[color:var(--event-gold)]']) }}>

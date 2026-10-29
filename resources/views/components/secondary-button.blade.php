<button {{ $attributes->merge(['type' => 'button', 'class' => 'ea inline-flex items-center px-4 py-2 bg-[color:var(--neo-panel)] border-none rounded-xl font-semibold text-xs text-[color:var(--event-ink)] uppercase tracking-[0.14em] shadow-[var(--neo-raise-sm)] hover:text-[color:var(--event-gold)] active:shadow-[var(--neo-inset-sm)] focus:outline-none focus:ring-2 focus:ring-[color:var(--event-gold)] focus:ring-offset-2 focus:ring-offset-[color:var(--event-cream)] disabled:opacity-40 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

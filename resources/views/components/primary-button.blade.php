<button {{ $attributes->merge(['type' => 'submit', 'class' => 'ea inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-br from-[#c9a35f] to-[#ed3d81] border-none rounded-xl font-semibold text-xs text-white uppercase tracking-[0.14em] shadow-[var(--neo-raise-sm)] hover:brightness-105 active:shadow-[var(--neo-inset-sm)] focus:outline-none focus:ring-2 focus:ring-[color:var(--event-gold)] focus:ring-offset-2 focus:ring-offset-[color:var(--event-cream)] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

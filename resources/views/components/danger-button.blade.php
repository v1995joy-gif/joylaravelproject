<button {{ $attributes->merge(['type' => 'submit', 'class' => 'ea inline-flex items-center px-4 py-2 bg-[#a83232] border-none rounded-xl font-semibold text-xs text-white uppercase tracking-[0.14em] shadow-[var(--neo-raise-sm)] hover:bg-[#8a2828] active:shadow-[var(--neo-inset-sm)] focus:outline-none focus:ring-2 focus:ring-[color:#a83232] focus:ring-offset-2 focus:ring-offset-[color:var(--event-cream)] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

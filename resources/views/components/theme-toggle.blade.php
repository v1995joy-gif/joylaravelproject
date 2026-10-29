<button type="button" {{ $attributes->merge(['class' => 'theme-toggle']) }} onclick="eaToggleTheme()" aria-label="Toggle dark mode" title="Toggle dark mode">
    <span class="theme-toggle-icon theme-toggle-moon"><x-lucide-moon width="16" height="16" aria-hidden="true" /></span>
    <span class="theme-toggle-icon theme-toggle-sun"><x-lucide-sun width="16" height="16" aria-hidden="true" /></span>
</button>

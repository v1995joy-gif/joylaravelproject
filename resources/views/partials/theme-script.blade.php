<script>
    if (localStorage.getItem('ea-theme') === 'dark') {
        document.documentElement.classList.add('dark');
    }
    function eaToggleTheme() {
        var dark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('ea-theme', dark ? 'dark' : 'light');
    }
</script>

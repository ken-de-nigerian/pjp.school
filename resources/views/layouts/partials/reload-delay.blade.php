{{-- After custom.js; before @stack('scripts'). Sets global delay for post-AJAX reload/redirect. --}}
<script>
    window.RELOAD_DELAY_MS = Math.max(0, Number(@json((int) config('school.ajax_reload_delay_ms', 2800))) || 2800);
</script>

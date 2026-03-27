<script>
    @if (session('success'))
        iziToast.success({
            message: "{{ session('success') }}",
            position: 'topRight',
            timeout: 5000,
            resetOnHover: true,
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX'
        });
    @endif

    @if (session('info'))
        iziToast.info({
            message: "{{ session('info') }}",
            position: 'topRight',
            timeout: 5000,
            resetOnHover: true,
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX'
        });
    @endif

    @if (session('warning'))
        iziToast.warning({
            message: "{{ session('warning') }}",
            position: 'topRight',
            timeout: 5000,
            resetOnHover: true,
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX'
        });
    @endif

    @if (session('error'))
        iziToast.error({
            message: "{{ session('error') }}",
            position: 'topRight',
            timeout: 5000,
            resetOnHover: true,
            transitionIn: 'flipInX',
            transitionOut: 'flipOutX'
        });
    @endif
</script>

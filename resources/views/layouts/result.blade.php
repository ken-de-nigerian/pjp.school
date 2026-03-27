<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover">

    @include('layouts.partials.site-logo-styles')

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/' . config('school.logo_file', 'logo/logo.jpg')) }}">

    <!-- SEO Meta Tags -->
    <title>{{ ($title ?? 'Results') . ' | ' . (site_settings()?->name ?? config('app.name')) }}</title>
    <meta name="description"
          content="Pope John Paul II Model Secondary School — a co-educational Catholic school in Ihitte, Imo State, offering holistic, faith-based education from JSS1 to SS3.">
    <meta name="keywords"
          content="Pope John Paul II Model Secondary School, PJP school, secondary school Imo State, Catholic school Ihitte, Ezinihitte Mbaise school, WAEC school Imo, PJP Great">
    <meta name="robots" content="index,follow">
    <meta name="author" content="Pope John Paul II Model Secondary School">
    <meta name="geo.region" content="NG-IM">
    <meta name="geo.placename" content="Umunagbor Amagbor Ihitte, Ezinihitte Mbaise, Imo State">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title"
          content="{{ ($title ?? 'Results') . ' | ' . (site_settings()?->name ?? config('app.name')) }}">
    <meta property="og:description"
          content="A leading Catholic secondary school in Imo State — rooted in faith, driven by excellence, and committed to the formation of the whole child.">
    <meta property="og:image" content="{{ asset('storage/' . config('school.logo_file', 'logo/logo.jpg')) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Pope John Paul II Model Secondary School">
    <meta property="og:locale" content="en_NG">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title"
          content="{{ ($title ?? 'Results') . ' | ' . (site_settings()?->name ?? config('app.name')) }}">
    <meta name="twitter:description"
          content="A leading Catholic secondary school in Imo State — rooted in faith, driven by excellence, and committed to the formation of the whole child.">
    <meta name="twitter:image" content="{{ asset('storage/' . config('school.logo_file', 'logo/logo.jpg')) }}">
    @include('layouts.partials.font-system')

    @unless(app()->runningUnitTests())
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endunless

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/iziToast.min.css') }}">

    @stack('styles')
</head>
<body class="app-portal font-sans">
<!-- Main -->
@yield('content')
<!-- Main -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>

<script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

@stack('scripts')
@include('layouts.partials.message')

<script>
    const iziToastSettings = {
        position: "topRight",
        timeout: 5000,
        resetOnHover: true,
        transitionIn: "flipInX",
        transitionOut: "flipOutX"
    };
</script>
</body>
</html>

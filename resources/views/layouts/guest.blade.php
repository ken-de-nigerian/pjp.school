<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- favicon -->
        <link rel="shortcut icon" href="{{ asset('storage/' . config('school.logo_file', 'logo/logo.jpg')) }}">

        <!-- SEO Meta Tags -->
        <title>{{ ($title ?? 'Home') . ' | ' . (site_settings()?->name ?? config('app.name')) }}</title>
        <meta name="description" content="Pope John Paul II Model Secondary School — a co-educational Catholic school in Ihitte, Imo State, offering holistic, faith-based education from JSS1 to SS3.">
        <meta name="keywords" content="Pope John Paul II Model Secondary School, PJP school, secondary school Imo State, Catholic school Ihitte, Ezinihitte Mbaise school, WAEC school Imo, PJP Great">
        <meta name="robots" content="index,follow">
        <meta name="author" content="Pope John Paul II Model Secondary School">
        <meta name="geo.region" content="NG-IM">
        <meta name="geo.placename" content="Umunagbor Amagbor Ihitte, Ezinihitte Mbaise, Imo State">

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ ($title ?? 'Home') . ' | ' . (site_settings()?->name ?? config('app.name')) }}">
        <meta property="og:description" content="A leading Catholic secondary school in Imo State — rooted in faith, driven by excellence, and committed to the formation of the whole child.">
        <meta property="og:image" content="{{ asset('storage/' . config('school.logo_file', 'logo/logo.jpg')) }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="Pope John Paul II Model Secondary School">
        <meta property="og:locale" content="en_NG">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ ($title ?? 'Home') . ' | ' . (site_settings()?->name ?? config('app.name')) }}">
        <meta name="twitter:description" content="A leading Catholic secondary school in Imo State — rooted in faith, driven by excellence, and committed to the formation of the whole child.">
        <meta name="twitter:image" content="{{ asset('storage/' . config('school.logo_file', 'logo/logo.jpg')) }}">

        @include('layouts.partials.font-system')

        @unless(app()->runningUnitTests())
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endunless

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('assets/css/guest-header-nav.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/iziToast.min.css') }}">

        <style>
            :root {
                --font-primary: 'Inter', sans-serif;
                --font-heading: 'Poppins', sans-serif;
            }
            html, body {
                font-family: var(--font-primary), serif;
            }
            h1, h2, h3, h4, h5, h6,
            .font-heading,
            .font-serif {
                font-family: var(--font-heading), serif !important;
            }
            .form-error {
                font-size: 0.8125rem;
                color: #dc2626;
                margin: 0.375rem 0 0 0;
            }
        </style>

        @include('layouts.partials.site-logo-styles')
        @stack('styles')
    </head>

    <body class="guest-site font-sans">
        <div class="w-full min-h-screen bg-white text-gray-800 font-sans selection:bg-educave-800 selection:text-white">
            @include('layouts.partials.page-header')

            @yield('content')

            @include('layouts.partials.page-footer')
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>

        <script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>

        @stack('scripts')
        @include('partials.message')

        <script>
            const iziToastSettings = {
                position: "topRight",
                timeout: 5000,
                resetOnHover: true,
                transitionIn: "flipInX",
                transitionOut: "flipOutX"
            };
        </script>

        <script>
            // Intersection Observer for Scroll Animations
            document.addEventListener("DOMContentLoaded", () => {
                const observerOptions = {
                    root: null,
                    rootMargin: "0px",
                    threshold: 0.1, // Trigger when 10% of the element is visible
                };

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("visible");
                            observer.unobserve(entry.target); // Only animate once
                        }
                    });
                }, observerOptions);

                // Observe elements
                const observeElements = () => {
                    const elements = document.querySelectorAll(
                        ".scroll-fade-up, .scroll-reveal-image, .scroll-scale-in"
                    );
                    elements.forEach((el) => observer.observe(el));
                };

                // Run initially and observe DOM changes (for SPA navigation)
                observeElements();

                const mutationObserver = new MutationObserver(observeElements);
                mutationObserver.observe(document.body, { childList: true, subtree: true });

                // Guest header: mobile menu modal (same pattern as admin/teacher overlay)
                const menuToggle = document.getElementById("mobile-menu-toggle");
                const guestOverlay = document.getElementById("guest-mobile-menu-overlay");
                const iconOpen = menuToggle?.querySelector("[data-icon-open]");
                const iconClose = menuToggle?.querySelector("[data-icon-close]");

                const setGuestMobileMenuIcons = (open) => {
                    iconOpen?.classList.toggle("hidden", open);
                    iconClose?.classList.toggle("hidden", !open);
                };

                window.closeGuestMobileMenu = function () {
                    if (!guestOverlay || !menuToggle) return;
                    guestOverlay.classList.remove("is-open");
                    guestOverlay.setAttribute("aria-hidden", "true");
                    menuToggle.setAttribute("aria-expanded", "false");
                    menuToggle.setAttribute("aria-label", "Open menu");
                    setGuestMobileMenuIcons(false);
                    document.body.classList.remove("overflow-hidden");
                    guestOverlay.querySelectorAll(".mobile-menu-dropdown-content").forEach((el) => {
                        el.classList.add("hidden");
                    });
                    guestOverlay.querySelectorAll(".mobile-menu-dropdown").forEach((el) => {
                        el.classList.remove("active");
                    });
                    guestOverlay.querySelectorAll(".mobile-dropdown-arrow").forEach((el) => {
                        el.style.transform = "rotate(0deg)";
                    });
                };

                window.openGuestMobileMenu = function () {
                    if (!guestOverlay || !menuToggle) return;
                    if (window.matchMedia("(min-width: 1024px)").matches) return;
                    guestOverlay.classList.add("is-open");
                    guestOverlay.setAttribute("aria-hidden", "false");
                    menuToggle.setAttribute("aria-expanded", "true");
                    menuToggle.setAttribute("aria-label", "Close menu");
                    setGuestMobileMenuIcons(true);
                    document.body.classList.add("overflow-hidden");
                };

                menuToggle?.addEventListener("click", () => {
                    if (!guestOverlay) return;
                    if (guestOverlay.classList.contains("is-open")) {
                        window.closeGuestMobileMenu();
                    } else {
                        window.openGuestMobileMenu();
                    }
                });

                document.addEventListener("keydown", (e) => {
                    if (e.key === "Escape" && guestOverlay?.classList.contains("is-open")) {
                        window.closeGuestMobileMenu();
                    }
                });

                window.addEventListener("resize", () => {
                    if (window.matchMedia("(min-width: 1024px)").matches) {
                        window.closeGuestMobileMenu();
                    }
                });
            });
        </script>
    </body>
</html>

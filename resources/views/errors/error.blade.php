<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- title -->
        <title>{{ $title ?? config('app.name') }} — {{ config('app.name') }}</title>

        @unless(app()->runningUnitTests())
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endunless

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Error page styles (MD3-aligned, built on app.css tokens) -->
        <style>
            body {
                font-family: 'Roboto', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                background: var(--surface);
                color: var(--on-surface);
            }

            .accent-color {
                color: var(--primary);
            }

            .accent-bg {
                background: var(--primary);
                color: var(--on-primary);
            }

            .accent-border {
                border-color: var(--primary);
            }

            .error-code {
                background: linear-gradient(135deg, var(--primary) 0%, #dc2626 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Icon background colors (soft containers) */
            .icon-400,
            .icon-401,
            .icon-403,
            .icon-404,
            .icon-419,
            .icon-422,
            .icon-429,
            .icon-500 {
                background: var(--surface-container);
            }

            /* Responsive typography */
            @media (max-width: 768px) {
                .error-code {
                    font-size: 6rem;
                }

                .error-title {
                    font-size: 1.5rem;
                }

                .error-description {
                    font-size: 0.875rem;
                }
            }

            /* Maintenance Mode Specific Styling */
            .maintenance-end {
                color: var(--on-surface-variant);
                font-weight: 500;
                font-size: 0.9rem;
                background: var(--primary-container);
                padding: 0.5rem 1rem;
                border-radius: 999px;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }
        </style>
    </head>
    <body>
    <div class="min-h-screen flex items-center justify-center p-4 md:p-8">
        <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
            <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                <div class="text-center">
            <!-- Error Code -->
            <div class="mb-8">
                @php
                    $errorCode = $code ?? 500;
                    $errorClass = "error-$errorCode";
                @endphp

                <h1 class="error-code text-8xl md:text-9xl font-black {{ $errorClass }}">
                    {{ $errorCode }}
                </h1>
            </div>

            <!-- Error Icon -->
            <div class="mb-6">
                @php
                    // Use the same icon system as the rest of the app (Font Awesome)
                    $icon = match($errorCode) {
                        400 => 'fas fa-exclamation-circle',
                        401 => 'fas fa-user-lock',
                        403 => 'fas fa-lock',
                        404 => 'fas fa-search',
                        419 => 'fas fa-shield-alt',
                        422 => 'fas fa-file-alt',
                        429 => 'fas fa-clock',
                        500 => 'fas fa-server',
                        default => 'fas fa-exclamation-triangle'
                    };
                    $iconBg = "icon-$errorCode";
                @endphp

                <div class="inline-flex items-center justify-center p-5 {{ $iconBg }} rounded-full shadow-sm">
                    <i class="{{ $icon }} text-2xl md:text-3xl accent-color" aria-hidden="true"></i>
                </div>
            </div>

            <!-- Error Title -->
            <h2 class="error-title text-2xl md:text-3xl font-bold text-gray-800 mb-4">
                @if(isset($title))
                    {{ $title }}
                @else
                    @switch($errorCode)
                        @case(400)
                            Bad Request
                            @break
                        @case(401)
                            Unauthorized Access
                            @break
                        @case(403)
                            Access Forbidden
                            @break
                        @case(404)
                            Page Not Found
                            @break
                        @case(419)
                            Session Expired
                            @break
                        @case(422)
                            Unprocessable Entity
                            @break
                        @case(429)
                            Too Many Requests
                            @break
                        @case(500)
                            Internal Server Error
                            @break
                        @default
                            Something Went Wrong
                    @endswitch
                @endif
            </h2>

            <!-- Error Description -->
            <p class="error-description text-base md:text-lg text-gray-600 mb-8 max-w-lg mx-auto leading-relaxed">
                @if(isset($message))
                    {{ $message }}
                @else
                    @switch($errorCode)
                        @case(400)
                            The request could not be understood due to malformed syntax.
                            @break
                        @case(401)
                            You need to be authenticated to access this resource.
                            @break
                        @case(403)
                            You don't have permission to access this resource.
                            @break
                        @case(404)
                            The page you're looking for could not be found.
                            @break
                        @case(419)
                            Your session has expired for security reasons.
                            @break
                        @case(422)
                            There were validation errors in your request.
                            @break
                        @case(429)
                            Too many requests. Please try again later.
                            @break
                        @case(500)
                            Our server encountered an unexpected error.
                            @break
                        @default
                            An unexpected error occurred.
                    @endswitch
                @endif
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                <!-- Primary Action -->
                @if($errorCode == 419)
                    <div class="flex justify-center">
                        <button onclick="window.location.reload()" class="btn-primary sm:w-auto w-full">
                            <span class="flex items-center justify-center space-x-2">
                                <i class="fas fa-sync-alt" aria-hidden="true"></i>
                                <span>Refresh Page</span>
                            </span>
                        </button>
                    </div>
                @elseif($errorCode == 401)
                    <div class="flex justify-center">
                        <a href="{{ route('login') ?? '/login' }}" class="btn-primary sm:w-auto w-full text-center flex items-center justify-center">
                            <span class="flex items-center space-x-2">
                                <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                                <span>Login</span>
                            </span>
                        </a>
                    </div>
                @else
                    <div class="flex justify-center">
                        <a href="{{ route('home') }}" class="btn-primary sm:w-auto w-full text-center flex items-center justify-center">
                            <span class="flex items-center space-x-2">
                                <i class="fas fa-home" aria-hidden="true"></i>
                                <span>Go Home</span>
                            </span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Footer Info -->
            <div class="mt-8 pt-6 border-t" style="border-color: var(--divider);">
                <p class="text-sm" style="color: var(--on-surface-variant);">
                    Error Code: <span class="font-mono accent-color font-medium">{{ $errorCode }}</span>
                </p>
                <p class="text-xs mt-1" style="color: var(--on-surface-variant);">
                    If this problem persists, please contact our support team.
                </p>
            </div>
        </div>
            </div>
        </div>
    </div>
    </body>
</html>

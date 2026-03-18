<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = trim(strip_tags($__env->yieldContent('title', 'Rahmat Fauji')));
        $seoDescription = trim(strip_tags($__env->yieldContent('meta_description', 'Rahmat Fauji - Data Analytics, Power BI, and Power Apps portfolio, case studies, and practical insights.')));
        $seoCanonical = trim($__env->yieldContent('meta_canonical', url()->current()));
        $seoImage = trim($__env->yieldContent('meta_image', asset('favicon.ico')));
        $seoType = trim($__env->yieldContent('meta_type', 'website'));
        $seoRobots = request()->routeIs('admin.*') || request()->routeIs('login*') ? 'noindex, nofollow' : trim($__env->yieldContent('meta_robots', 'index, follow, max-image-preview:large'));
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoTitle }} | Rahmat Fauji</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:site_name" content="Rahmat Fauji">
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:title" content="{{ $seoTitle }} | Rahmat Fauji">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }} | Rahmat Fauji">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Person",
            "name": "Rahmat Fauji",
            "url": "{{ route('home') }}",
            "jobTitle": "Data Analytics, Power BI, and Power Apps Specialist",
            "sameAs": []
        }
    </script>
    @stack('seo')
    <script>
        // Set data-bs-theme before CSS loads to prevent flash of wrong theme
        (function () {
            var storageKey = 'site-theme-preference';
            var mq = window.matchMedia('(prefers-color-scheme: dark)');

            function getStoredPreference() {
                try {
                    return localStorage.getItem(storageKey) || 'auto';
                } catch (e) {
                    return 'auto';
                }
            }

            function resolveTheme(preference) {
                if (preference === 'dark' || preference === 'light') {
                    return preference;
                }

                return mq.matches ? 'dark' : 'light';
            }

            function applyTheme(preference) {
                document.documentElement.setAttribute('data-bs-theme', resolveTheme(preference));
                document.documentElement.setAttribute('data-theme-preference', preference);
            }

            window.themePreference = {
                get: getStoredPreference,
                set: function (preference) {
                    var safePreference = ['light', 'dark', 'auto'].includes(preference) ? preference : 'auto';

                    try {
                        localStorage.setItem(storageKey, safePreference);
                    } catch (e) {
                        // no-op if localStorage is unavailable
                    }

                    applyTheme(safePreference);
                },
                apply: applyTheme,
            };

            applyTheme(getStoredPreference());

            mq.addEventListener('change', function () {
                if (getStoredPreference() === 'auto') {
                    applyTheme('auto');
                }
            });
        }());
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @stack('styles')
</head>
<body class="{{ request()->routeIs('admin.*') ? 'admin-page' : 'public-page' }}">
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container py-2">
        <a class="navbar-brand text-primary" href="{{ route('home') }}">RAHMAT FAUJI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>{{ __('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('profile') }}" @if(request()->routeIs('profile')) aria-current="page" @endif>{{ __('Profile') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blog.*') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>{{ __('Blog') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('portfolio.*') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('portfolio.index') }}" @if(request()->routeIs('portfolio.*')) aria-current="page" @endif>{{ __('Portfolio') }}</a>
                </li>
                <li class="nav-item dropdown">
                    <button
                        class="btn btn-outline-primary dropdown-toggle ms-lg-2"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        {{ __('Theme') }}: <span data-theme-current-label>Auto</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><button type="button" class="dropdown-item" data-theme-choice="light">{{ __('Light') }}</button></li>
                        <li><button type="button" class="dropdown-item" data-theme-choice="dark">{{ __('Dark') }}</button></li>
                        <li><button type="button" class="dropdown-item" data-theme-choice="auto">{{ __('Auto (Device)') }}</button></li>
                    </ul>
                </li>
                @auth
                    <li class="nav-item"><span class="nav-link">{{ auth()->user()->name }}</span></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="ms-lg-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">{{ __('Logout') }}</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5 {{ request()->routeIs('admin.*') ? 'admin-shell' : 'public-shell' }}">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @yield('content')
</main>

<footer class="container pb-5">
    <div class="text-center footer-note">{{ __('Copyright') }} {{ date('Y') }} - Rahmat Fauji</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const body = document.body;
        const copyButtons = document.querySelectorAll('[data-copy-url]');
        const parallaxTargets = document.querySelectorAll('[data-parallax]');
        const themeButtons = document.querySelectorAll('[data-theme-choice]');
        const themeLabel = document.querySelector('[data-theme-current-label]');

        const themeText = {
            light: 'Light',
            dark: 'Dark',
            auto: 'Auto',
        };

        const updateThemeMenuState = (preference) => {
            if (themeLabel) {
                themeLabel.textContent = themeText[preference] || themeText.auto;
            }

            themeButtons.forEach((button) => {
                const isActive = button.getAttribute('data-theme-choice') === preference;
                button.classList.toggle('active', isActive);
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
        };

        const currentPreference = window.themePreference?.get?.() || 'auto';
        updateThemeMenuState(currentPreference);

        themeButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const selectedTheme = button.getAttribute('data-theme-choice') || 'auto';

                if (window.themePreference?.set) {
                    window.themePreference.set(selectedTheme);
                }

                updateThemeMenuState(selectedTheme);
            });
        });

        copyButtons.forEach((button) => {
            button.addEventListener('click', async () => {
                const url = button.getAttribute('data-copy-url');

                if (!url) {
                    return;
                }

                try {
                    await navigator.clipboard.writeText(url);
                } catch (e) {
                    console.warn(@json(__('Failed to copy share link.')), e);
                }
            });
        });

        if (body.classList.contains('public-page')) {
            if (parallaxTargets.length > 0) {
                const updateParallax = () => {
                    const scrollY = window.scrollY || 0;

                    parallaxTargets.forEach((element) => {
                        const speed = Number(element.getAttribute('data-parallax')) || 0.08;
                        element.style.transform = `translate3d(0, ${Math.round(scrollY * speed)}px, 0)`;
                    });
                };

                updateParallax();
                window.addEventListener('scroll', updateParallax, { passive: true });
            }
        }
    });
</script>
@stack('scripts')
</body>
</html>

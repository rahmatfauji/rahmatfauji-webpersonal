<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seoTitle = trim(strip_tags($__env->yieldContent('title', 'Rahmat Fauji')));
        $seoDescription = trim(strip_tags($__env->yieldContent('meta_description', 'Rahmat Fauji - Data Analytics, Power BI, and Power Apps portfolio, case studies, and practical insights.')));
        $seoCanonical = trim($__env->yieldContent('meta_canonical', url()->current()));
        $faviconSvg = asset('favicon.svg');
        $seoImage = trim($__env->yieldContent('meta_image', $faviconSvg));
        $seoType = trim($__env->yieldContent('meta_type', 'website'));
        $seoRobots = request()->routeIs('admin.*') || request()->routeIs('login*') ? 'noindex, nofollow' : trim($__env->yieldContent('meta_robots', 'index, follow, max-image-preview:large'));
        $publicProfile = request()->routeIs('admin.*') ? null : \App\Models\Profile::query()->oldest()->first();
        $rawPhone = $publicProfile?->phone;
        $normalizedWhatsapp = $rawPhone ? preg_replace('/\D+/', '', $rawPhone) : null;
        if ($normalizedWhatsapp && str_starts_with($normalizedWhatsapp, '0')) {
            $normalizedWhatsapp = '62' . substr($normalizedWhatsapp, 1);
        }
        $whatsAppUrl = $normalizedWhatsapp ? 'https://wa.me/' . $normalizedWhatsapp . '?text=' . rawurlencode('Halo Rahmat, saya tertarik untuk berdiskusi lebih lanjut.') : null;
        $mailUrl = $publicProfile?->email ? 'mailto:' . $publicProfile->email : null;
        $linkedinUrl = $publicProfile?->linkedin_url;
        $githubUrl = $publicProfile?->github_url;
        $socialProfiles = array_values(array_filter([$linkedinUrl, $githubUrl]));
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoTitle }} | Rahmat Fauji</title>
    <link rel="icon" type="image/svg+xml" href="{{ $faviconSvg }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
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
            "sameAs": @json($socialProfiles)
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
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active fw-semibold text-primary' : '' }}" href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.*')) aria-current="page" @endif>{{ __('Administrator') }}</a>
                        </li>
                    @endif
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

@if($publicProfile && ($whatsAppUrl || $mailUrl || $linkedinUrl || $githubUrl))
    <div class="contact-fab is-attention" data-contact-fab>
        <div class="contact-fab-panel" id="contactFabPanel">
            <div class="mb-3">
                <div class="small text-uppercase text-muted fw-semibold">{{ __('Quick Contact') }}</div>
                <div class="fw-semibold">{{ $publicProfile->full_name }}</div>
                <div class="small text-muted">{{ $publicProfile->title }}</div>
                <div class="contact-fab-status">{{ __('Usually replies via WhatsApp or email') }}</div>
            </div>
            <div class="d-grid gap-2">
                @if($whatsAppUrl)
                    <a href="{{ $whatsAppUrl }}" target="_blank" rel="noreferrer" class="contact-fab-link contact-fab-link-whatsapp">
                        <span class="contact-fab-copy">
                            <strong>{{ __('WhatsApp') }}</strong>
                            <span>{{ $publicProfile->phone }}</span>
                        </span>
                        <span class="contact-fab-badge contact-fab-badge-whatsapp" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor" role="presentation">
                                <path d="M19.05 4.91A9.82 9.82 0 0 0 12.03 2C6.55 2 2.1 6.45 2.1 11.93c0 1.75.46 3.45 1.33 4.95L2 22l5.28-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.48 0 9.93-4.45 9.93-9.93a9.86 9.86 0 0 0-2.91-6.99Zm-7.02 15.24h-.01a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3.13.82.83-3.05-.2-.31a8.2 8.2 0 0 1-1.26-4.36c0-4.53 3.69-8.22 8.23-8.22 2.2 0 4.27.86 5.82 2.41a8.16 8.16 0 0 1 2.4 5.82c0 4.53-3.69 8.21-8.2 8.21Zm4.5-6.16c-.25-.12-1.47-.73-1.7-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.78.97-.14.17-.28.19-.53.06-.25-.12-1.03-.38-1.97-1.22-.73-.64-1.22-1.43-1.37-1.67-.14-.25-.01-.38.11-.5.11-.11.25-.28.37-.42.12-.14.16-.25.25-.42.08-.17.04-.31-.02-.44-.06-.12-.56-1.35-.77-1.86-.2-.48-.41-.41-.56-.42h-.48c-.17 0-.44.06-.67.31-.23.25-.87.85-.87 2.08s.89 2.42 1.02 2.58c.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.12.16 1.54.1.47-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.14-1.18-.06-.1-.23-.16-.48-.29Z"/>
                            </svg>
                        </span>
                    </a>
                @endif
                @if($mailUrl)
                    <a href="{{ $mailUrl }}" class="contact-fab-link contact-fab-link-email">
                        <span class="contact-fab-copy">
                            <strong>{{ __('Email') }}</strong>
                            <span>{{ $publicProfile->email }}</span>
                        </span>
                        <span class="contact-fab-badge contact-fab-badge-email" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" role="presentation">
                                <path d="M4 6h16v12H4z"/>
                                <path d="m4 7 8 6 8-6"/>
                            </svg>
                        </span>
                    </a>
                @endif
                @if($linkedinUrl)
                    <a href="{{ $linkedinUrl }}" target="_blank" rel="noreferrer" class="contact-fab-link contact-fab-link-linkedin">
                        <span class="contact-fab-copy">
                            <strong>{{ __('LinkedIn') }}</strong>
                            <span>{{ parse_url($linkedinUrl, PHP_URL_HOST) ?: $linkedinUrl }}</span>
                        </span>
                        <span class="contact-fab-badge contact-fab-badge-linkedin" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor" role="presentation">
                                <path d="M6.94 8.5H3.56V20h3.38V8.5ZM5.25 3A1.97 1.97 0 0 0 3.28 5c0 1.1.88 2 1.95 2h.02a1.98 1.98 0 0 0 0-4ZM20 13.02c0-3.46-1.85-5.07-4.32-5.07-1.99 0-2.88 1.1-3.38 1.86V8.5H8.94c.05.86 0 11.5 0 11.5h3.37v-6.42c0-.34.02-.68.13-.92.27-.68.88-1.38 1.9-1.38 1.34 0 1.87 1.03 1.87 2.54V20H20v-6.98Z"/>
                            </svg>
                        </span>
                    </a>
                @endif
                @if($githubUrl)
                    <a href="{{ $githubUrl }}" target="_blank" rel="noreferrer" class="contact-fab-link contact-fab-link-github">
                        <span class="contact-fab-copy">
                            <strong>{{ __('GitHub') }}</strong>
                            <span>{{ parse_url($githubUrl, PHP_URL_HOST) ?: $githubUrl }}</span>
                        </span>
                        <span class="contact-fab-badge contact-fab-badge-github" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor" role="presentation">
                                <path d="M12 .5C5.65.5.5 5.8.5 12.34c0 5.23 3.3 9.66 7.88 11.23.58.11.8-.26.8-.58 0-.29-.02-1.24-.02-2.25-2.89.55-3.64-.72-3.87-1.39-.13-.35-.7-1.4-1.2-1.69-.42-.24-1.01-.84-.02-.85.94-.02 1.62.89 1.84 1.26 1.07 1.85 2.79 1.33 3.48 1.01.11-.79.42-1.33.76-1.64-2.56-.29-5.23-1.31-5.23-5.81 0-1.28.45-2.34 1.18-3.16-.11-.29-.52-1.5.11-3.11 0 0 .97-.32 3.19 1.2a10.7 10.7 0 0 1 5.8 0c2.22-1.53 3.19-1.2 3.19-1.2.63 1.61.22 2.82.11 3.11.74.82 1.18 1.86 1.18 3.16 0 4.52-2.69 5.52-5.25 5.81.43.38.8 1.11.8 2.26 0 1.64-.02 2.96-.02 3.37 0 .32.22.71.8.58 4.56-1.57 7.87-6 7.87-11.23C23.5 5.8 18.35.5 12 .5Z"/>
                            </svg>
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="contact-fab-meta">
            <button type="button" class="contact-fab-trigger" data-contact-fab-toggle aria-expanded="false" aria-controls="contactFabPanel" aria-label="{{ __('Open quick contact options') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" role="presentation">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/>
                    <path d="M8 9h8"/>
                    <path d="M8 13h5"/>
                </svg>
                <span class="contact-fab-tooltip" aria-hidden="true">{{ __('Contact me') }}</span>
            </button>
        </div>
    </div>
@endif

<button id="scroll-to-top-btn" title="{{ __('Back to top') }}">↑</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const body = document.body;
        const copyButtons = document.querySelectorAll('[data-copy-url]');
        const parallaxTargets = document.querySelectorAll('[data-parallax]');
        const themeButtons = document.querySelectorAll('[data-theme-choice]');
        const themeLabel = document.querySelector('[data-theme-current-label]');
        const contactFab = document.querySelector('[data-contact-fab]');
        const contactFabToggle = document.querySelector('[data-contact-fab-toggle]');

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

                window.prompt(@json(__('Copy this link manually:')), url);
            });
        });

        if (contactFab && contactFabToggle) {
            const closeContactFab = () => {
                contactFab.classList.remove('is-open');
                contactFab.classList.remove('is-attention');
                contactFabToggle.setAttribute('aria-expanded', 'false');
            };

            window.setTimeout(() => {
                contactFab.classList.remove('is-attention');
            }, 7000);

            contactFabToggle.addEventListener('click', () => {
                const isOpen = contactFab.classList.toggle('is-open');
                contactFab.classList.remove('is-attention');
                contactFabToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', (event) => {
                if (!contactFab.contains(event.target)) {
                    closeContactFab();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeContactFab();
                }
            });
        }

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

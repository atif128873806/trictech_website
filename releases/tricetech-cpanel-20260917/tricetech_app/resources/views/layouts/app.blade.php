<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d1f28">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <title>@yield('title', 'Tricetech Solutions')</title>
    <meta name="description" content="@yield('description', 'Enterprise infrastructure, cloud, cybersecurity and software services for Canadian organizations.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Tricetech Solutions">
    <meta property="og:locale" content="en_CA">
    <meta property="og:title" content="@yield('title', 'Tricetech Solutions')">
    <meta property="og:description" content="@yield('description', 'Enterprise infrastructure, cloud, cybersecurity and software services for Canadian organizations.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body_class')">
<a class="skip-link" href="#main-content">Skip to main content</a>

<div class="utility-bar site-top">
    <div class="shell utility-bar__inner">
        <div class="utility-bar__group">
            <span>Mississauga, Ontario</span>
            <a href="tel:+19056246400">+1 905-624-6400</a>
        </div>
        <div class="utility-bar__group utility-bar__group--secondary">
            <span class="utility-bar__flag">24/7 operational monitoring</span>
            <a href="mailto:info@tricetech.ca">info@tricetech.ca</a>
        </div>
    </div>
</div>

<header class="site-header" data-site-header>
    <div class="shell header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="Tricetech Solutions home">
            <span>Tricetech</span>
            <small>Solutions</small>
        </a>
        <button class="menu-button" type="button" aria-expanded="false" aria-controls="site-navigation" data-menu-button>
            <span>Menu</span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8h16M4 16h16"/></svg>
        </button>
        <nav class="site-nav" id="site-navigation" aria-label="Primary navigation" data-site-nav>
            <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Company</a>
            <a href="{{ route('infrastructure') }}" @if(request()->routeIs('infrastructure')) aria-current="page" @endif>Infrastructure</a>
            <a href="{{ route('cloud-software') }}" @if(request()->routeIs('cloud-software')) aria-current="page" @endif>Cloud &amp; Software</a>
            <a class="button button--small" href="{{ route('home') }}#discussion">Request a discussion</a>
        </nav>
    </div>
</header>

<main id="main-content">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="shell footer-grid">
        <div class="footer-col">
            <a class="brand footer-brand" href="{{ route('home') }}"><span>Tricetech</span><small>Solutions</small></a>
            <p>Enterprise infrastructure, cloud, cybersecurity and software services for Canadian organizations — from growing mid-market teams to established enterprises.</p>
        </div>
        <div class="footer-col">
            <h2>Services</h2>
            <nav aria-label="Footer services">
                <a href="{{ route('infrastructure') }}">Infrastructure &amp; Networks</a>
                <a href="{{ route('cloud-software') }}">Cloud &amp; Software</a>
            </nav>
        </div>
        <div class="footer-col">
            <h2>Contact</h2>
            <address>
                Suite 5, 5160 Explorer Drive<br>
                Mississauga, ON L4W 4T7<br>
                <a href="tel:+19056246400">+1 905-624-6400</a><br>
                <a href="mailto:info@tricetech.ca">info@tricetech.ca</a>
            </address>
        </div>
        <div class="footer-col">
            <h2>Operations</h2>
            <ul>
                <li><a href="{{ route('home') }}#discussion">Architecture discussion</a></li>
                <li><a href="{{ route('privacy') }}">Inquiry privacy notice</a></li>
                <li><a href="{{ route('home') }}#capability">Environment coverage</a></li>
            </ul>
        </div>
    </div>
    <div class="shell footer-bottom">
        <span>&copy; {{ date('Y') }} Tricetech Solutions · Mississauga, Ontario</span>
        <a href="#main-content">Back to top</a>
    </div>
</footer>
</body>
</html>

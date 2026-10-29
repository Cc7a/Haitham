<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f3eedf">
    <meta name="description" content="أفق: منصة عربية يحوّل الاقتباسات إلى تجارب صغيرة وخطوات قابلة للإنجاز.">
    <title>@yield('title', 'أفق | دفتر التجارب')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />
    <main class="site-shell">
        @if (session('status'))
            <div class="flash flash-success" role="status"><span class="flash-mark">✓</span>{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="flash flash-error" role="alert"><span class="flash-mark">!</span>{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
    <footer class="site-footer">
        <div class="site-shell footer-inner">
            <a class="brand brand-footer" href="{{ route('home') }}">
                <span class="brand-mark">أ</span>
                <span class="brand-copy"><strong>أفق</strong><small>دفتر النادي • UQU</small></span>
            </a>
            <p>الفكرة بداية. والخطوة توقيعك.</p>
            <a class="footer-club" href="{{ route('home') }}#quotes">تصفّح الدفتر <span aria-hidden="true">↖</span></a>
            <span class="footer-year">© {{ now()->year }} • أفق</span>
        </div>
    </footer>
</body>
</html>
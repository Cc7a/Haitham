@php
    $ownerEmail = strtolower(trim((string) config('afaq.owner_email', '')));
    $isSiteOwner = auth()->check() && $ownerEmail !== '' && hash_equals($ownerEmail, strtolower(auth()->user()->email));
@endphp

<header class="site-header">
    <nav class="site-nav" aria-label="التنقل الرئيسي">
        <a class="brand" href="{{ route('home') }}" aria-label="أفق، الصفحة الرئيسية">
            <span class="brand-mark">أ</span>
            <span class="brand-copy"><strong>أفق</strong><small>دفتر النادي • UQU</small></span>
        </a>
        <span class="nav-edition"><span class="edition-dot"></span> العدد 01 <span>ـ</span> من الفكرة إلى التجربة</span>
        <button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="site-drawer" aria-label="افتح القائمة">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <div class="drawer-backdrop" data-menu-backdrop hidden></div>
    <aside class="site-drawer" id="site-drawer" data-menu-drawer data-open="false" aria-hidden="true" aria-labelledby="drawer-title">
        <div class="drawer-head">
            <div>
                <span class="drawer-kicker">أفق / القائمة</span>
                <h2 id="drawer-title">تنقّل من هنا.</h2>
            </div>
            <button class="drawer-close" type="button" data-menu-close aria-label="إغلاق القائمة">×</button>
        </div>

        <nav class="drawer-links" aria-label="روابط الموقع">
            <a href="{{ route('home') }}">الصفحة الرئيسية <span>01</span></a>
            <a href="{{ route('home') }}#quotes">مكتبة الكلمات <span>02</span></a>
            <a href="{{ route('home') }}#impact-stories">حكايات الأثر <span>03</span></a>
            @auth
                <a href="{{ route('impacts.index') }}">مسار الأثر <span>04</span></a>
                @if ($isSiteOwner)
                    <a class="drawer-owner-link" href="{{ route('owner.dashboard') }}">لوحة المالك <span>↗</span></a>
                @endif
            @endauth
        </nav>

        <div class="drawer-account">
            @auth
                <span class="drawer-account-label">مسجّل الدخول</span>
                <strong>{{ auth()->user()->name }}</strong>
                <small dir="ltr">{{ auth()->user()->email }}</small>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="button button-dark drawer-action" type="submit">تسجيل الخروج <span aria-hidden="true">↗</span></button>
                </form>
            @else
                <span class="drawer-account-label">أهلاً بك في أفق</span>
                <p>تصفّح المكتبة كزائر. أنشئ حسابًا لنشر اقتباس ومتابعة خطواتك.</p>
                <a class="button button-primary drawer-action" href="{{ route('login') }}">تسجيل الدخول <span aria-hidden="true">↗</span></a>
                <a class="drawer-register-link" href="{{ route('register') }}">أو أنشئ حسابًا جديدًا</a>
            @endauth
        </div>
        <div class="drawer-foot"><span>دفتر ميداني • نادي الجامعة</span><span>UQU / 01</span></div>
    </aside>
</header>

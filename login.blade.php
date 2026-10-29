@extends('layouts.app')

@section('title', 'تسجيل الدخول | أفق')

@section('content')
    <section class="auth-page">
        <div class="auth-story">
            <span class="eyebrow"><span class="eyebrow-dot"></span> رجوعك بداية جديدة</span>
            <h1>وش الخطوة<br><em>اللي تنتظرك؟</em></h1>
            <p>ادخل إلى اقتباساتك ومسار الأثر. يمكنك متابعة خطتك أو مشاركة فكرة جديدة.</p>
            <div class="auth-note"><span aria-hidden="true">✓</span><span>كلمة المرور تُخزّن مجزّأة ولا تظهر كنص صريح.</span></div>
        </div>

        <div class="auth-card">
            <h2>تسجيل الدخول</h2>
            <p>أدخل بالبريد وكلمة المرور اللذين أنشأتهما لهذا الموقع. لا يوجد ربط بتسجيل الدخول الموحد للجامعة؛ لا تستخدم كلمة مرور خدمات الجامعة.</p>
            <x-form-errors />
            <form method="POST" action="{{ route('login.store') }}" class="auth-form">
                @csrf
                <label for="email">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" dir="ltr" placeholder="name@example.com">

                <label for="password">كلمة المرور</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" dir="ltr" placeholder="كلمة المرور">
                <button class="button button-primary button-wide" type="submit">دخول إلى أفق <span aria-hidden="true">↗</span></button>
            </form>
            <p class="auth-switch">أول مرة هنا؟ <a href="{{ route('register') }}">أنشئ حسابًا</a></p>
        </div>
    </section>
@endsection


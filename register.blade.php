@extends('layouts.app')

@section('title', 'إنشاء حساب | أفق')

@section('content')
    <section class="auth-page">
        <div class="auth-story">
            <span class="eyebrow"><span class="eyebrow-dot"></span> ابدأ من فكرة قريبة منك</span>
            <h1>الكلمة الجميلة<br><em>تحتاج مساحة.</em></h1>
            <p>احفظ صوتك بين أصوات المجتمع. انشر اقتباسًا، ثم اختر إن كنت تريد تحويله إلى خطوة صغيرة خلال يومك.</p>
            <div class="auth-note"><span aria-hidden="true">✓</span><span>اقتباساتك لك، وخطواتك الخاصة تبقى لك.</span></div>
        </div>

        <div class="auth-card">
            <h2>إنشاء حساب</h2>
            <p>أنشئ حسابًا خاصًا بأفق؛ يمكنك استخدام بريدك الجامعي أو الشخصي مع كلمة مرور مستقلة عن خدمات الجامعة.</p>
            <x-form-errors />
            <form method="POST" action="{{ route('register.store') }}" class="auth-form">
                @csrf
                <label for="name">اسم العرض</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required minlength="2" maxlength="50" autocomplete="name" placeholder="مثال: اسمك">
                <span class="field-hint">من حرفين إلى 50 حرفًا، حروف ومسافات فقط.</span>

                <label for="email">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email" dir="ltr" placeholder="name@example.com">
                <span class="field-hint">استخدم بريدًا يمكنك الوصول إليه.</span>

                <label for="password">كلمة المرور</label>
                <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password" dir="ltr" placeholder="8 أحرف على الأقل">
                <span class="field-hint">لا تقل عن 8 أحرف.</span>

                <label for="password_confirmation">تأكيد كلمة المرور</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" autocomplete="new-password" dir="ltr" placeholder="أعد كتابة كلمة المرور">
                <button class="button button-primary button-wide" type="submit">إنشاء حساب <span aria-hidden="true">↗</span></button>
            </form>
            <p class="auth-switch">عندك حساب؟ <a href="{{ route('login') }}">سجّل الدخول</a></p>
        </div>
    </section>
@endsection

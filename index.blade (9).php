@extends('layouts.app')

@section('title', 'مساحة المالك | أفق')

@section('content')
    <section class="owner-hero">
        <div>
            <span class="eyebrow"><span class="eyebrow-dot"></span> مساحة خاصة بمالك أفق</span>
            <h1>أفق، <em>تحت إدارتك.</em></h1>
            <p>هذه الصفحة تظهر للحساب الذي يطابق البريد المحدد في <code>OWNER_EMAIL</code>. منها تراجع الحسابات المسجلة وآخر دخول إليها.</p>
        </div>
        <a class="button button-quiet" href="{{ route('home') }}">العودة للموقع <span aria-hidden="true">↖</span></a>
    </section>

    <section class="owner-metrics" aria-label="ملخص الحسابات">
        <article class="owner-metric"><span>الحسابات المسجلة</span><strong>{{ number_format($summary['members']) }}</strong><small>أسماء وبريد أصحاب الحسابات</small></article>
        <article class="owner-metric"><span>مرات الدخول المسجلة</span><strong>{{ number_format($summary['logins']) }}</strong><small>تشمل الدخول الأول بعد إنشاء الحساب</small></article>
        <article class="owner-metric"><span>آخر دخول</span><strong class="owner-last-login">{{ $summary['latestLogin'] ? \Illuminate\Support\Carbon::parse($summary['latestLogin'])->format('d/m/Y · h:i A') : '—' }}</strong><small>لأي حساب مسجل</small></article>
    </section>

    <section class="owner-list-section">
        <div class="section-heading">
            <div class="section-intro">
                <span class="eyebrow">سجل الموقع</span>
                <h2>الحسابات التي انضمت إلى أفق</h2>
                <p>تُسجّل الصفحة وقت الدخول للحسابات فقط. الزوار الذين يتصفحون من دون حساب لا تكشف هويتهم المنصة.</p>
            </div>
        </div>

        <div class="owner-table-wrap">
            <table class="owner-table">
                <thead><tr><th>الاسم</th><th>البريد</th><th>تاريخ التسجيل</th><th>آخر دخول</th><th>مرات الدخول</th></tr></thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td data-label="الاسم"><span class="owner-member-name"><span class="author-avatar" aria-hidden="true">{{ mb_substr($member->name, 0, 1) }}</span>{{ $member->name }}</span></td>
                            <td data-label="البريد" dir="ltr">{{ $member->email }}</td>
                            <td data-label="تاريخ التسجيل">{{ $member->created_at->format('d/m/Y') }}</td>
                            <td data-label="آخر دخول">{{ $member->last_login_at?->format('d/m/Y · h:i A') ?? 'لم يسجل الدخول بعد' }}</td>
                            <td data-label="مرات الدخول"><span class="login-count-pill">{{ number_format($member->login_count) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="owner-empty">لا توجد حسابات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($members->hasPages())
            <div class="pagination-wrap">{{ $members->links() }}</div>
        @endif
    </section>
@endsection
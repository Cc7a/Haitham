@extends('layouts.app')

@section('title', 'مساري | أفق')

@section('content')
    <section class="impact-page-hero">
        <div>
            <span class="eyebrow"><span class="eyebrow-dot"></span> سلسلة الأثر الخاصة بك</span>
            <h1>من كلمة إلى <em>خطوة.</em></h1>
            <p>اختر فعلًا صغيرًا من اقتباس ألهمك. سجّل ما أنجزته أو ما تعلمته، وقرر إن كنت تريد مشاركة النتيجة دون اسم.</p>
        </div>
        <a class="button button-quiet" href="{{ route('home') }}#quotes">العودة إلى الاقتباسات <span aria-hidden="true">←</span></a>
    </section>

    <section class="impact-summary" aria-label="ملخص مسار الأثر">
        <div class="impact-summary-card summary-active"><span class="summary-symbol" aria-hidden="true">◷</span><strong>{{ number_format($summary['planned']) }}</strong><span>خطوة قيد التنفيذ</span></div>
        <div class="impact-summary-card summary-complete"><span class="summary-symbol" aria-hidden="true">✓</span><strong>{{ number_format($summary['completed']) }}</strong><span>خطوة أنجزتها</span></div>
        <div class="impact-summary-card summary-learning"><span class="summary-symbol" aria-hidden="true">↗</span><strong>{{ number_format($summary['notCompleted']) }}</strong><span>محاولة نتج عنها تعلّم</span></div>
        <div class="impact-window-note"><span class="window-number">24</span><span>ساعة<br>لكل خطوة</span></div>
    </section>

    <section class="impact-list-section">
        <div class="section-heading impact-list-heading">
            <div class="section-intro">
                <span class="eyebrow">مسارك الشخصي</span>
                <h2>خطواتك المسجّلة</h2>
                <p>التفاصيل لا تظهر للآخرين إلا عند اختيار مشاركة نتيجة الخطوة بلا اسم.</p>
            </div>
        </div>

        <div class="impact-list">
            @forelse ($impactActions as $impactAction)
                <article class="impact-card">
                    <div class="impact-card-top">
                        @if ($impactAction->status === 'planned')
                            <span class="impact-status status-planned">{{ $impactAction->due_at->isPast() ? 'بانتظار نتيجتك' : 'قيد التنفيذ' }}</span>
                        @elseif ($impactAction->status === 'completed')
                            <span class="impact-status status-completed">خطوة تحققت</span>
                        @else
                            <span class="impact-status status-not-completed">لم تكتمل هذه المرة</span>
                        @endif
                        <span class="impact-created">تاريخ البدء {{ $impactAction->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="impact-source-quote">
                        <span>الاقتباس الذي ألهمك</span>
                        <blockquote>«{{ $impactAction->quote->text }}»</blockquote>
                    </div>

                    <div class="impact-action-box">
                        <span>خطوتي</span>
                        <p>{{ $impactAction->action_text }}</p>
                    </div>

                    <div class="impact-due"><span aria-hidden="true">◷</span> الموعد المحدد: {{ $impactAction->due_at->format('d/m/Y - h:i A') }}</div>

                    @if ($impactAction->status === 'planned')
                        <form class="outcome-form" method="POST" action="{{ route('impacts.outcome', $impactAction) }}">
                            @csrf
                            @method('PATCH')
                            <label for="outcome-{{ $impactAction->id }}">وش صار مع خطوتك؟</label>
                            <select id="outcome-{{ $impactAction->id }}" name="status" required>
                                <option value="">اختر النتيجة</option>
                                <option value="completed">أنجزتُ الخطوة</option>
                                <option value="not_completed">ما أنجزتُها، لكن عرفت ما أعطّلني</option>
                            </select>
                            <label for="reflection-{{ $impactAction->id }}">ملاحظة قصيرة <span class="optional-label">اختياري</span></label>
                            <textarea id="reflection-{{ $impactAction->id }}" name="outcome_note" rows="2" maxlength="280" placeholder="اكتب جملة تصف ما تعلمته أو كيف كان شعورك...">{{ old('outcome_note') }}</textarea>
                            <label class="checkbox-row">
                                <input type="hidden" name="share_publicly" value="0">
                                <input type="checkbox" name="share_publicly" value="1" {{ $impactAction->share_publicly ? 'checked' : '' }}>
                                <span>أشارك خطوتي ونتيجتها في قصص الأثر دون إظهار اسمي</span>
                            </label>
                            <button class="button button-primary button-small" type="submit">أحفظ النتيجة</button>
                        </form>
                    @else
                        <div class="impact-result-box">
                            <span>النتيجة المسجّلة</span>
                            <p>{{ $impactAction->outcome_note ?: 'لا توجد ملاحظة مضافة.' }}</p>
                            <span class="privacy-note"><span aria-hidden="true">◉</span> {{ $impactAction->share_publicly ? 'تظهر للآخرين دون اسمك.' : 'خاصة بحسابك فقط.' }}</span>
                        </div>
                    @endif
                </article>
            @empty
                <div class="empty-state impact-empty">
                    <span class="empty-symbol" aria-hidden="true">✦</span>
                    <h3>لم تبدأ سلسلة أثر بعد</h3>
                    <p>اختر اقتباسًا، ثم اكتب فعلًا صغيرًا يمكنك تجربته خلال 24 ساعة.</p>
                    <a class="button button-primary" href="{{ route('home') }}#quotes">اختر اقتباسًا</a>
                </div>
            @endforelse
        </div>

        @if ($impactActions->hasPages())
            <div class="pagination-wrap">{{ $impactActions->links() }}</div>
        @endif
    </section>
@endsection
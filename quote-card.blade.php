@props(['quote'])

@php
    $quoteTheme = in_array($quote->theme, ['paper', 'lemon', 'sea', 'lilac', 'coral'], true) ? $quote->theme : 'paper';
@endphp

<article class="quote-card theme-{{ $quoteTheme }}">
    <div class="quote-card-topline">
        <span class="quote-kind"><span class="small-spark">✦</span> مساحة أفق</span>
        <time datetime="{{ $quote->created_at->toDateString() }}">{{ $quote->created_at->format('d/m/Y') }}</time>
    </div>

    <blockquote class="quote-text">{{ $quote->text }}</blockquote>

    <div class="quote-card-footer">
        <div class="quote-author">
            <span class="author-avatar" aria-hidden="true">{{ mb_substr($quote->user->name, 0, 1) }}</span>
            <span><small>شاركته</small><strong>{{ $quote->user->name }}</strong></span>
        </div>
        <a class="impact-count" href="{{ route('home') }}#impact-stories" aria-label="{{ $quote->public_impact_count }} أثر معلن بلا اسم">
            <span class="impact-count-icon" aria-hidden="true">↗</span>
            {{ $quote->public_impact_count }} أثر
        </a>
    </div>

    @auth
        <div class="quote-card-actions">
            @if (auth()->id() === $quote->user_id)
                <details class="quote-edit-details">
                    <summary>تعديل اقتباسي</summary>
                    <form method="POST" action="{{ route('quotes.update', $quote) }}" class="compact-form">
                        @csrf
                        @method('PATCH')
                        <label for="quote-edit-{{ $quote->id }}">نص الاقتباس</label>
                        <textarea id="quote-edit-{{ $quote->id }}" name="text" rows="3" minlength="3" maxlength="155" required>{{ old('text', $quote->text) }}</textarea>
                        <x-quote-palette id-prefix="edit-quote-{{ $quote->id }}" :selected="$quote->theme ?? 'paper'" />
                        <button class="button button-small" type="submit">حفظ التعديل</button>
                    </form>
                    <form method="POST" action="{{ route('quotes.destroy', $quote) }}" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button class="text-button text-danger" type="submit">حذف الاقتباس</button>
                    </form>
                </details>
            @endif

            <details class="impact-start-details">
                <summary><span aria-hidden="true">✦</span> أحوّلها لخطوة</summary>
                <form method="POST" action="{{ route('impacts.store', $quote) }}" class="compact-form">
                    @csrf
                    <label for="impact-action-{{ $quote->id }}">ما الفعل الصغير الذي ستجربه؟</label>
                    <textarea id="impact-action-{{ $quote->id }}" name="action_text" rows="2" minlength="5" maxlength="180" required placeholder="مثال: أبدأ المهمة المؤجلة لمدة 15 دقيقة"></textarea>
                    <div class="form-hint">حدّد خطوة واحدة يمكنك البدء بها خلال 24 ساعة.</div>
                    <label class="checkbox-row">
                        <input type="hidden" name="share_publicly" value="0">
                        <input type="checkbox" name="share_publicly" value="1">
                        <span>أشارك النتيجة لاحقًا بلا اسمي</span>
                    </label>
                    <button class="button button-small" type="submit">أبدأ سلسلة الأثر</button>
                </form>
            </details>
        </div>
    @else
        <a class="quote-guest-action" href="{{ route('login') }}">سجّل الدخول لتحويل الاقتباس إلى خطوة <span aria-hidden="true">←</span></a>
    @endauth
</article>
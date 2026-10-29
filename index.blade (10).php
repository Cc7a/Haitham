@extends('layouts.app')

@section('title', 'أفق | الفكرة لا تكفي')

@section('content')
    <section class="hero-section">
        <div class="hero-copy">
            <div class="edition-line"><span class="edition-number">01</span><span>دفتر ميداني • نادي الجامعة</span><span class="edition-rule"></span><span>مكة</span></div>
            <h1>اقرأها.<br><em>حوّلها</em><br>إلى أثر.</h1>
            <p class="hero-description">أفق مو جدار اقتباسات. كل كلمة هنا ممكن تصير تجربة صغيرة: اختر خطوة، خصص لها 24 ساعة، ثم ارجع وسجّل ما حدث — حتى لو ما اكتملت.</p>
            <div class="hero-actions">
                @auth
                    <a class="button button-primary" href="#share-quote">أضف كلمة للدفتر <span aria-hidden="true">↙</span></a>
                    <a class="button button-quiet" href="{{ route('impacts.index') }}">أتابع خطوتي</a>
                @else
                    <a class="button button-primary" href="{{ route('register') }}">ابدأ من هنا <span aria-hidden="true">↙</span></a>
                    <a class="button button-quiet" href="#quotes">تصفّح المكتبة</a>
                @endauth
            </div>
            <div class="hero-note"><span class="note-mark">✳</span><span>لا تحفظ العبارة فقط. جرّبها في يومك.</span></div>
        </div>

        <div class="hero-media" aria-label="صورة من بيئة البرنامج ورمز لمسار الأثر">
            <div class="hero-media-index">ملاحظة<br><strong>01</strong></div>
            <figure class="campus-photo">
                <img src="{{ asset('images/signup.jpeg') }}" alt="واجهة مبنى أكاديمية طويق في ضوء النهار">
                <figcaption><span>المكان الذي جمعنا</span><span>UQU / 2026</span></figcaption>
            </figure>
            <div class="photo-stamp"><span>فكرة</span><i aria-hidden="true">↓</i><span>فعل</span></div>
            <div class="hero-quote-slip">
                <span class="slip-label"><span></span> بطاقة اليوم</span>
                <p>«الفكرة إذا ما صارت فعل، تظل احتمال.»</p>
                <div class="slip-footer"><span>اختر خطوة</span><strong>24 ساعة</strong></div>
            </div>
            <span class="hero-scribble" aria-hidden="true">× 24</span>
        </div>
    </section>

    <section class="stats-row" aria-label="مؤشر الأثر من قاعدة بيانات المنصة">
        <div class="stats-heading"><span class="mini-index">00</span><span>المشهد الآن</span></div>
        <div class="stat-item"><strong class="stat-value">{{ number_format($summary['quotes']) }}</strong><span class="stat-label">كلمة في المكتبة</span></div>
        <div class="stat-item"><strong class="stat-value">{{ number_format($summary['plans']) }}</strong><span class="stat-label">خطوة بدأت</span></div>
        <div class="stat-item"><strong class="stat-value">{{ number_format($summary['completed']) }}</strong><span class="stat-label">أثر تحقق</span></div>
        <div class="stat-note"><span class="status-dot"></span> أرقام حقيقية، تتحدث مع كل تجربة</div>
    </section>

    <section class="manifesto-section" id="how-it-works">
        <div class="manifesto-art" aria-hidden="true"><img src="{{ asset('images/background.png') }}" alt=""></div>
        <div class="manifesto-copy">
            <span class="manifesto-index">الفكرة / 01</span>
            <h2>الكلمات تسوي<br><em>موجة.</em></h2>
            <p>لكن الموجة تبدأ من حركة صغيرة. اختر اقتباسًا، اكتب فعلًا تقدر تنفذه اليوم، ثم ارجع وسجّل ما حدث.</p>
            <a class="manifesto-link" href="#quotes">تصفّح الكلمات التي بدأت الموجة <span aria-hidden="true">↙</span></a>
        </div>
        <div class="manifesto-aside"><span>قانون الدفتر</span><strong>01 — 24</strong><small>خطوة واحدة<br>خلال يوم واحد</small></div>
    </section>

    @auth
        <section id="share-quote" class="share-section">
            <div class="section-intro share-intro">
                <span class="eyebrow"><span class="section-index">02</span> إضافة جديدة</span>
                <h2>ما الجملة<br>التي تريد أن تبقى؟</h2>
                <p>اكتب اقتباسًا من 3 إلى 155 حرفًا، واختر اللون الذي يعبّر عن نبرته. وإذا رغبت، اربطه بخطوة واقعية.</p>
            </div>
            <form class="share-form" method="POST" action="{{ route('quotes.store') }}" data-quote-composer>
                @csrf
                <label for="share-quote-text">نص الاقتباس <span class="required-mark">*</span></label>
                <textarea id="share-quote-text" name="text" rows="3" minlength="3" maxlength="155" required placeholder="اكتب العبارة كما تتذكرها...">{{ old('text') }}</textarea>
                <x-quote-palette id-prefix="new-quote" />
                <div class="quote-preview theme-paper" data-quote-preview aria-live="polite">
                    <div class="quote-preview-top"><span>معاينة قبل النشر</span><span>بطاقة أفق / 01</span></div>
                    <blockquote data-preview-text>اكتب اقتباسك ليظهر هنا.</blockquote>
                    <span class="quote-preview-foot">بصمة لونك تظهر في المكتبة</span>
                </div>
                <div class="form-row form-row-end">
                    <span class="form-hint">من 3 إلى 155 حرفًا</span>
                    <span class="char-count" data-counter-for="share-quote-text">0 / 155</span>
                </div>
                <button class="button button-primary" type="submit">أضفها للمكتبة <span aria-hidden="true">↙</span></button>
            </form>
        </section>
    @else
        <section class="join-banner">
            <span class="join-index">دعوة / 01</span>
            <div><h2>الدخول مجاني. الخطوة عليك.</h2><p>أنشئ حسابًا لنشر اقتباسك ومتابعة سلسلة الأثر الخاصة بك.</p></div>
            <a class="button button-dark" href="{{ route('register') }}">أنشئ حسابًا <span aria-hidden="true">↙</span></a>
        </section>
    @endauth

    <section id="quotes" class="quotes-section">
        <div class="section-heading">
            <div class="section-intro">
                <span class="eyebrow"><span class="section-index">03</span> المكتبة المفتوحة</span>
                <h2>كلمات علّمت أحدًا شيئًا.</h2>
                <p>تصفّح الكلمات؛ ربما تغيّر كلمة واحدة اتجاه يومك.</p>
            </div>
            <form class="search-form" method="GET" action="{{ route('home') }}" role="search">
                <label class="sr-only" for="quote-search">ابحث في الاقتباسات أو أسماء ناشريها</label>
                <span class="search-icon" aria-hidden="true">⌕</span>
                <input id="quote-search" type="search" name="q" value="{{ $search }}" maxlength="100" placeholder="كلمة، اسم، بداية فكرة...">
                <button type="submit">ابحث <span aria-hidden="true">↙</span></button>
            </form>
        </div>

        <div class="quote-grid">
            @forelse ($quotes as $quote)
                <x-quote-card :quote="$quote" />
            @empty
                <div class="empty-state">
                    <span class="empty-symbol" aria-hidden="true">✳</span>
                    <h3>{{ $search !== '' ? 'ما لقينا كلمة بهذا البحث' : 'الصفحة الأولى ما زالت فارغة' }}</h3>
                    <p>{{ $search !== '' ? 'جرّب كلمة ثانية أو امسح البحث.' : 'ابدأ حسابك واكتب أول سطر لك هنا.' }}</p>
                    @if ($search !== '')
                        <a class="text-link" href="{{ route('home') }}">العودة إلى المكتبة</a>
                    @else
                        <a class="text-link" href="{{ route('register') }}">اكتب السطر الأول</a>
                    @endif
                </div>
            @endforelse
        </div>
        @if ($quotes->hasPages())
            <div class="pagination-wrap">{{ $quotes->links() }}</div>
        @endif
    </section>

    <section id="impact-stories" class="stories-section">
        <div class="stories-topline"><span>04 / يوميات مفتوحة</span><span class="privacy-note"><span aria-hidden="true">◉</span> من دون أسماء</span></div>
        <div class="stories-intro">
            <div class="section-intro">
                <span class="eyebrow"><span class="section-index">04</span> بعد 24 ساعة</span>
                <h2>وش صار<br>بعد قراءة الاقتباس؟</h2>
            </div>
            <p>هذه مو قصص نجاح مثالية. هنا نحتفي بالخطوة، وبالمحاولة، وبالشيء اللي عرفناه عن أنفسنا. المشاركة اختيارية — والاسم ما يظهر.</p>
        </div>

        <div class="story-grid">
            @forelse ($publicStories as $story)
                <article class="story-card">
                    <div class="story-card-top">
                        <span class="story-result {{ $story->status === 'completed' ? 'result-done' : 'result-tried' }}">
                            <span class="story-pin" aria-hidden="true">{{ $story->status === 'completed' ? '✓' : '↗' }}</span>
                            {{ $story->status === 'completed' ? 'خطوة صارت حقيقة' : 'محاولة تستحق التقدير' }}
                        </span>
                        <time datetime="{{ $story->updated_at->toDateString() }}">{{ $story->updated_at->format('d/m/Y') }}</time>
                    </div>
                    <blockquote>«{{ $story->quote->text }}»</blockquote>
                    <div class="story-action"><span>الخطوة</span><p>{{ $story->action_text }}</p></div>
                    <div class="story-outcome"><span>وش صار</span><p>{{ $story->outcome_note }}</p></div>
                    <span class="anonymous-label"><span aria-hidden="true">◉</span> تجربة مجهولة الاسم</span>
                </article>
            @empty
                <div class="stories-empty">
                    <span aria-hidden="true">✳</span>
                    <p>أول تجربة مشتركة بتظهر هنا بعد ما يسجّل أحدهم النتيجة ويختار مشاركتها بلا اسم.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
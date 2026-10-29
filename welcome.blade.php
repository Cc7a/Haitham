@extends('Layout.app')

@section('title', 'Home')
@section('content')
    @php
        $myQuotes = request()->has('quotes')
            ? array_filter((array) request()->query('quotes', []), 'is_string')
            : [];
        ksort($myQuotes);

        $editingIndex = request()->integer('edit', -1);
        $state = array_filter(['name' => request('name'), 'quotes' => $myQuotes]);

        $otherQuotes = [];

        /*
        $otherQuotes = [
            [
                'author' => 'Lujain',
                'text' => 'The best way to predict the future is to invent it.',
                'date' => 'Oct 10, 2026',
            ],
            [
                'author' => 'Sara',
                'text' => 'Simplicity is the soul of efficiency.',
                'date' => 'Oct 12, 2026',
            ],
        ];
        */
    @endphp

    <div class="mx-auto max-w-3xl p-8">

        {{-- Quotes panel --}}
        <section class="space-y-4">
            <h2 class="text-2xl font-bold text-white"> Quotes</h2>

            {{-- My quotes: purple, newest first --}}
            @forelse (array_reverse($myQuotes, true) as $index => $quote)
                @if ($index === $editingIndex)
                    <form method="GET" action="{{ route('home') }}"
                        class="space-y-3 rounded-2xl bg-tuwaiq-purple p-5 shadow">
                        @foreach (Arr::except($myQuotes, $index) as $otherIndex => $otherQuote)
                            <input type="hidden" name="quotes[{{ $otherIndex }}]" value="{{ $otherQuote }}">
                        @endforeach
                        @if (request('name'))
                            <input type="hidden" name="name" value="{{ request('name') }}">
                        @endif

                        <textarea name="quotes[{{ $index }}]" rows="3" required minlength="3" maxlength="155" class="input"
                            autofocus>{{ $quote }}</textarea>

                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-secondary">Save</button>
                            <a href="{{ route('home', $state) }}" class="btn text-white hover:underline">Cancel</a>
                        </div>
                    </form>
                @else
                    <blockquote class="relative rounded-2xl bg-tuwaiq-purple p-5 text-white shadow">
                        <p class="text-lg">“{{ $quote }}”</p>
                        <p class="mt-2 text-sm font-semibold opacity-80"> —You
                         <span class="text-xs opacity-70">. {{ date('Y-m-d') }}</span></p>

                        
                        <div class="absolute right-4 bottom-5 flex gap-3">
                            <a href="{{ route('home', [...$state, 'edit' => $index]) }}"
                                class="hover:text-tuwaiq-orange">✎</a>

                            <a href="{{ route('home', [...$state, 'quotes' => Arr::except($myQuotes, $index)]) }}"
                                class="hover:text-tuwaiq-orange">⌫</a>
                        </div>
                    </blockquote>
                @endif
                  @empty
                <p class="text-gray-500">No quotes yet. Be the first to share one!</p>
            @endforelse

            {{-- Other people's quotes: teal and yellow, alternating --}}
            @foreach ($otherQuotes as $quote)
                <blockquote
                    class="rounded-2xl p-5 text-tuwaiq-purple shadow bg-white">
                    <p class="text-lg">“{{ $quote['text'] }}”</p>
                    <p class="mt-2 text-sm">
                        <span class="font-semibold">— {{ $quote['author'] }}</span>
                        <span class="text-xs opacity-70">· {{ $quote['date'] }}</span>
                    </p>

                </blockquote>
            @endforeach
        </section>

    </div>

    {{-- Floating "+" button --}}
    <button type="button" popovertarget="quote-popup" aria-label="Share a quote"
        class="fixed bottom-8 right-8 z-40 flex h-16 w-16 items-center justify-center rounded-full bg-tuwaiq-purple text-white shadow-xl transition hover:scale-110 hover:bg-tuwaiq-purple-light focus:outline-none focus-visible:ring-4 focus-visible:ring-tuwaiq-purple/30">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
            class="h-8 w-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>

    {{-- Pop-up form --}}
    <div id="quote-popup" popover
        class="m-auto w-[90%] max-w-lg rounded-3xl bg-white p-0 shadow-2xl backdrop:bg-black/40 backdrop:backdrop-blur-sm">

        <form method="GET" action="{{ route('home') }}" class="space-y-4 p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-tuwaiq-purple">Share a quote</h2>
                <button type="button" popovertarget="quote-popup" popovertargetaction="hide" aria-label="Close"
                    class="rounded-full p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @foreach ($myQuotes as $index => $savedQuote)
                <input type="hidden" name="quotes[{{ $index }}]" value="{{ $savedQuote }}">
            @endforeach
            @if (request('name'))
                <input type="hidden" name="name" value="{{ request('name') }}">
            @endif

            <div>
                <textarea id="quote-body" name="quotes[]" rows="5" required minlength="3" maxlength="155" autofocus
                    placeholder="Write your quote…" class="input peer resize-none user-invalid:border-red-500"></textarea>
                <p class="mt-1 text-xs text-gray-500 peer-user-invalid:text-red-600">3–155 characters.</p>
            </div>

            <button type="submit" class="btn btn-primary w-full">Send</button>
        </form>
    </div>
@endsection


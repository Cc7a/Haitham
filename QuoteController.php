<?php

namespace App\Http\Controllers;

use App\Models\ImpactAction;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $quotesQuery = Quote::query()
            ->with('user')
            ->withCount([
                'impactActions as public_impact_count' => function (Builder $query): void {
                    $query->where('share_publicly', true)
                        ->whereIn('status', ['completed', 'not_completed']);
                },
            ]);

        if ($search !== '') {
            $quotesQuery->where(function (Builder $query) use ($search): void {
                $query->where('text', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $userQuery) use ($search): void {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $quotes = $quotesQuery->latest('created_at')->paginate(8)->withQueryString();
        $publicStories = ImpactAction::query()
            ->where('share_publicly', true)
            ->whereIn('status', ['completed', 'not_completed'])
            ->whereNotNull('outcome_note')
            ->with('quote')
            ->latest('updated_at')
            ->take(4)
            ->get();

        return view('quotes.index', [
            'quotes' => $quotes,
            'search' => $search,
            'publicStories' => $publicStories,
            'summary' => [
                'quotes' => Quote::count(),
                'plans' => ImpactAction::count(),
                'completed' => ImpactAction::where('status', 'completed')->count(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'text' => ['required', 'string', 'min:3', 'max:155'],
            'theme' => ['nullable', 'string', 'in:paper,lemon,sea,lilac,coral'],
        ], [
            'text.required' => 'اكتب نص الاقتباس.',
            'text.min' => 'الاقتباس لازم يكون 3 أحرف على الأقل.',
            'text.max' => 'الاقتباس ما يتجاوز 155 حرفًا.',
        ]);

        $request->user()->quotes()->create([
            'text' => trim($validated['text']),
            'theme' => $validated['theme'] ?? 'paper',
        ]);

        return redirect()->route('home')->with('status', 'وصل اقتباسك إلى مجتمع أفق.');
    }

    public function update(Request $request, Quote $quote): RedirectResponse
    {
        abort_unless($request->user()?->id === $quote->user_id, 403);

        $validated = $request->validate([
            'text' => ['required', 'string', 'min:3', 'max:155'],
            'theme' => ['nullable', 'string', 'in:paper,lemon,sea,lilac,coral'],
        ], [
            'text.required' => 'اكتب نص الاقتباس.',
            'text.min' => 'الاقتباس لازم يكون 3 أحرف على الأقل.',
            'text.max' => 'الاقتباس ما يتجاوز 155 حرفًا.',
        ]);

        $quote->update([
            'text' => trim($validated['text']),
            'theme' => $validated['theme'] ?? $quote->theme ?? 'paper',
        ]);

        return redirect()->route('home')->with('status', 'تم تحديث اقتباسك.');
    }

    public function destroy(Request $request, Quote $quote): RedirectResponse
    {
        abort_unless($request->user()?->id === $quote->user_id, 403);

        $quote->delete();

        return redirect()->route('home')->with('status', 'تم حذف الاقتباس.');
    }
}

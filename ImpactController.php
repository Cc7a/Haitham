<?php

namespace App\Http\Controllers;

use App\Models\ImpactAction;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImpactController extends Controller
{
    public function index(Request $request): View
    {
        $impactActions = $request->user()
            ->impactActions()
            ->with('quote.user')
            ->latest()
            ->paginate(10);

        return view('impacts.index', [
            'impactActions' => $impactActions,
            'summary' => [
                'planned' => $request->user()->impactActions()->where('status', 'planned')->count(),
                'completed' => $request->user()->impactActions()->where('status', 'completed')->count(),
                'notCompleted' => $request->user()->impactActions()->where('status', 'not_completed')->count(),
            ],
        ]);
    }

    public function store(Request $request, Quote $quote): RedirectResponse
    {
        $validated = $request->validate([
            'action_text' => ['required', 'string', 'min:5', 'max:180'],
            'share_publicly' => ['nullable', 'boolean'],
        ], [
            'action_text.required' => 'اكتب الخطوة التي ستجربها.',
            'action_text.min' => 'اكتب خطوة من 5 أحرف على الأقل.',
            'action_text.max' => 'الخطوة ما تتجاوز 180 حرفًا.',
        ]);

        $request->user()->impactActions()->create([
            'quote_id' => $quote->id,
            'action_text' => trim($validated['action_text']),
            'due_at' => now()->addHours(24),
            'status' => 'planned',
            'share_publicly' => $request->boolean('share_publicly'),
        ]);

        return redirect()->route('impacts.index')->with('status', 'بدأت سلسلة أثر جديدة. عد بعد خطوتك وسجّل النتيجة.');
    }

    public function updateOutcome(Request $request, ImpactAction $impactAction): RedirectResponse
    {
        abort_unless($request->user()?->id === $impactAction->user_id, 403);

        if ($impactAction->status !== 'planned') {
            return back()->with('error', 'تم تسجيل نتيجة هذه الخطوة من قبل.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:completed,not_completed'],
            'outcome_note' => ['nullable', 'string', 'max:280'],
            'share_publicly' => ['nullable', 'boolean'],
        ], [
            'status.required' => 'اختر نتيجة الخطوة.',
            'outcome_note.max' => 'الملخص ما يتجاوز 280 حرفًا.',
        ]);

        $sharePublicly = $request->boolean('share_publicly');
        $outcomeNote = trim((string) ($validated['outcome_note'] ?? ''));

        if ($sharePublicly && $outcomeNote === '') {
            return back()
                ->withInput()
                ->withErrors(['outcome_note' => 'أضف جملة قصيرة عن النتيجة عند اختيار مشاركتها بلا اسم.']);
        }

        $impactAction->update([
            'status' => $validated['status'],
            'outcome_note' => $outcomeNote !== '' ? $outcomeNote : null,
            'share_publicly' => $sharePublicly,
        ]);

        return redirect()->route('impacts.index')->with('status', 'حُفظت نتيجة خطوتك.');
    }
}

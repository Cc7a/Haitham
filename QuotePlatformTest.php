<?php

use App\Models\ImpactAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('shows public quotes to visitors', function (): void {
    $author = User::factory()->create(['name' => 'نورة']);
    $quote = $author->quotes()->create(['text' => 'خطوة واحدة تغيّر اتجاه اليوم.']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($quote->text)
        ->assertSee($author->name);
});

it('registers a user and stores a hashed password', function (): void {
    $this->post(route('register.store'), [
        'name' => 'نورة الحربي',
        'email' => 'noura@example.test',
        'password' => 'strong-pass-123',
        'password_confirmation' => 'strong-pass-123',
    ])->assertRedirect(route('home'));

    $user = User::where('email', 'noura@example.test')->firstOrFail();

    expect(Hash::check('strong-pass-123', $user->password))->toBeTrue();
    $this->assertAuthenticatedAs($user);
});

it('validates registration fields before creating an account', function (): void {
    $this->post(route('register.store'), [
        'name' => 'ن',
        'email' => 'not-an-email',
        'password' => 'short',
        'password_confirmation' => 'different',
    ])->assertSessionHasErrors(['name', 'email', 'password']);

    $this->assertDatabaseCount('users', 0);
});

it('logs in a member and logs out safely', function (): void {
    $user = User::factory()->create([
        'email' => 'member@example.test',
        'password' => 'strong-pass-123',
    ]);

    $this->post(route('login.store'), [
        'email' => 'member@example.test',
        'password' => 'strong-pass-123',
    ])->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);

    $this->post(route('logout'))->assertRedirect(route('home'));

    $this->assertGuest();
});
it('lets a registered user publish a quote', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('quotes.store'), [
        'text' => 'أبدأ بما أستطيع، ثم أوسّع خطوتي.',
    ])->assertRedirect(route('home'));

    $this->assertDatabaseHas('quotes', [
        'user_id' => $user->id,
        'text' => 'أبدأ بما أستطيع، ثم أوسّع خطوتي.',
    ]);
});

it('allows quote owners to edit and delete only their own quotes', function (): void {
    $owner = User::factory()->create();
    $visitor = User::factory()->create();
    $quote = $owner->quotes()->create(['text' => 'النص الأول للاقتباس.']);

    $this->actingAs($owner)
        ->patch(route('quotes.update', $quote), ['text' => 'النص المعدّل للاقتباس.'])
        ->assertRedirect(route('home'));

    expect($quote->fresh()->text)->toBe('النص المعدّل للاقتباس.');

    $this->actingAs($visitor)
        ->patch(route('quotes.update', $quote), ['text' => 'محاولة تعديل غير مسموحة.'])
        ->assertForbidden();

    $this->actingAs($visitor)
        ->delete(route('quotes.destroy', $quote))
        ->assertForbidden();

    $this->actingAs($owner)->delete(route('quotes.destroy', $quote))->assertRedirect(route('home'));
    $this->assertDatabaseMissing('quotes', ['id' => $quote->id]);
});

it('creates a 24-hour impact plan and records its outcome', function (): void {
    $author = User::factory()->create();
    $reader = User::factory()->create();
    $quote = $author->quotes()->create(['text' => 'امنحي فكرتك وقتًا لتصبح فعلًا.']);

    $this->actingAs($reader)
        ->post(route('impacts.store', $quote), [
            'action_text' => 'أقرأ عشر صفحات هذا المساء.',
            'share_publicly' => '0',
        ])
        ->assertRedirect(route('impacts.index'));

    $impactAction = ImpactAction::where('user_id', $reader->id)->firstOrFail();
    expect($impactAction->due_at->isBetween(now()->addHours(23), now()->addHours(25)))->toBeTrue();

    $this->actingAs($reader)
        ->patch(route('impacts.outcome', $impactAction), [
            'status' => 'completed',
            'outcome_note' => '',
            'share_publicly' => '1',
        ])
        ->assertSessionHasErrors('outcome_note');

    $this->actingAs($reader)
        ->patch(route('impacts.outcome', $impactAction), [
            'status' => 'completed',
            'outcome_note' => 'أنجزت الخطوة وشعرت بتركيز أفضل.',
            'share_publicly' => '1',
        ])
        ->assertRedirect(route('impacts.index'));

    expect($impactAction->fresh()->status)->toBe('completed');
    expect($impactAction->fresh()->share_publicly)->toBeTrue();

    $this->get(route('home'))->assertSee('أنجزت الخطوة وشعرت بتركيز أفضل.');
});

it('prevents users from updating another person’s impact plan', function (): void {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $quote = $owner->quotes()->create(['text' => 'نص تجريبي للاقتباس.']);
    $impactAction = ImpactAction::factory()->create([
        'user_id' => $owner->id,
        'quote_id' => $quote->id,
    ]);

    $this->actingAs($otherUser)
        ->patch(route('impacts.outcome', $impactAction), [
            'status' => 'completed',
            'outcome_note' => 'محاولة غير مسموحة.',
        ])
        ->assertForbidden();
});

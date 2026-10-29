<?php

namespace Database\Factories;

use App\Models\ImpactAction;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ImpactAction> */
class ImpactActionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'quote_id' => Quote::factory(),
            'action_text' => 'Write one page before opening social media.',
            'due_at' => now()->addHours(24),
            'status' => 'planned',
            'outcome_note' => null,
            'share_publicly' => false,
        ];
    }
}

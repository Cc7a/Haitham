<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impact_actions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->string('action_text', 180);
            $table->timestamp('due_at');
            $table->string('status', 24)->default('planned');
            $table->text('outcome_note')->nullable();
            $table->boolean('share_publicly')->default(false);
            $table->timestamps();
            $table->index(['user_id', 'status']);
            $table->index(['quote_id', 'share_publicly']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impact_actions');
    }
};

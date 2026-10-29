<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImpactAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quote_id',
        'action_text',
        'due_at',
        'status',
        'outcome_note',
        'share_publicly',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'share_publicly' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}

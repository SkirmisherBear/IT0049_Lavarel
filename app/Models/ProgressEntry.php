<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressEntry extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'duration_minutes',
        'notes',
        'recorded_on',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'recorded_on' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

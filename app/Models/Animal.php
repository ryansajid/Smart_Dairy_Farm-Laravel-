<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Animal extends Model
{
    protected $fillable = [
        'animal_id',
        'breed_type',
        'date',
        'milk_collected',
        'health_condition',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'milk_collected' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'animal_id',
        'medicine_id',
        'treatment_id',
        'treatment_date',
        'milk_withdrawal_hours',
        'meat_withdrawal_days',
        'milk_withdrawal_end',
        'meat_withdrawal_end',
        'milk_test_date',
        'milk_test_result',
        'status',
        'milk_discard_qty',
        'notes',
    ];
}

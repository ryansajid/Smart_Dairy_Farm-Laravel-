<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = [
        'treatment_number',
        'animal_id',
        'disease_case_id',
        'treatment_date',
        'treatment_type',
        'diagnosis',
        'treatment_description',
        'prescribed_by',
        'administered_by',
        'response',
        'next_treatment_date',
        'cost',
        'notes',
    ];
}

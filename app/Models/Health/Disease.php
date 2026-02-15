<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $fillable = [
        'name',
        'disease_type',
        'incubation_period_days',
        'transmission_methods',
        'clinical_signs',
        'treatment_protocol',
        'prevention',
        'zoonotic',
        'notifiable',
        'severity',
    ];
}

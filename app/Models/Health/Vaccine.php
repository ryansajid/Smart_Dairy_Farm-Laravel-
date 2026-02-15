<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    protected $fillable = [
        'name',
        'manufacturer',
        'vaccine_type',
        'administration_route',
        'dosage',
        'storage_temp_min',
        'storage_temp_max',
        'shelf_life_months',
        'cost_per_dose',
        'supplier',
        'regulatory_approval_number',
        'active',
    ];
}

<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'generic_name',
        'manufacturer',
        'supplier',
        'medicine_form',
        'strength',
        'storage_requirements',
        'expiry_tracking',
        'reorder_level',
        'reorder_qty',
        'unit_cost',
        'current_stock',
        'stock_location',
        'active',
    ];
}

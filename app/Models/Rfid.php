<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    //
    protected $table = 'rfids';
    protected $fillable = [
        'tag_uid', 'is_active', 'assigned_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'assigned_at' => 'datetime',
    ];
}

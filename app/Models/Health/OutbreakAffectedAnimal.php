<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OutbreakAffectedAnimal extends Model
{
    use HasFactory;

    protected $fillable = [
        'outbreak_id',
        'disease_case_id',
        'quarantine_start',
        'quarantine_end',
        'status',
    ];

    protected $casts = [
        'quarantine_start' => 'date',
        'quarantine_end' => 'date',
    ];

    /**
     * Get the outbreak this animal belongs to
     */
    public function outbreak()
    {
        return $this->belongsTo(DiseaseOutbreak::class);
    }

    /**
     * Get the disease case
     */
    public function diseaseCase()
    {
        return $this->belongsTo(DiseaseCase::class);
    }
}

<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiseaseOutbreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'disease_id',
        'start_date',
        'end_date',
        'description',
        'biosecurity_protocols',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the disease associated with this outbreak
     */
    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    /**
     * Get affected animals in this outbreak
     */
    public function affectedAnimals()
    {
        return $this->hasMany(OutbreakAffectedAnimal::class);
    }

    /**
     * Get disease cases in this outbreak
     */
    public function diseaseCases()
    {
        return $this->hasManyThrough(
            DiseaseCase::class,
            OutbreakAffectedAnimal::class,
            'outbreak_id',
            'id',
            'id',
            'disease_case_id'
        );
    }

    /**
     * Count active cases in outbreak
     */
    public function getActiveCasesCountAttribute(): int
    {
        return $this->affectedAnimals()
            ->where('status', 'quarantined')
            ->count();
    }
}

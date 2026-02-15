<?php

namespace App\Models\Health;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiseaseCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'animal_id',
        'disease_id',
        'onset_date',
        'diagnosis_date',
        'severity',
        'isolation_status',
        'outcome',
        'outcome_date',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'onset_date' => 'date',
        'diagnosis_date' => 'date',
        'outcome_date' => 'date',
        'isolation_status' => 'boolean',
    ];

    /**
     * Get the disease associated with this case
     */
    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    /**
     * Get the animal associated with this case
     */
    public function animal()
    {
        return $this->belongsTo(\App\Models\Animal::class);
    }

    /**
     * Get the user who recorded this case
     */
    public function recordedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'recorded_by');
    }

    /**
     * Get treatments for this case
     */
    public function treatments()
    {
        return $this->belongsToMany(Treatment::class, 'case_treatment_rel');
    }

    /**
     * Get outbreak associations
     */
    public function outbreakAnimals()
    {
        return $this->hasMany(OutbreakAffectedAnimal::class, 'disease_case_id');
    }

    /**
     * Generate case number automatically
     */
    public static function generateCaseNumber(): string
    {
        $latest = self::latest()->first();
        $number = $latest ? intval(substr($latest->case_number, 4)) + 1 : 1;
        return 'CASE' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}

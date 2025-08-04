<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Patient
 *
 * @property int $id
 * @property string $patient_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $birth_date
 * @property string $gender
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $allergies
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Visit> $visits
 * @property-read int|null $visits_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereAllergies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereUpdatedAt($value)
 * @method static \Database\Factories\PatientFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'patient_id',
        'name',
        'birth_date',
        'gender',
        'phone',
        'address',
        'allergies',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all visits for this patient.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get the patient's age.
     */
    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    /**
     * Get allergies as an array.
     */
    public function getAllergiesListAttribute(): array
    {
        if (!$this->allergies) {
            return [];
        }
        
        return array_map(
            'trim', 
            explode(',', strtolower($this->allergies))
        );
    }

    /**
     * Get the most recent visit.
     */
    public function getLatestVisitAttribute(): ?Visit
    {
        $visit = $this->visits()->latest('visit_date')->first();
        return $visit instanceof Visit ? $visit : null;
    }
}
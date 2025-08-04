<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Visit
 *
 * @property int $id
 * @property int $patient_id
 * @property \Illuminate\Support\Carbon $visit_date
 * @property string|null $chief_complaint
 * @property string|null $diagnosis
 * @property string|null $treatment
 * @property string|null $notes
 * @property string|null $follow_up_plan
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Patient $patient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prescription> $prescriptions
 * @property-read int|null $prescriptions_count
 * @property-read \App\Models\Bill|null $bill
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereChiefComplaint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereDiagnosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereFollowUpPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereTreatment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereVisitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit today()
 * @method static \Database\Factories\VisitFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Visit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'patient_id',
        'visit_date',
        'chief_complaint',
        'diagnosis',
        'treatment',
        'notes',
        'follow_up_plan',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'visit_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the patient that owns the visit.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get all prescriptions for this visit.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the bill for this visit.
     */
    public function bill(): HasOne
    {
        return $this->hasOne(Bill::class);
    }

    /**
     * Scope a query to only include today's visits.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', today());
    }

    /**
     * Get the main prescription for this visit.
     */
    public function getMainPrescriptionAttribute(): ?Prescription
    {
        $prescription = $this->prescriptions()->first();
        return $prescription instanceof Prescription ? $prescription : null;
    }
}
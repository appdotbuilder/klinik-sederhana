<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Prescription
 *
 * @property int $id
 * @property int $visit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Visit $visit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PrescriptionItem> $items
 * @property-read int|null $items_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription query()
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Prescription whereVisitId($value)

 * 
 * @mixin \Eloquent
 */
class Prescription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'visit_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the visit that owns the prescription.
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get all prescription items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Check for allergy warnings.
     */
    public function getAllergyWarningsAttribute(): array
    {
        $patient = $this->visit->patient;
        $allergies = $patient->allergies_list;
        $warnings = [];

        if (empty($allergies)) {
            return $warnings;
        }

        foreach ($this->items as $item) {
            $medicationName = strtolower($item->inventoryItem->name);
            
            foreach ($allergies as $allergy) {
                if (str_contains($medicationName, $allergy)) {
                    $warnings[] = [
                        'medication' => $item->inventoryItem->name,
                        'allergy' => $allergy,
                    ];
                }
            }
        }

        return $warnings;
    }
}
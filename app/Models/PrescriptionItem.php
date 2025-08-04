<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PrescriptionItem
 *
 * @property int $id
 * @property int $prescription_id
 * @property int $inventory_item_id
 * @property int $quantity
 * @property string $dosage
 * @property string $frequency
 * @property string $duration
 * @property string|null $instructions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Prescription $prescription
 * @property-read \App\Models\InventoryItem $inventoryItem
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereDosage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem wherePrescriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrescriptionItem whereUpdatedAt($value)

 * 
 * @mixin \Eloquent
 */
class PrescriptionItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'prescription_id',
        'inventory_item_id',
        'quantity',
        'dosage',
        'frequency',
        'duration',
        'instructions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the prescription that owns the item.
     */
    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    /**
     * Get the inventory item.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
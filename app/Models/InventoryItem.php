<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\InventoryItem
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property string|null $description
 * @property string $unit
 * @property int $stock
 * @property float $price
 * @property int $minimal_alert
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PrescriptionItem> $prescriptionItems
 * @property-read int|null $prescription_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BillItem> $billItems
 * @property-read int|null $bill_items_count
 * @property-read bool $is_low_stock
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereMinimalAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryItem lowStock()
 * @method static \Database\Factories\InventoryItemFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class InventoryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'unit',
        'stock',
        'price',
        'minimal_alert',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'minimal_alert' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all prescription items for this inventory item.
     */
    public function prescriptionItems(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * Get all bill items for this inventory item.
     */
    public function billItems(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * Check if stock is low.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock <= $this->minimal_alert;
    }

    /**
     * Scope a query to only include items with low stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= minimal_alert');
    }

    /**
     * Reduce stock by quantity.
     */
    public function reduceStock(int $quantity): void
    {
        $this->decrement('stock', $quantity);
    }

    /**
     * Get total quantity sold.
     */
    public function getTotalSoldAttribute(): int
    {
        return $this->billItems()
            ->whereHas('bill', function ($query) {
                $query->where('status', 'paid');
            })
            ->sum('quantity');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\BillItem
 *
 * @property int $id
 * @property int $bill_id
 * @property int $inventory_item_id
 * @property int $quantity
 * @property float $unit_price
 * @property float $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bill $bill
 * @property-read \App\Models\InventoryItem $inventoryItem
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereBillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereInventoryItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BillItem whereUpdatedAt($value)

 * 
 * @mixin \Eloquent
 */
class BillItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'bill_id',
        'inventory_item_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the bill that owns the item.
     */
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    /**
     * Get the inventory item.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (BillItem $billItem) {
            $billItem->subtotal = $billItem->quantity * $billItem->unit_price;
        });
    }
}
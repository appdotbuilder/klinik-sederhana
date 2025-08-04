<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Bill
 *
 * @property int $id
 * @property int $visit_id
 * @property string $bill_number
 * @property float $subtotal
 * @property float $total
 * @property float $paid_amount
 * @property float $change_amount
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Visit $visit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BillItem> $items
 * @property-read int|null $items_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereBillNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereChangeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill whereVisitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bill paid()
 * @method static \Illuminate\Database\Eloquent\Builder|Bill today()

 * 
 * @mixin \Eloquent
 */
class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'visit_id',
        'bill_number',
        'subtotal',
        'total',
        'paid_amount',
        'change_amount',
        'status',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the visit that owns the bill.
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * Get all bill items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * Generate a unique bill number.
     */
    public static function generateBillNumber(): string
    {
        $prefix = 'BILL-';
        $date = now()->format('Ymd');
        $sequence = static::whereDate('created_at', today())->count() + 1;
        
        return $prefix . $date . '-' . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate totals based on items.
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum('subtotal');
        
        $this->update([
            'subtotal' => $subtotal,
            'total' => $subtotal, // No tax for now
        ]);
    }

    /**
     * Process payment.
     */
    public function processPayment(float $paidAmount): void
    {
        $changeAmount = $paidAmount - $this->total;
        
        $this->update([
            'paid_amount' => $paidAmount,
            'change_amount' => $changeAmount,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Reduce inventory stock
        foreach ($this->items as $item) {
            $item->inventoryItem->reduceStock($item->quantity);
        }
    }

    /**
     * Scope a query to only include paid bills.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include today's bills.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
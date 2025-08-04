<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillItemRequest;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class BillItemController extends Controller
{
    /**
     * Store a newly created bill item.
     */
    public function store(StoreBillItemRequest $request, Bill $bill)
    {
        $data = $request->validated();
        $inventoryItem = InventoryItem::findOrFail($data['inventory_item_id']);
        
        // Check if there's enough stock
        if ($inventoryItem->stock < $data['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $inventoryItem->stock);
        }

        // Check if item already exists in bill
        /** @var BillItem|null $existingItem */
        $existingItem = $bill->items()->where('inventory_item_id', $data['inventory_item_id'])->first();
        
        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $data['quantity'];
            if ($inventoryItem->stock < $newQuantity) {
                return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $inventoryItem->stock);
            }
            
            $existingItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            BillItem::create([
                'bill_id' => $bill->id,
                'inventory_item_id' => $data['inventory_item_id'],
                'quantity' => $data['quantity'],
                'unit_price' => $inventoryItem->price,
            ]);
        }

        $bill->calculateTotals();

        return back()->with('success', 'Item berhasil ditambahkan ke tagihan.');
    }

    /**
     * Remove the specified bill item.
     */
    public function destroy(Bill $bill, BillItem $item)
    {
        if ($item->bill_id !== $bill->id) {
            return back()->with('error', 'Item tidak ditemukan dalam tagihan ini.');
        }

        $item->delete();
        $bill->calculateTotals();

        return back()->with('success', 'Item berhasil dihapus dari tagihan.');
    }
}
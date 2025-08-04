<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrescriptionItemRequest;
use App\Models\InventoryItem;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;

class PrescriptionItemController extends Controller
{
    /**
     * Store a newly created prescription item.
     */
    public function store(StorePrescriptionItemRequest $request, Prescription $prescription)
    {
        $data = $request->validated();
        $medication = InventoryItem::findOrFail($data['inventory_item_id']);
        
        // Check if medication is actually medicine
        if ($medication->type !== 'obat') {
            return back()->with('error', 'Item yang dipilih bukan obat.');
        }

        // Check if there's enough stock
        if ($medication->stock < $data['quantity']) {
            return back()->with('error', 'Stok obat tidak mencukupi. Stok tersedia: ' . $medication->stock);
        }

        PrescriptionItem::create([
            'prescription_id' => $prescription->id,
            'inventory_item_id' => $data['inventory_item_id'],
            'quantity' => $data['quantity'],
            'dosage' => $data['dosage'],
            'frequency' => $data['frequency'],
            'duration' => $data['duration'],
            'instructions' => $data['instructions'],
        ]);

        return back()->with('success', 'Obat berhasil ditambahkan ke resep.');
    }

    /**
     * Remove the specified prescription item.
     */
    public function destroy(Prescription $prescription, PrescriptionItem $item)
    {
        if ($item->prescription_id !== $prescription->id) {
            return back()->with('error', 'Item tidak ditemukan dalam resep ini.');
        }

        $item->delete();

        return back()->with('success', 'Obat berhasil dihapus dari resep.');
    }
}
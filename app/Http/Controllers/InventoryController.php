<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryItemRequest;
use App\Http\Requests\UpdateInventoryItemRequest;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items.
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }
        
        if ($request->filled('low_stock')) {
            $query->lowStock();
        }
        
        $items = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('inventory/index', [
            'items' => $items,
            'filters' => $request->only(['search', 'type', 'low_stock']),
        ]);
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        return Inertia::render('inventory/create');
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(StoreInventoryItemRequest $request)
    {
        $item = InventoryItem::create($request->validated());

        return redirect()->route('inventory.show', $item)
            ->with('success', 'Item inventori berhasil ditambahkan.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(InventoryItem $item)
    {
        $item->load(['prescriptionItems.prescription.visit.patient', 'billItems.bill.visit.patient']);

        return Inertia::render('inventory/show', [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the inventory item.
     */
    public function edit(InventoryItem $item)
    {
        return Inertia::render('inventory/edit', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified inventory item.
     */
    public function update(UpdateInventoryItemRequest $request, InventoryItem $item)
    {
        $item->update($request->validated());

        return redirect()->route('inventory.show', $item)
            ->with('success', 'Item inventori berhasil diperbarui.');
    }

    /**
     * Remove the specified inventory item.
     */
    public function destroy(InventoryItem $item)
    {
        $item->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Item inventori berhasil dihapus.');
    }
}
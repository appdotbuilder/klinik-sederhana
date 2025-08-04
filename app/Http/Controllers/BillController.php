<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillItemRequest;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\InventoryItem;
use App\Models\Visit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BillController extends Controller
{
    /**
     * Display a listing of bills.
     */
    public function index(Request $request)
    {
        $query = Bill::with(['visit.patient']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }
        
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->get('date'));
        }
        
        $bills = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('bills/index', [
            'bills' => $bills,
            'filters' => $request->only(['status', 'date']),
        ]);
    }

    /**
     * Show the form for creating a new bill.
     */
    public function create(Request $request)
    {
        $visit = null;
        if ($request->filled('visit_id')) {
            $visit = Visit::with('patient')->find($request->get('visit_id'));
        }

        $inventoryItems = InventoryItem::where('stock', '>', 0)->get();

        return Inertia::render('bills/create', [
            'visit' => $visit,
            'inventoryItems' => $inventoryItems,
        ]);
    }

    /**
     * Store a newly created bill.
     */
    public function store(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
        ]);

        $visit = Visit::findOrFail($request->visit_id);
        
        // Check if bill already exists for this visit
        if ($visit->bill) {
            return redirect()->route('bills.show', $visit->bill)
                ->with('info', 'Tagihan sudah ada untuk kunjungan ini.');
        }

        $bill = Bill::create([
            'visit_id' => $visit->id,
            'bill_number' => Bill::generateBillNumber(),
            'status' => 'pending',
        ]);

        return redirect()->route('bills.show', $bill)
            ->with('success', 'Tagihan berhasil dibuat.');
    }

    /**
     * Display the specified bill.
     */
    public function show(Bill $bill)
    {
        $bill->load(['visit.patient', 'items.inventoryItem']);
        $inventoryItems = InventoryItem::where('stock', '>', 0)->get();

        return Inertia::render('bills/show', [
            'bill' => $bill,
            'inventoryItems' => $inventoryItems,
        ]);
    }





    /**
     * Remove the specified bill.
     */
    public function destroy(Bill $bill)
    {
        if ($bill->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat dihapus.');
        }

        $bill->delete();

        return redirect()->route('bills.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
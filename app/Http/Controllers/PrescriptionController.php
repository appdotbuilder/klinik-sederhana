<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrescriptionItemRequest;
use App\Models\InventoryItem;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Visit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescriptions.
     */
    public function index(Request $request)
    {
        $query = Prescription::with(['visit.patient']);
        
        if ($request->filled('patient_id')) {
            $query->whereHas('visit', function ($q) use ($request) {
                $q->where('patient_id', $request->get('patient_id'));
            });
        }
        
        $prescriptions = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('prescriptions/index', [
            'prescriptions' => $prescriptions,
            'filters' => $request->only(['patient_id']),
        ]);
    }

    /**
     * Show the form for creating a new prescription.
     */
    public function create(Request $request)
    {
        $visit = null;
        if ($request->filled('visit_id')) {
            $visit = Visit::with('patient')->find($request->get('visit_id'));
        }

        $medications = InventoryItem::where('type', 'obat')->where('stock', '>', 0)->get();

        return Inertia::render('prescriptions/create', [
            'visit' => $visit,
            'medications' => $medications,
        ]);
    }

    /**
     * Store a newly created prescription.
     */
    public function store(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
        ]);

        $visit = Visit::findOrFail($request->visit_id);
        
        $prescription = Prescription::create([
            'visit_id' => $visit->id,
        ]);

        return redirect()->route('prescriptions.show', $prescription)
            ->with('success', 'Resep berhasil dibuat.');
    }

    /**
     * Display the specified prescription.
     */
    public function show(Prescription $prescription)
    {
        $prescription->load(['visit.patient', 'items.inventoryItem']);
        $medications = InventoryItem::where('type', 'obat')->where('stock', '>', 0)->get();

        return Inertia::render('prescriptions/show', [
            'prescription' => $prescription,
            'medications' => $medications,
            'allergyWarnings' => $prescription->allergy_warnings,
        ]);
    }



    /**
     * Remove the specified prescription.
     */
    public function destroy(Prescription $prescription)
    {
        $prescription->delete();

        return redirect()->route('prescriptions.index')
            ->with('success', 'Resep berhasil dihapus.');
    }
}
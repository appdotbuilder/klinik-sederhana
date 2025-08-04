<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VisitController extends Controller
{
    /**
     * Display a listing of visits.
     */
    public function index(Request $request)
    {
        $query = Visit::with(['patient', 'bill']);
        
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->get('patient_id'));
        }
        
        if ($request->filled('date')) {
            $query->whereDate('visit_date', $request->get('date'));
        }
        
        $visits = $query->latest('visit_date')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('visits/index', [
            'visits' => $visits,
            'filters' => $request->only(['patient_id', 'date']),
        ]);
    }

    /**
     * Show the form for creating a new visit.
     */
    public function create(Request $request)
    {
        $patient = null;
        if ($request->filled('patient_id')) {
            $patient = Patient::find($request->get('patient_id'));
        }

        return Inertia::render('visits/create', [
            'patient' => $patient,
        ]);
    }

    /**
     * Store a newly created visit.
     */
    public function store(StoreVisitRequest $request)
    {
        $data = $request->validated();
        $data['visit_date'] = now();
        
        $visit = Visit::create($data);

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Kunjungan berhasil dicatat.');
    }

    /**
     * Display the specified visit.
     */
    public function show(Visit $visit)
    {
        $visit->load([
            'patient',
            'prescriptions.items.inventoryItem',
            'bill.items.inventoryItem'
        ]);

        return Inertia::render('visits/show', [
            'visit' => $visit,
        ]);
    }

    /**
     * Show the form for editing the visit.
     */
    public function edit(Visit $visit)
    {
        $visit->load('patient');

        return Inertia::render('visits/edit', [
            'visit' => $visit,
        ]);
    }

    /**
     * Update the specified visit.
     */
    public function update(UpdateVisitRequest $request, Visit $visit)
    {
        $visit->update($request->validated());

        return redirect()->route('visits.show', $visit)
            ->with('success', 'Data kunjungan berhasil diperbarui.');
    }

    /**
     * Remove the specified visit.
     */
    public function destroy(Visit $visit)
    {
        $visit->delete();

        return redirect()->route('visits.index')
            ->with('success', 'Data kunjungan berhasil dihapus.');
    }
}
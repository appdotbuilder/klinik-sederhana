<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $query = Patient::query();
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('patient_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $patients = $query->withCount('visits')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('patients/index', [
            'patients' => $patients,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return Inertia::render('patients/create');
    }

    /**
     * Store a newly created patient.
     */
    public function store(StorePatientRequest $request, PatientService $patientService)
    {
        $data = $request->validated();
        
        // Generate patient ID if not provided
        if (!isset($data['patient_id'])) {
            $data['patient_id'] = $patientService->generatePatientId();
        }
        
        $patient = Patient::create($data);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Data pasien berhasil disimpan.');
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        $patient->load(['visits' => function ($query) {
            $query->with(['prescriptions.items.inventoryItem', 'bill.items.inventoryItem'])
                  ->latest('visit_date');
        }]);

        return Inertia::render('patients/show', [
            'patient' => $patient,
        ]);
    }

    /**
     * Show the form for editing the patient.
     */
    public function edit(Patient $patient)
    {
        return Inertia::render('patients/edit', [
            'patient' => $patient,
        ]);
    }

    /**
     * Update the specified patient.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $patient->update($request->validated());

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified patient.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }


}
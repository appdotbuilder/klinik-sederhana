<?php

namespace App\Services;

use App\Models\Patient;

class PatientService
{
    /**
     * Generate a unique patient ID.
     */
    public function generatePatientId(): string
    {
        $prefix = 'P';
        $date = now()->format('Ym');
        $sequence = Patient::whereDate('created_at', today())->count() + 1;
        
        return $prefix . $date . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
<?php

namespace Database\Seeders;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\InventoryItem;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Visit;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create inventory items first
        $medicines = InventoryItem::factory()->count(20)->create(['type' => 'obat']);
        $supplies = InventoryItem::factory()->count(15)->create(['type' => 'barang_lain']);
        $equipment = InventoryItem::factory()->count(10)->create(['type' => 'alat_medis']);
        
        // Create patients
        $patients = Patient::factory()->count(50)->create();
        
        // Create visits for some patients
        foreach ($patients->take(30) as $patient) {
            $visitCount = random_int(1, 3);
            
            for ($i = 0; $i < $visitCount; $i++) {
                $visit = Visit::factory()->create([
                    'patient_id' => $patient->id,
                    'visit_date' => now()->subDays(random_int(0, 30))->addHours(random_int(8, 17)),
                ]);
                
                // Create prescription for some visits
                if (random_int(1, 100) <= 70) { // 70% chance of prescription
                    $prescription = Prescription::create(['visit_id' => $visit->id]);
                    
                    // Add 1-3 medicines to prescription
                    $medicineCount = random_int(1, 3);
                    $selectedMedicines = $medicines->random($medicineCount);
                    
                    foreach ($selectedMedicines as $medicine) {
                        PrescriptionItem::create([
                            'prescription_id' => $prescription->id,
                            'inventory_item_id' => $medicine->id,
                            'quantity' => random_int(1, 10),
                            'dosage' => random_int(1, 3) . ' tablet',
                            'frequency' => random_int(1, 3) . 'x sehari',
                            'duration' => random_int(3, 14) . ' hari',
                            'instructions' => 'Diminum setelah makan',
                        ]);
                    }
                }
                
                // Create bill for some visits
                if (random_int(1, 100) <= 80) { // 80% chance of bill
                    $bill = Bill::create([
                        'visit_id' => $visit->id,
                        'bill_number' => Bill::generateBillNumber(),
                        'status' => 'pending',
                    ]);
                    
                    // Add 1-4 items to bill
                    $itemCount = random_int(1, 4);
                    $allItems = $medicines->concat($supplies)->concat($equipment);
                    $selectedItems = $allItems->random($itemCount);
                    
                    foreach ($selectedItems as $item) {
                        BillItem::create([
                            'bill_id' => $bill->id,
                            'inventory_item_id' => $item->id,
                            'quantity' => random_int(1, 5),
                            'unit_price' => $item->price,
                        ]);
                    }
                    
                    $bill->calculateTotals();
                    
                    // Pay some bills
                    if (random_int(1, 100) <= 70) { // 70% of bills are paid
                        $paidAmount = $bill->total + random_int(0, 20000); // Some change
                        $bill->processPayment($paidAmount);
                    }
                }
            }
        }
        
        $this->command->info('Clinic seed data created successfully!');
        $this->command->info('Patients: ' . Patient::count());
        $this->command->info('Visits: ' . Visit::count());
        $this->command->info('Inventory Items: ' . InventoryItem::count());
        $this->command->info('Prescriptions: ' . Prescription::count());
        $this->command->info('Bills: ' . Bill::count());
    }
}
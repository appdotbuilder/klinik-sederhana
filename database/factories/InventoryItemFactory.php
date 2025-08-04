<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['obat', 'barang_lain', 'alat_medis']);
        
        $names = [
            'obat' => [
                'Paracetamol 500mg',
                'Amoxicillin 500mg', 
                'Ibuprofen 400mg',
                'CTM 4mg',
                'Antasida Tablet',
                'Vitamin B Complex',
                'Antimo Tablet',
                'Bodrex Tablet',
                'Panadol Extra',
                'Omeprazole 20mg'
            ],
            'barang_lain' => [
                'Kapas Steril',
                'Perban Elastic',
                'Hansaplast',
                'Alkohol 70%',
                'Betadine Solution',
                'Masker Medis',
                'Sarung Tangan Sekali Pakai',
                'Thermometer Digital',
                'Tensimeter Manual',
                'Stetoskop'
            ],
            'alat_medis' => [
                'Syringe 3ml',
                'Syringe 5ml',
                'Jarum Suntik 23G',
                'Jarum Suntik 25G',
                'Spuit Insulin',
                'Lancet Pen',
                'Strip Glucose',
                'Kateter Urin',
                'NGT Size 16',
                'Infus Set'
            ]
        ];
        
        $units = [
            'obat' => ['tablet', 'kapsul', 'botol', 'strip'],
            'barang_lain' => ['pcs', 'roll', 'botol', 'box'],
            'alat_medis' => ['pcs', 'set', 'box', 'unit']
        ];

        return [
            'name' => $this->faker->randomElement($names[$type]),
            'code' => strtoupper($this->faker->bothify('??###')),
            'type' => $type,
            'description' => $this->faker->optional(0.6)->sentence(),
            'unit' => $this->faker->randomElement($units[$type]),
            'stock' => $this->faker->numberBetween(0, 100),
            'price' => $this->faker->numberBetween(1000, 50000),
            'minimal_alert' => $this->faker->numberBetween(5, 20),
        ];
    }
}
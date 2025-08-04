<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $complaints = [
            'Demam dan batuk',
            'Sakit kepala',
            'Mual dan muntah',
            'Diare',
            'Sakit perut',
            'Flu dan pilek',
            'Nyeri otot',
            'Sesak napas',
            'Pusing',
            'Lemas dan tidak bertenaga'
        ];

        $diagnoses = [
            'Common Cold',
            'Gastritis',
            'Hypertension',
            'Diabetes Mellitus Type 2',
            'Migraine',
            'Acute Gastroenteritis',
            'Upper Respiratory Tract Infection',
            'Tension Headache',
            'Dyspepsia',
            'Allergic Rhinitis'
        ];

        $treatments = [
            'Istirahat yang cukup, minum obat sesuai resep',
            'Diet rendah garam, kontrol tekanan darah',
            'Minum obat teratur, kontrol gula darah',
            'Kompres hangat, hindari stress',
            'Makan teratur, hindari makanan pedas',
            'Banyak minum air putih, istirahat total',
            'Hindari alergen, jaga kebersihan',
            'Obat simptomatik, follow up jika perlu'
        ];

        return [
            'patient_id' => Patient::factory(),
            'visit_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'chief_complaint' => $this->faker->randomElement($complaints),
            'diagnosis' => $this->faker->optional(0.8)->randomElement($diagnoses),
            'treatment' => $this->faker->optional(0.8)->randomElement($treatments),
            'notes' => $this->faker->optional(0.5)->sentence(),
            'follow_up_plan' => $this->faker->optional(0.4)->randomElement([
                'Kontrol 1 minggu lagi',
                'Kontrol 3 hari lagi jika tidak membaik',
                'Kontrol sesuai kebutuhan',
                'Follow up 2 minggu',
                'Kontrol rutin 1 bulan'
            ]),
            'status' => $this->faker->randomElement(['menunggu', 'selesai']),
        ];
    }
}
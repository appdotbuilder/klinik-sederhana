<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => 'P' . now()->format('Ym') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT),
            'name' => $this->faker->name(),
            'birth_date' => $this->faker->dateTimeBetween('-80 years', '-1 year'),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'phone' => $this->faker->optional(0.8)->phoneNumber(),
            'address' => $this->faker->optional(0.7)->address(),
            'allergies' => $this->faker->optional(0.3)->randomElement([
                'Penisilin, Aspirin',
                'Sulfa',
                'Ibuprofen',
                'Amoxicillin',
                'Seafood, Kacang',
            ]),
            'notes' => $this->faker->optional(0.2)->sentence(),
        ];
    }
}
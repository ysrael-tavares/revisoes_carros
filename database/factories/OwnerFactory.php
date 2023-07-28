<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Owner>
 */
class OwnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genderPortuguese = ['Homem', 'Mulher'];

        $genderEnglish = ['male', 'female'];

        $selected = rand(0,1);

        return [
            'name' => $this->faker->name($genderEnglish[$selected]),
            'gender' => $genderPortuguese[$selected],
            'date_of_birth' => $this->faker->dateTimeBetween('-30 years', '-18 years'),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}

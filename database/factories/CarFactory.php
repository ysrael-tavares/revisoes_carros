<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $models = [
            'Mustang',
            'Camry',
            'Corvette',
            'Civic',
            'Golf',
            'Benz C-Class',
            'Wrangler',
            'Outback',
            'Rogue',
            'Q5',
            'Tucson',
            'Sorento',
            '911',
            'Model S',
            'F-150',
            'Prius',
            'Silverado',
            'CR-V',
            'X5',
        ];

        $colors = [
            'Branco',
            'Preto',
            'Prata',
            'Azul',
            'Vermelho',
            'Verde',
            'Cinza',
            'Amarelo',
            'Laranja',
            'Marrom',
            'Dourado',
            'Roxo',
            'Rosa',
            'Bege',
            'Azul Marinho',
            'Cobre',
            'Turquesa',
            'Bronze',
            'Grafite',
            'Champanhe',
        ];

        $plate = $this->faker->regexify('[A-Z]{3}[0-9][A-Z][0-9]{2}');

        return [
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'owner_id' => Owner::inRandomOrder()->first()->id,
            'model' => $this->faker->randomElement($models),
            'plate' => $plate,
            'color' => $this->faker->randomElement($colors),
            'year_of_manufacture' => rand(1990, 2022),
        ];
    }
}

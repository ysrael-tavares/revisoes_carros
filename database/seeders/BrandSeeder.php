<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandList = [
            'Mercedes',
            'Volkswagem',
            'Chevrolet',
            'Fiat',
            'Peugeot'
        ];

        foreach ($brandList as $brand){
            Brand::create(['name' => $brand]);
        }
    }
}

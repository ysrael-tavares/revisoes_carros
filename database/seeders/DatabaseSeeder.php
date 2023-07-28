<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ysrael',
            'email' => 'ysraeltavares33@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        (new OwnerSeeder())->run();
        (new BrandSeeder())->run();
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            User::factory(10)->create();
            CompanySeeder::class,
            CategorySeeder::class,
        ]);
    }
}

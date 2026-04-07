<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed products only
        // $this->call([
        //     ProductSeeder::class,
        // ]);

        $this->call(CountryStateCityTableSeeder::class);
    }
}
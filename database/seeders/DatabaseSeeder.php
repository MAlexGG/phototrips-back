<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UsersSeeder::class);
        $this->call(ContinentsSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(PhotosSeeder::class);
        $this->call(CodeSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('countries')->insert([
            'id' => 1,
            'name' => 'Japón',
            'continent_id' => 3,
            'user_id' => 1,
            'created_at' => '2023/05/28'
        ]);

        DB::table('countries')->insert([
            'id' => 2,
            'name' => 'Ecuador',
            'continent_id' => 1,
            'user_id' => 2,
            'created_at' => '2023/05/28'
        ]);
    }
}

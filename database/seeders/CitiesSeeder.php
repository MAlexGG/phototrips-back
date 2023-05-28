<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cities')->insert([
            'id' => 1,
            'name' => 'Tokio',
            'country_id' => 1
        ]);

        DB::table('cities')->insert([
            'id' => 2,
            'name' => 'Puerto Ayora',
            'country_id' => 2
        ]);
    }
}

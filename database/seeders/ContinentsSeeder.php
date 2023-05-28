<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContinentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('continents')->insert([
            'id' => 1,
            'name' => 'América',
            'created_at' => '2023/05/28'
        ]);

        DB::table('continents')->insert([
            'id' => 2,
            'name' => 'Europa',
            'created_at' => '2023/05/28'
        ]);

        DB::table('continents')->insert([
            'id' => 3,
            'name' => 'Asia',
            'created_at' => '2023/05/28'
        ]);

        DB::table('continents')->insert([
            'id' => 4,
            'name' => 'Oceanía',
            'created_at' => '2023/05/28'
        ]);

        DB::table('continents')->insert([
            'id' => 5,
            'name' => 'África',
            'created_at' => '2023/05/28'
        ]);

        DB::table('continents')->insert([
            'id' => 6,
            'name' => 'Antártida',
            'created_at' => '2023/05/28'
        ]);
    }
}

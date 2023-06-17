<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('codes')->insert([
            'id' => 1,
            'code' => "2j2h83oi9wduq93e30djo902heidnsmolw0192e"
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhotosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('photos')->insert([
            'id' => 1,
            'name' => 'El Santuario flotante',
            'description' => 'Lorem ipsum',
            'image' => 'https://upload.wikimedia.org/wikipedia/commons/5/55/20131012_07_Miyajima_-_Torii_%2810491662566%29.jpg',
            'user_id' => 1,
            'continent_id' => 3,
            'country_id' => 1,
            'created_at' => '2023/03/26'
        ]);

        DB::table('photos')->insert([
            'id' => 2,
            'name' => 'Isla Bartolomé',
            'description' => 'Lorem ipsum',
            'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Bartoleme_Island.jpg/260px-Bartoleme_Island.jpg',
            'user_id' => 2,
            'continent_id' => 1,
            'country_id' => 2,
            'created_at' => '2023/03/26'
        ]);
    }
}

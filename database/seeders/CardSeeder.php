<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cards')->insert([
            [
                'title' => 'Moonlight',
                'image' => 'img/moon.jpg',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec interdum sodales est ac posuere. Morbi facilisis lorem est, ac volutpat massa pellentesque eu. Praesent feugiat lectus at blandit sodales. Maecenas vel erat at urna finibus fringilla.',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Waikiki beach',
                'image' => 'img/beach.jpg',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vitae dapibus nisi. Sed porta, felis in pulvinar lobortis, nisl odio viverra orci, eu porta arcu nulla sit amet justo. Sed est orci, euismod in aliquam et, tincidunt et augue.',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}

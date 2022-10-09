<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    static function searchByTitle($title)
    {
        $cards = DB::table('cards')->where('title', 'LIKE', "%$title%")->get();

        return $cards;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

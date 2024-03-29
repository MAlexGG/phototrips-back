<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    static function searchByName($name)
    {
        $continent = Continent::where('name', $name)->first();
        return $continent;
    }
}

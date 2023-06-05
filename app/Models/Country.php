<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'continent_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function continents()
    {
        return $this->belongsTo(Continent::class);
    }

    static function searchByName($name)
    {
        $country = Country::where('name', $name)->first();
        return $country;
    }

    static function orderByName()
    {
        $countries = Country::orderBy('name')->get();
        return $countries;
    }

    static function findCountriesByContinent($id)
    {
        $countries = Country::where('continent_id', $id)->get();
        return $countries;
    }
}

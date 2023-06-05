<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function countries()
    {
        return $this->belongsTo(Country::class);
    }

    static function searchByName($name)
    {
        $city = City::where('name', $name)->first();
        return $city;
    }

    static function orderByName()
    {
        $cities = City::orderBy('name')->get();
        return $cities;
    }

    static function findCitiesByCountry($id)
    {
        $cities = City::where('country_id', $id)->get();
        return $cities;
    }
}

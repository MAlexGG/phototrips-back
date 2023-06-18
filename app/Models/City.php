<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function countries()
    {
        return $this->belongsTo(Country::class);
    }

    static function searchByName($name)
    {
        $city = City::where('user_id', Auth::user()->id)->where('name', "=", $name)->first();
        return $city;
    }

    static function findCityByAuthUser($id)
    {
        $city = City::where('user_id', Auth::user()->id)->where('id', '=', $id)->first();
        return $city;
    }
    
    static function findCitiesByAuthUser()
    {
        $cities = City::where('user_id', Auth::user()->id)->orderBy('name')->get();
        return $cities;
    }

    static function findCitiesByCountry($id)
    {
        $cities = City::where('user_id', Auth::user()->id)->where('country_id', $id)->get();
        return $cities;
    }
}

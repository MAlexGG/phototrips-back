<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'continent_id',
        'user_id'
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

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    static function searchByName($name)
    {
        $country = Country::where('user_id', Auth::user()->id)->where('name', $name)->first();
        return $country;
    }

    static function findCountriesByAuthUser()
    {
        $countries = Country::where('user_id', Auth::user()->id)->orderBy('name')->get();
        return $countries;
    }

    static function findCountryByAuthUser($id)
    {
        $countries = Country::where('user_id', Auth::user()->id)->where('id', '=', $id)->first();
        return $countries;
    }

    static function findCountriesByContinent($id)
    {
        $countries = Country::where('continent_id', $id)->get();
        return $countries;
    }
}

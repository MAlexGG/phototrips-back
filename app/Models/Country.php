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
}

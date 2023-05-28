<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

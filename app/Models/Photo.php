<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    static function findPhotosByAuthUser()
    {
        $photos = Photo::where('user_id', Auth::user()->id)->get();
        return $photos;
    } 

    static function findPhotoByAuthUser($id)
    {
        $photo = Photo::where('id', $id)->where('user_id', Auth::user()->id)->first();
        return $photo;
    }

}

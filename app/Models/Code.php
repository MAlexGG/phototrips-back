<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    static function getCode()
    {
        $code = Code::where('id', '=' , 1)->first();
        return $code;
    }
}

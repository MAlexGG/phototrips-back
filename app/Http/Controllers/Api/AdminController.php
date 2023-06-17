<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function validateByAdmin(string $id)
    {
        if(Auth::user()->isAdmin){
            User::validate($id);
            return response()->json([
                'res' => true,
                "msg" => "Usuario ha sido validado correctamente",
            ], 200);
        } 

        return response()->json(["msg" => "No tienes authorización para validar usuarios"]);      
    }
}

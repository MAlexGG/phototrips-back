<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function getUsers()
    {
        $users = User::orderByName();

        if(Auth::user()->isAdmin)
        {
            if(count($users) == 0){
                return response()->json(['msg' => 'No existen usuarios en la base de datos']);
            }
            return response()->json($users, 200);
        }

        return response()->json(['msg' => 'No tienes autorización']);
    }

    public function validateByAdmin(string $id)
    {
        if(Auth::user()->isAdmin){
            User::validate($id);
            return response()->json([
                'res' => true,
                'msg' => 'Usuario ha sido validado correctamente',
            ], 200);
        } 

        return response()->json(['msg' => 'No tienes autorización para validar usuarios']);      
    }

    public function destroyUsers(string $id)
    {
        if(Auth::user()->isAdmin){
            
        }
    }
}

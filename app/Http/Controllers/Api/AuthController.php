<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'res' => true,
            'msg' => 'Usuario se ha registrado correctamente',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'res' => true,
                'msg' => 'Las credenciales son incorrectas'
            ]);
        }
        
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'res' => true,
            'msg' => 'Usuario identificado correctamente',
            'token' => $token,
            'user' => $user
        ], 200);
        
        
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'res' => true,
            'msg' => 'Usuario se ha desconectado correctamente'
        ], 200);
    }
}

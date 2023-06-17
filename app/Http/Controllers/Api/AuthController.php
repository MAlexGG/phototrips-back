<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            $user->isValidated = false;
            $user->save();

        return response()->json([
            "msg" => "Gracias por registrarte, tu administrador tiene que validar tu registro para poder acceder a la aplicación",
            "user" => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json(["msg" => "No existe un usuario con ese mail, por favor regístrate"]);
        }

        if($user->isValidated == false){
            return response()->json(["msg" => "Tu usuario no está validado, contacta a tu administrador"]); 
        }
        
        if(!Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'msg' => 'Las credenciales son incorrectas.',
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

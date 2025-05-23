<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
      info('Mensaje desde el controlador');


        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);



        return response()->json(['message' => 'Usuario creado con éxito'], 201);
    }

  public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');


       // Intentar autenticar y generar un token JWT
       try{

           if (!$token = JWTAuth::attempt($credentials)) {
               return response()->json(['error' => 'Credenciales inválidas'], 401);
            }   
            return response()->json(['token' => $token]);
        }catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }
    }

    public function user()
    {
        return response()->json(Auth::guard('api')->user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada correctamente',200    ]);
    }
}
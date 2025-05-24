<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @OA\Info(
 *     title="API de Autenticación",
 *     version="1.0.0",
 *     description="Controlador para registro, login y autenticación con JWT"
 * )
 *
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Endpoints de login, logout y registro"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registro de usuario",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", example="juan@email.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuario creado con éxito"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuario creado con éxito'], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="juan@email.com"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Token JWT retornado"),
     *     @OA\Response(response=401, description="Credenciales inválidas")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
            return response()->json(['token' => $token]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Obtener datos del usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description="Datos del usuario"),
     *     @OA\Response(response=401, description="Token inválido o ausente")
     * )
     */
    public function user()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cerrar sesión",
     *     tags={"Autenticación"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description="Sesión cerrada correctamente"),
     *     @OA\Response(response=401, description="Token inválido o ausente")
     * )
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
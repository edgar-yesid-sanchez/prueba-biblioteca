<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Libros",
 *     description="Operaciones CRUD sobre libros"
 * )
 */
class LibroController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/libros",
     *     summary="Obtener todos los libros",
     *     tags={"Libros"},
     *     @OA\Response(response=200, description="Lista de libros")
     * )
     */
    public function index()
    {
        return Libro::all();
    }

    /**
     * @OA\Post(
     *     path="/api/libros",
     *     summary="Crear un nuevo libro",
     *     tags={"Libros"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titulo","autor","genero"},
     *             @OA\Property(property="titulo", type="string", example="Cien años de soledad"),
     *             @OA\Property(property="autor", type="string", example="Gabriel García Márquez"),
     *             @OA\Property(property="genero", type="string", example="Ficción"),
     *             @OA\Property(property="disponible", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Libro creado exitosamente"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'genero' => 'required|string|max:100',
            'disponible' => 'boolean'
        ]);

        $libro = Libro::create($validated);
        return response()->json($libro, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/libros/{id}",
     *     summary="Obtener detalles de un libro",
     *     tags={"Libros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del libro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detalles del libro"),
     *     @OA\Response(response=404, description="Libro no encontrado")
     * )
     */
    public function show(Libro $libro)
    {
        return $libro;
    }

    /**
     * @OA\Put(
     *     path="/api/libros/{id}",
     *     summary="Actualizar un libro",
     *     tags={"Libros"},
     *    security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del libro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="titulo", type="string", example="Nuevo título"),
     *             @OA\Property(property="autor", type="string", example="Nuevo autor"),
     *             @OA\Property(property="genero", type="string", example="Ciencia ficción"),
     *             @OA\Property(property="disponible", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Libro actualizado"),
     *     @OA\Response(response=404, description="Libro no encontrado")
     * )
     */
    public function update(Request $request, Libro $libro)
    {
        $validated = $request->validate([
            'titulo' => 'string|max:255',
            'autor' => 'string|max:255',
            'genero' => 'string|max:100',
            'disponible' => 'boolean'
        ]);

        $libro->update($validated);
        return response()->json($libro);
    }

    /**
     * @OA\Delete(
     *     path="/api/libros/{id}",
     *     summary="Eliminar un libro",
     *     tags={"Libros"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del libro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Libro eliminado"),
     *     @OA\Response(response=404, description="Libro no encontrado")
     * )
     */
    public function destroy(Libro $libro)
    {
        $libro->delete();
        return response()->json(null, 204);
    }
}
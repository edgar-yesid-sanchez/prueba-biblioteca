<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Préstamos",
 *     description="Gestión de préstamos de libros"
 * )
 */
class PrestamoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/prestamos",
     *     summary="Obtener la lista de préstamos",
     *     tags={"Préstamos"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description="Lista de préstamos (según el rol del usuario)")
     * )
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return Prestamo::with(['libro', 'user'])->get();
        }

        return Prestamo::with(['libro'])
            ->where('user_id', $user->id)
            ->get();
    }

    /**
     * @OA\Post(
     *     path="/api/prestamos",
     *     summary="Crear un nuevo préstamo",
     *     tags={"Préstamos"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"libro_id", "fecha_prestamo", "fecha_devolucion"},
     *             @OA\Property(property="libro_id", type="integer", example=1),
     *             @OA\Property(property="fecha_prestamo", type="string", format="date", example="2025-05-24"),
     *             @OA\Property(property="fecha_devolucion", type="string", format="date", example="2025-05-31")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Préstamo creado correctamente"),
     *     @OA\Response(response=400, description="Libro no disponible")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'required|date|after:fecha_prestamo',
        ]);

        $libro = Libro::findOrFail($request->libro_id);
        if (!$libro->disponible) {
            return response()->json(['error' => 'Este libro no está disponible.'], 400);
        }

        $user = Auth::guard('api')->user();

        $prestamo = Prestamo::create([
            'user_id' => $user->id,
            'libro_id' => $request->libro_id,
            'fecha_prestamo' => $request->fecha_prestamo,
            'fecha_devolucion' => $request->fecha_devolucion,
            'estado' => 'pendiente',
        ]);

        $libro->update(['disponible' => false]);

        return response()->json($prestamo, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/prestamos/{id}",
     *     summary="Obtener un préstamo específico",
     *     tags={"Préstamos"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Datos del préstamo"),
     *     @OA\Response(response=404, description="No encontrado")
     * )
     */
    public function show(Prestamo $prestamo)
    {
        return $prestamo->load(['libro', 'user']);
    }

    /**
     * @OA\Put(
     *     path="/api/prestamos/{id}",
     *     summary="Actualizar un préstamo",
     *     tags={"Préstamos"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="fecha_devolucion", type="string", format="date", example="2025-06-01"),
     *             @OA\Property(property="estado", type="string", enum={"pendiente", "entregado"}, example="entregado")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Préstamo actualizado")
     * )
     */
    public function update(Request $request, Prestamo $prestamo)
    {
        $request->validate([
            'estado' => 'nullable|in:pendiente,entregado',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_prestamo',
        ]);

        $prestamo->update($request->only('fecha_devolucion', 'estado'));

        if ($request->estado === 'devuelto') {
            $prestamo->libro->update(['disponible' => true]);
        }

        return response()->json($prestamo);
    }

    /**
     * @OA\Delete(
     *     path="/api/prestamos/{id}",
     *     summary="Eliminar un préstamo",
     *     tags={"Préstamos"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Préstamo eliminado")
     * )
     */
    public function destroy(Prestamo $prestamo)
    {
        if ($prestamo->estado === 'pendiente') {
            $prestamo->libro->update(['disponible' => true]);
        }

        $prestamo->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/libros/{id}/ultimo-prestamo",
     *     summary="Consultar el último préstamo pendiente de un libro",
     *     tags={"Préstamos"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Último préstamo encontrado"),
     *     @OA\Response(response=404, description="No hay préstamo pendiente para ese libro")
     * )
     */
    public function ultimoPrestamo($id)
    {
        $prestamo = Prestamo::where('libro_id', $id)
            ->orderBy('fecha_prestamo', 'desc')
            ->where('estado', 'pendiente')
            ->first();

        if (!$prestamo) {
            return response()->json(['message' => 'Este libro no tiene préstamos registrados'], 404);
        }

        return response()->json($prestamo);
    }
}
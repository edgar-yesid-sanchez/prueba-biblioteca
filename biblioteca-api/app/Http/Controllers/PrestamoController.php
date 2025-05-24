<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestamoController extends Controller
{
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

    public function show(Prestamo $prestamo)
    {
        return $prestamo->load(['libro', 'user']);
    }

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

    public function destroy(Prestamo $prestamo)
    {
        if ($prestamo->estado === 'pendiente') {
            $prestamo->libro->update(['disponible' => true]);
        }

        $prestamo->delete();
        return response()->json(null, 204);
    }
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
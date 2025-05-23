<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function index()
    {
        return Prestamo::with(['libro', 'user'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
        ]);

        $libro = Libro::findOrFail($request->libro_id);
        if (!$libro->disponible) {
            return response()->json(['error' => 'Este libro no está disponible.'], 400);
        }

        $prestamo = Prestamo::create([
            'user_id' => $request->user_id,
            'libro_id' => $request->libro_id,
            'fecha_prestamo' => $request->fecha_prestamo,
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
            'fecha_devolucion' => 'nullable|date',
            'estado' => 'in:pendiente,devuelto'
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
}
<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index()
    {
        return Libro::all();
    }

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

    public function show(Libro $libro)
    {
        return $libro;
    }

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

    public function destroy(Libro $libro)
    {
        $libro->delete();
        return response()->json(null, 204);
    }
}
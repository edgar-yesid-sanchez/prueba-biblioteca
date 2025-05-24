<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Libro;
use App\Models\Prestamo;
use Carbon\Carbon;

class BibliotecaSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios
        $usuarios = [
            ['name' => 'Juan', 'email' => 'juan@email.com'],
            ['name' => 'Pedro', 'email' => 'pedro@email.com'],
            ['name' => 'Administrador', 'email' => 'admin@admin.com', 'role' => 'admin'],
        ];

        foreach ($usuarios as $data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'] ?? 'user',
                'password' => Hash::make('123456'),
            ]);
        }

        // Crear libros
        for ($i = 1; $i <= 15; $i++) {
            Libro::create([
                'titulo' => "Libro $i",
                'autor' => "Autor $i",
                'genero' => ['Ficción', 'Ciencia', 'Historia'][rand(0, 2)],
                'disponible' => true,
            ]);
        }

        // Crear préstamos
        $usuariosIds = User::where('role', 'user')->pluck('id')->toArray();
        $libros = Libro::all();

        foreach ($libros as $libro) {
            $estado = ['entregado', 'pendiente'][rand(0, 1)];

            Prestamo::create([
                'user_id' => $usuariosIds[array_rand($usuariosIds)],
                'libro_id' => $libros->random()->id,
                'fecha_prestamo' => now()->toDateString(),
                'fecha_devolucion' => now()->addDays(7)->toDateString(),
                'estado' => $estado,
            ]);

            // Marcar libro como no disponible si el préstamo está pendiente
            if ($estado === 'pendiente') {
                $libro->update(['disponible' => false]);
            }
        }

        // Prestamos completados
        for ($i = 1; $i <= 20; $i++) {
            $fechaPrestamo = Carbon::now()->subDays(rand(10, 30));
            $fechaDevolucion = (clone $fechaPrestamo)->addDays(7);
            Prestamo::create([
                'user_id' => $usuariosIds[array_rand($usuariosIds)],
                'libro_id' =>  $libros->random()->id,
                'fecha_prestamo' => $fechaPrestamo->toDateString(),
                'fecha_devolucion' => $fechaDevolucion->toDateString(),
                'estado' => 'entregado',
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Libro;

class BibliotecaSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 2 usuarios
        User::create([
            'name' => 'Usuario Uno',
            'email' => 'usuario1@ejemplo.com',
            'password' => Hash::make('123456'),
        ]);

        User::create([
            'name' => 'Usuario Dos',
            'email' => 'usuario2@ejemplo.com',
            'password' => Hash::make('123456'),
        ]);

        // Crear 10 libros
        for ($i = 1; $i <= 10; $i++) {
            Libro::create([
                'titulo' => "Libro $i",
                'autor' => "Autor $i",
                'genero' => ['Ficción', 'Ciencia', 'Historia'][rand(0, 2)],
                'disponible' => true,
            ]);
        }
    }
}

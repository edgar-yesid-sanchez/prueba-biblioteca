<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Libro;

class LibroFactory extends Factory
{
    protected $model = Libro::class;

    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'autor' => $this->faker->name,
            'genero' => $this->faker->randomElement(['Ficción', 'Historia', 'Ciencia']),
            'disponible' => true
        ];
    }
}
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Libro;
use App\Models\Prestamo;

class PrestamoTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_crear_prestamo()
    {
        $user = User::factory()->create();
        $libro = Libro::factory()->create(['disponible' => true]);

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/prestamos', [
            'libro_id' => $libro->id,
            'fecha_prestamo' => now()->toDateString(),
            'fecha_devolucion' => now()->addDays(7)->toDateString()
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('prestamos', [
            'user_id' => $user->id,
            'libro_id' => $libro->id
        ]);
    }

    public function test_no_se_puede_prestar_libro_no_disponible()
    {
      $this->withoutExceptionHandling();

      $user = User::factory()->create();
      $libro = Libro::factory()->create([
          'disponible' => false
      ]);

      $this->actingAs($user, 'api');

      $response = $this->postJson('/api/prestamos', [
          'libro_id' => $libro->id,
          'fecha_prestamo' => now()->toDateString(),
          'fecha_devolucion' => now()->addDays(7)->toDateString()
      ]);

      $response->assertStatus(400);
      $response->assertJson([
          'error' => 'Este libro no está disponible.'
      ]);
    }
  }

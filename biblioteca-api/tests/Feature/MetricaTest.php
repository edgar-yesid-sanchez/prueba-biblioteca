<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetricaTest extends TestCase
{
    use RefreshDatabase;

    public function test_consulta_metricas_solo_administradores()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        // Admin sí puede acceder
        $this->actingAs($admin, 'api');
        $response = $this->getJson('/api/metricas');
        $response->assertStatus(200);

        // Usuario normal no puede
        $this->actingAs($user, 'api');
        $response = $this->getJson('/api/metricas');
        $response->assertStatus(403);
        $response->assertJson(['error' => 'No autorizado']);
    }
}

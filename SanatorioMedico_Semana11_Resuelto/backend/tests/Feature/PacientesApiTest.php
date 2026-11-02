<?php

namespace Tests\Feature;

use App\Models\Paciente;
use App\Repositories\PacienteRepository;
use Mockery;
use Tests\TestCase;

/** Pruebas del contrato REST actual del CRUD de pacientes. */
class PacientesApiTest extends TestCase
{
    private function datos(): array
    {
        return [
            'nombres' => 'Ana', 'apellidos' => 'García',
            'documento_identificacion' => '1234 56789 0101',
            'fecha_nacimiento' => '2000-02-29', 'telefono' => '5555-0101',
            'correo' => 'ana@example.com', 'direccion' => 'Cuilapa',
            'contacto_emergencia' => 'Carlos - 5555-0102', 'estado' => true,
        ];
    }

    private function modelo(): Paciente
    {
        $modelo = new Paciente();
        $modelo->forceFill(array_merge($this->datos(), ['_id' => '507f1f77bcf86cd799439011']));
        return $modelo;
    }

    public function test_validacion_de_campos_obligatorios(): void
    {
        $this->postJson('/api/pacientes', [])->assertUnprocessable()
            ->assertJsonValidationErrors(['nombres', 'apellidos', 'documento_identificacion', 'fecha_nacimiento', 'telefono', 'direccion', 'contacto_emergencia', 'estado']);
    }

    public function test_listar_y_buscar_pacientes(): void
    {
        $repo = Mockery::mock(PacienteRepository::class);
        $repo->shouldReceive('obtenerTodos')->with('Ana')->once()->andReturn(collect([$this->modelo()]));
        $this->app->instance(PacienteRepository::class, $repo);
        $this->getJson('/api/pacientes?buscar=Ana')->assertOk()->assertJsonPath('0.nombres', 'Ana');
    }

    public function test_crear_paciente(): void
    {
        $repo = Mockery::mock(PacienteRepository::class);
        $repo->shouldReceive('crear')->once()->andReturn($this->modelo());
        $this->app->instance(PacienteRepository::class, $repo);
        $this->postJson('/api/pacientes', $this->datos())->assertCreated()->assertJsonPath('mensaje', 'Paciente creado correctamente.');
    }
}

<?php

namespace App\Repositories;

use App\Models\Paciente;
use MongoDB\BSON\Regex;

/**
 * PASO 3 - REPOSITORIO PACIENTES
 * Aquí se realizan las operaciones directas contra MongoDB.
 */
class PacienteRepository
{
    public function obtenerTodos(string $busqueda = '')
    {
        $query = Paciente::query();

        if ($busqueda !== '') {
            $regex = new Regex(preg_quote($busqueda), 'i');
            $query->where(function ($filtro) use ($regex) {
                foreach (['nombres', 'apellidos', 'documento_identificacion', 'telefono', 'correo', 'direccion', 'contacto_emergencia'] as $campo) {
                    $filtro->orWhere($campo, 'regex', $regex);
                }
            });
        }

        return $query->orderBy('apellidos')->orderBy('nombres')->get();
    }

    public function buscarPorId(string $id): Paciente
    {
        return Paciente::findOrFail($id);
    }

    public function crear(array $datos): Paciente
    {
        return Paciente::create($datos);
    }

    public function actualizar(string $id, array $datos): Paciente
    {
        $paciente = $this->buscarPorId($id);
        $paciente->update($datos);
        return $paciente->fresh();
    }

    public function eliminar(string $id): void
    {
        $this->buscarPorId($id)->delete();
    }
}

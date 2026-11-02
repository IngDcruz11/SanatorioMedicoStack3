<?php

namespace App\Repositories;

use App\Models\Medico;
use MongoDB\BSON\Regex;

/** PASO 3 - REPOSITORIO MÉDICOS: consultas CRUD y búsqueda en MongoDB. */
class MedicoRepository
{
    public function obtenerTodos(string $busqueda = '')
    {
        $query = Medico::query();
        if ($busqueda !== '') {
            $regex = new Regex(preg_quote($busqueda), 'i');
            $query->where(function ($filtro) use ($regex) {
                foreach (['nombres', 'apellidos', 'numero_colegiado', 'especialidad', 'telefono', 'correo', 'horario_atencion'] as $campo) {
                    $filtro->orWhere($campo, 'regex', $regex);
                }
            });
        }
        return $query->orderBy('apellidos')->orderBy('nombres')->get();
    }

    public function buscarPorId(string $id): Medico { return Medico::findOrFail($id); }
    public function crear(array $datos): Medico { return Medico::create($datos); }
    public function actualizar(string $id, array $datos): Medico
    {
        $medico = $this->buscarPorId($id);
        $medico->update($datos);
        return $medico->fresh();
    }
    public function eliminar(string $id): void { $this->buscarPorId($id)->delete(); }
}

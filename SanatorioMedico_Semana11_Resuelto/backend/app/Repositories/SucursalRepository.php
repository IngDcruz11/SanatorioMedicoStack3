<?php

namespace App\Repositories;

use App\Models\Sucursal;

/** PASO 3 - REPOSITORIO SUCURSALES: CRUD y búsqueda contra MongoDB. */
class SucursalRepository
{
    public function obtenerTodos(string $busqueda = '')
    {
        $query = Sucursal::query();
        if ($busqueda !== '') {
            $regex = new \MongoDB\BSON\Regex(preg_quote($busqueda), 'i');
            $query->where(function ($filter) use ($regex) {
                foreach (['nombre', 'direccion', 'telefono', 'correo', 'ciudad', 'departamento', 'tipo_sucursal'] as $campo) {
                    $filter->orWhere($campo, 'regex', $regex);
                }
            });
        }
        return $query->orderBy('nombre')->get();
    }

    public function buscarPorId(string $id)
    {
        return Sucursal::findOrFail($id);
    }

    public function crear(array $datos)
    {
        return Sucursal::create($datos);
    }

    public function actualizar(string $id, array $datos)
    {
        $sucursal = $this->buscarPorId($id);
        $sucursal->update($datos);
        return $sucursal->fresh();
    }

    public function eliminar(string $id): void
    {
        $sucursal = $this->buscarPorId($id);
        $sucursal->delete();
    }
}

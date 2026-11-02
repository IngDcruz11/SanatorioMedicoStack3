<?php

namespace App\Services;

use App\Repositories\SucursalRepository;

/** PASO 4 - SERVICIO SUCURSALES: comunica controlador y repositorio. */
class SucursalService
{
    public function __construct(
        private SucursalRepository $repository
    ) {}

    public function listar(string $busqueda = '')
    {
        return $this->repository->obtenerTodos($busqueda);
    }

    public function buscar(string $id)
    {
        return $this->repository->buscarPorId($id);
    }

    public function agregar(array $datos)
    {
        return $this->repository->crear($datos);
    }

    public function editar(string $id, array $datos)
    {
        return $this->repository->actualizar($id, $datos);
    }

    public function eliminar(string $id): void
    {
        $this->repository->eliminar($id);
    }
}

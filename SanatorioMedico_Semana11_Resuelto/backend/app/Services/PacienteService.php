<?php

namespace App\Services;

use App\Repositories\PacienteRepository;

/**
 * PASO 4 - SERVICIO PACIENTES
 * Separa la lógica del controlador y reutiliza el repositorio.
 */
class PacienteService
{
    public function __construct(private PacienteRepository $repository) {}

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

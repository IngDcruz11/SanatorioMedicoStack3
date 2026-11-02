<?php

namespace App\Services;

use App\Repositories\MedicoRepository;

/** PASO 4 - SERVICIO MÉDICOS: capa intermedia entre API y repositorio. */
class MedicoService
{
    public function __construct(private MedicoRepository $repository) {}
    public function listar(string $busqueda = '') { return $this->repository->obtenerTodos($busqueda); }
    public function buscar(string $id) { return $this->repository->buscarPorId($id); }
    public function agregar(array $datos) { return $this->repository->crear($datos); }
    public function editar(string $id, array $datos) { return $this->repository->actualizar($id, $datos); }
    public function eliminar(string $id): void { $this->repository->eliminar($id); }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PacienteRequest;
use App\Services\PacienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * PASO 5 - CONTROLADOR PACIENTES
 * Recibe las peticiones del frontend/Postman y devuelve respuestas JSON.
 */
class PacienteController extends Controller
{
    public function __construct(private PacienteService $service) {}

    public function index(Request $request): JsonResponse
    {
        $datos = $request->validate(['buscar' => ['nullable', 'string', 'max:255']]);
        return response()->json($this->service->listar(trim($datos['buscar'] ?? '')));
    }

    public function store(PacienteRequest $request): JsonResponse
    {
        $paciente = $this->service->agregar($request->validated());
        return response()->json(['mensaje' => 'Paciente creado correctamente.', 'datos' => $paciente], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json($this->service->buscar($id));
    }

    public function update(PacienteRequest $request, string $id): JsonResponse
    {
        $paciente = $this->service->editar($id, $request->validated());
        return response()->json(['mensaje' => 'Paciente actualizado correctamente.', 'datos' => $paciente]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->eliminar($id);
        return response()->json(['mensaje' => 'Paciente eliminado correctamente.']);
    }
}

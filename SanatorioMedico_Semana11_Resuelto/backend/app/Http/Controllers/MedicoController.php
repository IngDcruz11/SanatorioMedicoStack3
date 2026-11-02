<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicoRequest;
use App\Services\MedicoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** PASO 5 - CONTROLADOR MÉDICOS: endpoints consumidos por Vue y Postman. */
class MedicoController extends Controller
{
    public function __construct(private MedicoService $service) {}

    public function index(Request $request): JsonResponse
    {
        $datos = $request->validate(['buscar' => ['nullable', 'string', 'max:255']]);
        return response()->json($this->service->listar(trim($datos['buscar'] ?? '')));
    }

    public function store(MedicoRequest $request): JsonResponse
    {
        $medico = $this->service->agregar($request->validated());
        return response()->json(['mensaje' => 'Médico creado correctamente.', 'datos' => $medico], 201);
    }

    public function show(string $id): JsonResponse { return response()->json($this->service->buscar($id)); }

    public function update(MedicoRequest $request, string $id): JsonResponse
    {
        $medico = $this->service->editar($id, $request->validated());
        return response()->json(['mensaje' => 'Médico actualizado correctamente.', 'datos' => $medico]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->eliminar($id);
        return response()->json(['mensaje' => 'Médico eliminado correctamente.']);
    }
}

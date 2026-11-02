<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalRequest;
use App\Services\SucursalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/** PASO 5 - CONTROLADOR SUCURSALES: endpoints JSON para Vue/Postman. */
class SucursalController extends Controller
{
    public function __construct(
        private SucursalService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $datos = $request->validate(['buscar' => 'nullable|string|max:255']);
        return response()->json($this->service->listar(trim($datos['buscar'] ?? '')));
    }

    public function store(SucursalRequest $request): JsonResponse
    {
        $sucursal = $this->service->agregar($request->validated());

        return response()->json([
            'mensaje' => 'Sucursal creada correctamente.',
            'datos' => $sucursal
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json($this->service->buscar($id));
    }

    public function update(SucursalRequest $request, string $id): JsonResponse
    {
        $sucursal = $this->service->editar($id, $request->validated());

        return response()->json([
            'mensaje' => 'Sucursal actualizada correctamente.',
            'datos' => $sucursal
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->service->eliminar($id);

        return response()->json([
            'mensaje' => 'Sucursal eliminada correctamente.'
        ]);
    }
}

<?php

use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\SucursalController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PASO 6 - RUTAS API
|--------------------------------------------------------------------------
| Flujo solicitado por la tarea: Vue -> Laravel API -> MongoDB.
| Los tres módulos exponen consultar, buscar, agregar, editar y eliminar.
*/
Route::get('/ping', function () {
    try {
        DB::connection('mongodb')->command(['ping' => 1]);
        return response()->json(['ok' => true, 'mensaje' => 'Conexión correcta con MongoDB.']);
    } catch (\Throwable $e) {
        report($e);
        return response()->json(['ok' => false, 'mensaje' => 'No fue posible conectar con MongoDB.'], 503);
    }
});

Route::pattern('id', '[a-fA-F0-9]{24}');

foreach ([
    'sucursales' => SucursalController::class,
    'pacientes' => PacienteController::class,
    'medicos' => MedicoController::class,
] as $prefijo => $controlador) {
    Route::get("/$prefijo", [$controlador, 'index']);
    Route::post("/$prefijo", [$controlador, 'store']);
    Route::get("/$prefijo/{id}", [$controlador, 'show']);
    Route::put("/$prefijo/{id}", [$controlador, 'update']);
    Route::delete("/$prefijo/{id}", [$controlador, 'destroy']);
}

<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ColaboradorCatalogoController;
use App\Http\Controllers\EspecialidadCatalogoController;
Route::middleware('auth:sanctum')->group(function(){
 Route::get('/colaboradores',[ColaboradorCatalogoController::class,'index']);
 Route::get('/especialidades',[EspecialidadCatalogoController::class,'index']);
 Route::prefix('citas')->middleware('can:citas.manage')->group(function(){
  Route::get('/buscar',[CitaController::class,'buscar']);
  Route::get('/catalogos',[CitaController::class,'catalogos']);
  Route::get('/',[CitaController::class,'index']); Route::post('/',[CitaController::class,'store']);
  Route::get('/{id}',[CitaController::class,'show']); Route::put('/{id}',[CitaController::class,'update']); Route::patch('/{id}',[CitaController::class,'update']); Route::delete('/{id}',[CitaController::class,'destroy']);
 });
});

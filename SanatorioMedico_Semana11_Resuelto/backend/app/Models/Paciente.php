<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * PASO 1 - MODELO PACIENTES
 * Representa la colección "pacientes" de MongoDB.
 * $fillable limita los campos que pueden guardarse desde la API.
 */
class Paciente extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'pacientes';

    protected $fillable = [
        'nombres',
        'apellidos',
        'documento_identificacion',
        'fecha_nacimiento',
        'telefono',
        'correo',
        'direccion',
        'contacto_emergencia',
        'estado',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date:Y-m-d',
        'estado' => 'boolean',
    ];
}

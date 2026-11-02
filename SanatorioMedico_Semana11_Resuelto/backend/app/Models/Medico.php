<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/** PASO 1 - MODELO MÉDICOS: colección "medicos" en MongoDB. */
class Medico extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'medicos';

    protected $fillable = [
        'nombres', 'apellidos', 'numero_colegiado', 'especialidad',
        'telefono', 'correo', 'horario_atencion', 'estado',
    ];

    protected $casts = ['estado' => 'boolean'];
}

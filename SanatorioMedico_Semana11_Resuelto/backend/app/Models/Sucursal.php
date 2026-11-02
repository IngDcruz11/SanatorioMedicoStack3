<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Carbon\Carbon;

class Sucursal extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'sucursales';

    protected $attributes = ['estado' => true];

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'correo',
        'ciudad',
        'departamento',
        'fecha_apertura',
        'tipo_sucursal',
        'estado'
    ];

    protected $casts = [
        'fecha_apertura' => 'date',
        'estado' => 'boolean',
    ];

    /**
     * Mutador para asegurar que fecha_apertura se guarde como un objeto de fecha válido para MongoDB.
     */
    public function setFechaAperturaAttribute($value)
    {
        $this->attributes['fecha_apertura'] = $value ? Carbon::parse($value) : null;
    }
}
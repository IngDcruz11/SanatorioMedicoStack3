<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
class Cita extends Model {
    protected $connection='mongodb'; protected $table='Tbl_CitasConsultas'; public $timestamps=false;
    public const LONGITUDES=['TipoAtencion'=>30,'MotivoConsulta'=>500,'Sintomas'=>1000,'ObservacionesMedicas'=>1000,'TratamientoGeneral'=>1000,'PresionArterial'=>20,'Estado'=>30];
    public const OPCIONALES=['Sintomas','ObservacionesMedicas','TratamientoGeneral','PresionArterial','Temperatura','Peso'];
    protected $fillable=['CodigoPaciente','CodigoColaborador','CodigoSucursal','CodigoEspecialidad','FechaHoraCita','TipoAtencion','MotivoConsulta','Sintomas','ObservacionesMedicas','TratamientoGeneral','PresionArterial','Temperatura','Peso','Estado'];
    protected function casts(): array { return ['FechaHoraCita'=>'immutable_datetime','Temperatura'=>'float','Peso'=>'float']; }
}

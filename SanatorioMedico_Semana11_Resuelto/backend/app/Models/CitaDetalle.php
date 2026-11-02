<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
class CitaDetalle extends Model {
    protected $connection='mongodb'; protected $table='Tbl_CitasConsultasDetalle'; public $timestamps=false;
    public const LONGITUDES=['TipoDetalle'=>40,'SubtipoDetalle'=>100,'DescripcionDetalle'=>1000,'Dosis'=>100,'Frecuencia'=>100,'Duracion'=>100,'Indicaciones'=>500,'Resultado'=>1000,'Estado'=>20];
    public const OPCIONALES=['CodigoProducto','SubtipoDetalle','Dosis','Frecuencia','Duracion','Indicaciones','Resultado','Cantidad'];
    protected $fillable=['CodigoCitaConsulta','CodigoProducto','TipoDetalle','SubtipoDetalle','DescripcionDetalle','Dosis','Frecuencia','Duracion','Indicaciones','Resultado','Cantidad','FechaRegistro','Estado'];
    protected function casts(): array { return ['FechaRegistro'=>'immutable_datetime','Cantidad'=>'float']; }
}

<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
class EspecialidadCatalogo extends Model { protected $connection='mongodb'; protected $table='Tbl_Especialidades'; public $timestamps=false; }

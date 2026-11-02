<?php
namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;
class ColaboradorCatalogo extends Model { protected $connection='mongodb'; protected $table='Tbl_Colaboradores'; public $timestamps=false; }

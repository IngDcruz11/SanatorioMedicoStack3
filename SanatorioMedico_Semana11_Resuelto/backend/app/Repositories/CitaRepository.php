<?php
namespace App\Repositories;
use App\Models\Cita; use App\Models\CitaDetalle; use MongoDB\BSON\Regex; use MongoDB\BSON\ObjectId;
class CitaRepository {
 public function listar(array $f){ $q=Cita::query(); foreach(['Estado','CodigoPaciente','CodigoColaborador','CodigoSucursal','CodigoEspecialidad'] as $k) if(isset($f[$k])&&$f[$k]!=='') $q->where($k,in_array($k,['CodigoPaciente','CodigoColaborador','CodigoSucursal','CodigoEspecialidad'],true)?new ObjectId(strtolower($f[$k])):$f[$k]); if(isset($f['texto'])&&$f['texto']!==''){ $r=new Regex(preg_quote($f['texto']),'i'); $q->where(function($x)use($r){$x->orWhere('MotivoConsulta','regex',$r)->orWhere('Sintomas','regex',$r)->orWhere('TipoAtencion','regex',$r);}); } return $q->orderBy('FechaHoraCita','desc')->paginate($f['per_page']??15,['*'],'page',$f['page']??1); }
 public function obtener(string $id): Cita { return Cita::findOrFail($id); }
 public function detalles(string $id){ return CitaDetalle::where('CodigoCitaConsulta',new ObjectId(strtolower($id)))->orderBy('FechaRegistro')->get(); }
 public function crear(array $d): Cita { return Cita::create($d); }
 public function actualizar(string $id,array $d): Cita { $m=$this->obtener($id); $m->fill($d)->save(); return $m->fresh(); }
 public function reemplazarDetalles(string $id,array $lineas): void { CitaDetalle::where('CodigoCitaConsulta',new ObjectId(strtolower($id)))->delete(); foreach($lineas as $linea){$linea['CodigoCitaConsulta']=new ObjectId(strtolower($id)); CitaDetalle::create($linea);} }
 public function eliminarDetalles(string $id): void { CitaDetalle::where('CodigoCitaConsulta',new ObjectId(strtolower($id)))->delete(); }
 public function eliminar(string $id): void { $this->obtener($id)->delete(); }
}

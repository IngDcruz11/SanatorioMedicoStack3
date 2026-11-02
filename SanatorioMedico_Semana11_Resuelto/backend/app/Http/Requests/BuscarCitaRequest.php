<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class BuscarCitaRequest extends FormRequest {public function authorize():bool{return true;} public function rules():array{return ['texto'=>['sometimes','nullable','string','max:1000'],'Estado'=>['sometimes','nullable','string','max:30'],'CodigoPaciente'=>['sometimes','nullable','string','regex:/^[a-fA-F0-9]{24}$/'],'CodigoColaborador'=>['sometimes','nullable','string','regex:/^[a-fA-F0-9]{24}$/'],'CodigoSucursal'=>['sometimes','nullable','string','regex:/^[a-fA-F0-9]{24}$/'],'CodigoEspecialidad'=>['sometimes','nullable','string','regex:/^[a-fA-F0-9]{24}$/'],'page'=>['sometimes','integer','min:1'],'per_page'=>['sometimes','integer','min:1','max:100']];}}

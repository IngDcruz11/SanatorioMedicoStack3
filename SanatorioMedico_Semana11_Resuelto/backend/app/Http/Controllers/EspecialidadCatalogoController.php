<?php
namespace App\Http\Controllers;
use App\Repositories\CitaCatalogoRepository;
class EspecialidadCatalogoController extends Controller {public function __construct(private CitaCatalogoRepository $repo){} public function index(){return response()->json(['data'=>$this->repo->especialidades()]);}}

<?php
namespace App\Http\Controllers;
use App\Repositories\CitaCatalogoRepository;
class ColaboradorCatalogoController extends Controller {public function __construct(private CitaCatalogoRepository $repo){} public function index(){return response()->json(['data'=>$this->repo->colaboradores()]);}}

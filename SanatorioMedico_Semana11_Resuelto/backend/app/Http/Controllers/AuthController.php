<?php
namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}
    public function login(LoginRequest $request) { return response()->json($this->auth->login($request->validated()))->header('Cache-Control','no-store'); }
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) { $token->delete(); }
        return response()->noContent();
    }
    public function user(Request $request) { return response()->json(['user'=>$this->auth->datosUsuario($request->user())])->header('Cache-Control','no-store'); }
}

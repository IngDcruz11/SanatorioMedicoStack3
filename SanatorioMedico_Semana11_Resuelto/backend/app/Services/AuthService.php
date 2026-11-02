<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthService
{
    public function __construct(private User $users) {}
    public function login(array $credentials): array
    {
        $user = $this->users->newQuery()->where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages(['email'=>['El correo o la contraseña son incorrectos.']]);
        }
        $abilities = array_values(array_filter(['pacientes.manage', 'citas.manage'], fn ($permission) => in_array($permission, $user->permissions ?? [], true)));
        $token = $user->createToken('sanatorio-web', $abilities, now()->addHours(8));
        return ['token'=>$token->plainTextToken, 'token_type'=>'Bearer', 'user'=>$this->datosUsuario($user)];
    }
    public function datosUsuario($user): array
    {
        return ['id'=>(string) $user->getAuthIdentifier(), 'name'=>$user->name, 'email'=>$user->email];
    }
}

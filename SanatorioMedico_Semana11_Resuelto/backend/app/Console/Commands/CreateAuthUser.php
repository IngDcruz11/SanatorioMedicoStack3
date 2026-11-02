<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
class CreateAuthUser extends Command
{
    protected $signature = 'auth:create-user {--pacientes : Autorizar administración de pacientes}';
    protected $description = 'Crear una cuenta MongoDB mediante preguntas; contraseña oculta.';
    public function handle(): int
    {
        $name = $this->ask('Nombre'); $email = trim((string) $this->ask('Correo'));
        $password = $this->secret('Contraseña (mínimo 12 caracteres)');
        $validation = Validator::make(compact('name','email','password'), ['name'=>'required|string|max:100','email'=>'required|email|max:254','password'=>'required|string|min:12|max:1024']);
        if ($validation->fails()) { foreach ($validation->errors()->all() as $error) { $this->error($error); } return self::FAILURE; }
        if (User::where('email',$email)->exists()) { $this->error('Ya existe una cuenta con ese correo.'); return self::FAILURE; }
        $user = new User(compact('name','email','password'));
        $user->permissions = $this->option('pacientes') ? ['pacientes.manage'] : [];
        $user->save();
        $this->info('Cuenta creada.'); return self::SUCCESS;
    }
}

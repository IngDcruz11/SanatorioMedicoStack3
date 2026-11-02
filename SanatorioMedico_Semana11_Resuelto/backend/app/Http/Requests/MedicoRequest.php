<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** PASO 2 - VALIDACIÓN MÉDICOS: valida datos antes de escribir en MongoDB. */
class MedicoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $this->merge(['correo' => $this->correo === '' ? null : $this->correo]);
    }

    public function rules(): array
    {
        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'numero_colegiado' => ['required', 'string', 'max:30'],
            'especialidad' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'max:25'],
            'correo' => ['nullable', 'email', 'max:120'],
            'horario_atencion' => ['required', 'string', 'max:150'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'email' => 'Ingrese un correo electrónico válido.',
            'max' => 'No debe superar :max caracteres.',
            'boolean' => 'El estado debe ser activo o inactivo.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PASO 2 - VALIDACIÓN PACIENTES
 * Centraliza las reglas usadas al agregar y editar un paciente.
 */
class PacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Convierte cadenas vacías de campos opcionales a null antes de validar.
        $this->merge([
            'correo' => $this->correo === '' ? null : $this->correo,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'documento_identificacion' => ['required', 'string', 'max:30'],
            'fecha_nacimiento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'telefono' => ['required', 'string', 'max:25'],
            'correo' => ['nullable', 'email', 'max:120'],
            'direccion' => ['required', 'string', 'max:250'],
            'contacto_emergencia' => ['required', 'string', 'max:150'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'email' => 'Ingrese un correo electrónico válido.',
            'date_format' => 'La fecha debe tener formato AAAA-MM-DD.',
            'before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
            'max' => 'No debe superar :max caracteres.',
            'boolean' => 'El estado debe ser activo o inactivo.',
        ];
    }
}

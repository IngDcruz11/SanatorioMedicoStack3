<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SucursalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir la petición
    }
    /**
     * Prepara los datos antes de que pasen por la validación.
     */
    protected function prepareForValidation(): void
    {
        if ($this->fecha_apertura) {
            // Reemplaza las barras '/' por guiones '-' para que coincida con Y-m-d
            $this->merge([
                'fecha_apertura' => str_replace('/', '-', $this->fecha_apertura),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'correo' => 'nullable|email|max:255',
            'ciudad' => 'nullable|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'fecha_apertura' => 'nullable|date_format:Y-m-d',
            'tipo_sucursal' => 'nullable|string|max:255',
            'estado' => 'boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'string' => 'Debe ingresar un texto.',
            'max' => 'No debe superar :max caracteres.',
            'email' => 'Ingrese un correo electrónico válido.',
            'date_format' => 'Ingrese la fecha en formato año-mes-día.',
            'boolean' => 'El estado debe ser activo o inactivo.',
        ];
    }
}
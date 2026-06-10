<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * ¿El usuario actual tiene permiso de hacer esta petición?
     */
    public function authorize(): bool
    {
        // Solo usuarios autenticados pueden actualizar su perfil
        return $this->user() !== null;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email')
                    ->ignore($this->user()->id),
            ],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }

    /**
     * Mensajes de error personalizados (en español).
     */
    public function messages(): array
    {
        return [
            'name.required'   => 'El nombre es obligatorio.',
            'email.required'  => 'El correo electrónico es obligatorio.',
            'email.unique'    => 'Este correo ya está registrado.',
            'password.min'    => 'La contraseña debe tener mínimo 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}

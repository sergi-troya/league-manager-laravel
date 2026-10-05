<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StandingsRequest extends FormRequest
{
    // Las peticiones web inválidas regresan a una URL limpia sin bucles de redirección.
    protected $redirectRoute = 'standings.index';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'matchday' => ['bail', 'nullable', 'integer', 'min:1', Rule::exists('matchdays', 'number')],
        ];
    }

    public function messages(): array
    {
        return [
            'matchday.integer' => 'Selecciona una jornada con un número entero.',
            'matchday.min' => 'La jornada debe ser mayor o igual a 1.',
            'matchday.exists' => 'La jornada seleccionada no existe.',
        ];
    }

    public function matchdayNumber(): ?int
    {
        $number = $this->validated('matchday');

        return $number === null ? null : (int) $number;
    }

    /**
     * Devuelve respuesta JSON 422 para llamadas API/tests y redirección limpia para navegadores.
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            $response = response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ], 422);

            throw new ValidationException($validator, $response);
        }

        parent::failedValidation($validator);
    }
}
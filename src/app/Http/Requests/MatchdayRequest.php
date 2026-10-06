<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class MatchdayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'matchday' => [
                'bail',
                'nullable',
                'integer',
                'min:1',
                Rule::exists('matchdays', 'number'),
            ],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->route('matchday.index')
                ->with('error', 'La jornada solicitada no existe o tiene un formato inválido.')
                ->withErrors($validator)
        );
    }
}
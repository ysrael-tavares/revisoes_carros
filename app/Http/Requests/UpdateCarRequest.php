<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->hasUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'numeric'],
            'owner_id' => ['required', 'numeric'],
            'model' => ['required', 'string'],
            'plate' => ['required', 'string', Rule::unique('cars')->ignore($this->id)],
            'color' => ['required', 'string'],
            'year_of_manufacture' => ['required', 'numeric', 'max_digits:4'],
        ];
    }
}

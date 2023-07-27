<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'plate' => ['required', 'string', 'unique:'.Car::class],
            'color' => ['required', 'string'],
            'year_of_manufacture' => ['required', 'numeric', 'max_digits:4'],
        ];
    }
}

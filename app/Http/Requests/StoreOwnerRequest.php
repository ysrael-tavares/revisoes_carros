<?php

namespace App\Http\Requests;

use App\Models\Owner;
use Illuminate\Foundation\Http\FormRequest;

class StoreOwnerRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'date_of_birth' => ['required', 'date', 'before:'.date('Y-m-d')],
            'email' => ['required', 'email', 'unique:'.Owner::class ],
            'phone' => ['required', 'string'],
        ];
    }
}

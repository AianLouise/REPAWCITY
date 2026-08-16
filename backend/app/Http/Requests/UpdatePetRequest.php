<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:250'],
            'type' => ['sometimes', 'required', Rule::in(['Dog', 'Cat'])],
            'breed' => ['sometimes', 'required', 'string', 'max:250'],
            'sex' => ['sometimes', 'required', Rule::in(['Male', 'Female'])],
            'weight' => ['sometimes', 'required', 'string', 'max:250'],
            'age' => ['sometimes', 'required', 'string', 'max:250'],
            'date' => ['sometimes', 'required', 'date'],
            'about' => ['sometimes', 'required', 'string'],
            'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}

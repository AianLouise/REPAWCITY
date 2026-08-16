<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePetRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:250'],
            'type' => ['required', Rule::in(['Dog', 'Cat'])],
            'breed' => ['required', 'string', 'max:250'],
            'sex' => ['required', Rule::in(['Male', 'Female'])],
            'weight' => ['required', 'string', 'max:250'],
            'age' => ['required', 'string', 'max:250'],
            'date' => ['required', 'date'],
            'about' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}

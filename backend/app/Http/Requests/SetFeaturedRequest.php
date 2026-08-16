<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetFeaturedRequest extends FormRequest
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
            'featured_image_1' => ['required', 'integer'],
            'featured_image_2' => ['required', 'integer'],
            'featured_image_3' => ['required', 'integer'],
            'featured_image_4' => ['required', 'integer'],
        ];
    }
}

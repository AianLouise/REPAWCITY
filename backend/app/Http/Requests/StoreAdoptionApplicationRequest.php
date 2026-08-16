<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionApplicationRequest extends FormRequest
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
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'appointment_id' => ['nullable', 'integer', 'exists:appointments,id'],
            'answers' => ['required', 'array'],
            'answers.housing' => ['required', 'string', 'max:1000'],
            'answers.other_pets' => ['required', 'string', 'max:1000'],
            'answers.experience' => ['required', 'string', 'max:1000'],
            'answers.why_this_pet' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom error messages for the questionnaire.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'answers.housing.required' => 'Please describe your housing situation.',
            'answers.other_pets.required' => 'Please tell us about any other pets.',
            'answers.experience.required' => 'Please share your pet experience.',
            'answers.why_this_pet.required' => 'Please tell us why you want this pet.',
        ];
    }
}

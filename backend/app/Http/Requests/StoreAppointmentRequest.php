<?php

namespace App\Http\Requests;

use App\Models\Pet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
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
            'appointment_type' => ['required', Rule::in(['Adopt', 'Donate', 'Visit', 'Volunteer'])],
            'pet_id' => ['nullable', 'integer', 'exists:pets,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['required', Rule::in(['Morning Session', 'Afternoon Session'])],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:250'],
            'last_name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'home_address' => ['required', 'string', 'max:255'],
            'email_address' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    /**
     * After-validation hook: reject booking a pet that is not available.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $petId = $this->input('pet_id');
            if ($petId === null) {
                return;
            }

            $pet = Pet::find($petId);
            if ($pet === null) {
                return;
            }

            if (! in_array($pet->status, [Pet::STATUS_AVAILABLE, Pet::STATUS_ON_HOLD])) {
                $validator->errors()->add('pet_id', 'This pet is no longer available for booking.');
            }
        });
    }
}

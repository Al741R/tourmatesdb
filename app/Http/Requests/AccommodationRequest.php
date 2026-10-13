<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccommodationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plan_trip_id' => 'required|integer|exists:plan_trips,plan_trip_id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0|max:1000000',
            'room_type' => 'nullable|string|max:100',
            'contact_number' => 'nullable|digits_between:10,15',
            'review' => 'nullable|string|max:500',
            'rating' => 'nullable|numeric|min:0|max:5',
        ];
    }

    public function messages()
    {
        return [
            'contact_number.digits_between' => 'Contact number must be between 10 and 15 digits.',
            'rating.max' => 'Rating must not exceed 5 stars.',
        ];
    }
}

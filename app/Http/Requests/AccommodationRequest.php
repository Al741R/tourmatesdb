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
            'destination_id' => 'required|integer|exists:destinations,destination_id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'room_type' => 'nullable|string|max:100',
            'contact_number' => 'nullable|integer',
            'review' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:10',
        ];
    }
}

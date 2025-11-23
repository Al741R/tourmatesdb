<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plan_trip_id' => 'required|integer|exists:plan_trips,plan_trip_id',
            'accommodation_id' => 'nullable|integer|exists:accommodations,accommodation_id',
            'user_id' => 'nullable|integer|exists:users,id',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after_or_equal:check_in',
            'number_of_people' => 'nullable|integer|min:1',
            'price_per_night' => 'nullable|integer|min:0',
            'number_of_nights' => 'nullable|integer|min:1',
            'total_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:100',
        ];
    }
}

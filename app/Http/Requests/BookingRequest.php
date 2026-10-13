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
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after_or_equal:check_in',
            'number_of_people' => 'required|integer|min:1|max:50',
            'price_per_night' => 'required|integer|min:0|max:1000000',
            'status' => 'required|string|in:pending,confirmed,cancelled,completed',
        ];
    }

    public function messages()
    {
        return [
            'check_in.after_or_equal' => 'Check-in date must be today or a future date.',
            'check_out.after_or_equal' => 'Check-out date must be after or equal to check-in date.',
            'status.in' => 'Status must be one of: pending, confirmed, cancelled, completed.',
        ];
    }
}

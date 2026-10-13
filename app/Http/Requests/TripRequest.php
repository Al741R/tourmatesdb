<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            // the DB table remains `plan_trips` so keep exists rule referencing that table
            'destination_id' => 'required|integer|exists:destinations,destination_id',
            'user_id' => 'nullable|integer|exists:users,id',
            'price' => 'nullable|numeric|min:0|max:10000000',
            'category' => 'nullable|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'start_date.after_or_equal' => 'Trip start date must be today or a future date.',
            'end_date.after_or_equal' => 'Trip end date must be after or equal to start date.',
        ];
    }
}

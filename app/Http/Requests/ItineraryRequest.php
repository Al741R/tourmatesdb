<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItineraryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plan_trip_id' => 'required|integer|exists:plan_trips,plan_trip_id',
            'day' => 'nullable|integer|min:1',
            'title' => 'required|string|max:255',
            'activity' => 'nullable|string',
            'time' => 'nullable|string',
        ];
    }
}

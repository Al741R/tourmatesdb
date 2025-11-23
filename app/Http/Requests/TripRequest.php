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
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            // the DB table remains `plan_trips` so keep exists rule referencing that table
            'destination_id' => 'required|integer|exists:destinations,destination_id',
            'user_id' => 'nullable|integer|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:100',
        ];
    }
}

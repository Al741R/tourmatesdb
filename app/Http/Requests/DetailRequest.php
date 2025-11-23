<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailRequest extends FormRequest
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
            'itineraries_id' => 'nullable|integer|exists:itineraries,itineraries_id',
            'list_id' => 'nullable|integer|exists:pack_lists,pack_list_id',
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
        ];
    }
}

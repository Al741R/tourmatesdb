<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plan_trip_id' => 'required|integer|exists:plan_trips,plan_trip_id',
            'list_title' => 'required|string|max:255',
            'item' => 'required|string|max:255',
        ];
    }
}

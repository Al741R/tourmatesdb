<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentGcashDetailRequest extends FormRequest
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
            'payment_id' => ['required', 'integer', 'exists:payment_method,payment_id'],
            'booking_id' => ['required', 'integer', 'exists:bookings,booking_id'],
            'gcash_number' => ['required', 'string', 'max:15'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentCardDetailsRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'card_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'digits:16'],
            'expiry_date' => ['required', 'date_format:m/y'],
            'cvv' => ['required', 'digits:3'],
            'postal_code' => ['required', 'string', 'max:20'],
            'payment_id' => ['required', 'integer', 'exists:payment_method,payment_id'],
        ];
    }
}

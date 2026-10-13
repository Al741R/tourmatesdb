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
            // user_id is now set automatically from auth()->id() in controller
            'card_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'digits_between:13,19'], // Support various card types
            'expiry_date' => ['required', 'date_format:m/Y'], // MM/YYYY format
            'postal_code' => ['required', 'string', 'max:20'],
            'payment_id' => ['required', 'integer', 'exists:payment_method,payment_id'],
            // CVV removed - should be processed but never stored
        ];
    }

    public function messages(): array
    {
        return [
            'card_number.digits_between' => 'Card number must be between 13 and 19 digits.',
            'expiry_date.date_format' => 'Expiry date must be in MM/YYYY format.',
        ];
    }
}

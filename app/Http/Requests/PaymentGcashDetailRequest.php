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
            'gcash_number' => ['required', 'string', 'regex:/^09\d{9}$/'], // Philippine mobile format: 09XXXXXXXXX
        ];
    }

    public function messages(): array
    {
        return [
            'gcash_number.regex' => 'GCash number must be a valid Philippine mobile number (09XXXXXXXXX).',
        ];
    }
}

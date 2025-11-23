<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if (request()->routeIs('user.login') || request()->routeIs('auth.login')) {
            return [
                'email'     => 'required|string|email|max:255',
                'password'  => 'required|min:8',
            ];
        } else if (request()->routeIs('user.store') || request()->routeIs('users.store') || request()->routeIs('auth.signup')) {
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:App\Models\User,email|max:255',
                'password' => 'required|confirmed|min:8',
                'role' => 'required|string|in:user',
                'location' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ];
        } else if (request()->routeIs('user.update') || request()->routeIs('users.update')) {
            return [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:App\Models\User,email,' . $this->route('id'),
                'password' => 'sometimes|nullable|confirmed|min:8',
                'location' => 'sometimes|required|string|max:255',
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
            ];
        } else if (request()->routeIs('user.email')) {
            return [
                'email'     => 'required|string|email|max:255',
            ];
        } else if (request()->routeIs('user.password')) {
            return [
                'password'  => 'required|confirmed|min:8',
            ];
        }

        return [];
    }
    public function messages()
        {
            return [
                'password.confirmed' => 'Passwords do not match.',
                'email.unique' => 'This email is already registered.',
            ];
        }

}


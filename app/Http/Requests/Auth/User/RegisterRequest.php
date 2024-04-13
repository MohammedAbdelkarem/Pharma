<?php

namespace App\Http\Requests\Auth\User;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required' , 'unique:users,username'],
            'mobile' => ['required' ,  'unique:users,mobile' , 'phone:AUTO'],
            'password' => [
                'required' ,  
                Password::min(8)
                ->letters()
                ->numbers()
            ],
            'longitude' => ['numeric', 'between:-180,180'],
            'latitude' => ['numeric', 'between:-90,90'],
        ];
    }
}

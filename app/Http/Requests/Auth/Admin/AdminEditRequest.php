<?php

namespace App\Http\Requests\Auth\Admin;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class AdminEditRequest extends FormRequest
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
            'username' => ['unique:admins,username'],
            'mobile' => ['unique:admins,mobile' , 'phone:AUTO'],
            'password' => [ 
                Password::min(8)
                ->letters()
                ->numbers()
            ],
            'photo' => ['image' , 'mimes:png,jpg,jpeg,bmp,svg,gif'],
            'bio' => ['string' , 'max:200'],
            'longitude' => ['numeric', 'between:-180,180'],
            'latitude' => ['numeric', 'between:-90,90'],
        ];
    }
}

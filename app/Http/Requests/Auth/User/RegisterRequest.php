<?php

namespace App\Http\Requests\Auth\User;

use Illuminate\Validation\Rules\Password;
use App\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    use ResponseTrait;
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

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new HttpResponseException(
            $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'validation error' , $validator->errors()->toArray()))
        );
    }
}

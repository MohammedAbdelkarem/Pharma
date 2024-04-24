<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SubOrderRequest extends FormRequest
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
            'required_quantity' => ['required' , 'integer'],
            'medicine_id' => ['required' , 'integer' , 'min:1'],
            'admin_id' => ['required' , 'integer' , 'min:1'],
        ];
    }
}

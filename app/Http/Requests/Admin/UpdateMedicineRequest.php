<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineRequest extends FormRequest
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
            'scientific_name' => ['required'],
            'id' => ['required'],
            'manufacture_company' => ['required'],
            'available_quantity' => ['required' , 'integer' , 'min:0'],
            'Ed' => ['required' , 'date'],
            'price' => ['required' , 'min:0'],
            'photo' => ['image' , 'mimes:png,jpg,jpeg,bmp,svg,gif'],
            'category_id' => ['required' , 'integer' , 'min:0'],
            'trade_name' => [
                Rule::unique('medicines')->where(function ($query) {
                    $query->where('admin_id', admin_id());
                })->ignore($this->request->get('id'))
            ],
        ];
    }
}

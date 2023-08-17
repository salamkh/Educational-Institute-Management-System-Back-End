<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'phone' => 'required|numeric|starts_with:09',
                'password' => 'required',
            
        ];
    }
    public function messages()
    {
        return[
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.numeric' => '  يجب أن يكون رقم الهاتف عبارة عن أرقام فقط',
            'phone.starts_with' => 'رقم الهاتف يجب أن يبدأ ب 09',
            'password.required' => 'كلمة السر مطلوبة',
        ];
    }
}

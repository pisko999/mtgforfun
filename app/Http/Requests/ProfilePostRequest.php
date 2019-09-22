<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['alpha', 'max:255'],
            'forename' => ['alpha', 'max:255'],
            'country_code' => ['numeric', 'max:99999'],
            'phone' => ['regex:/^[0-9]{7,15}$/'],
            'address' => ['numeric'],
        ];
    }
}

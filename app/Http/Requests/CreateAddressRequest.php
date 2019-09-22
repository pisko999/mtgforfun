<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
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
            'street' => ['required','alpha', 'max:255'],
            'number' => ['required','alpha_num', 'max:16'],
            'flat' => ['alpha_num', 'max:16'],
            'city' => ['required','alpha', 'max:255'],
            'country' => ['required','alpha', 'max:255'],
            'region' => ['alpha', 'max:255'],
            'postal' => ['required','alpha_num', 'max:16'],
        ];
    }
}

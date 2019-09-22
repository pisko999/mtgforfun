<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartConfirmRequest extends FormRequest
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
            'address' => ['nullable', 'numeric', 'max:999'], // exists
            'billing_address' => ['nullable', 'numeric', 'max:999'], // exists
            'payment' => ['required', 'in:transfer,cash', 'max:999'], // exists
        ];
    }
}

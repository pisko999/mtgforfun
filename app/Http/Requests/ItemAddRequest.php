<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemAddRequest extends FormRequest
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
            'id' => ['nullable', 'numeric'],
            'quantity' => ['required', 'numeric', 'max:255'],
            'stock_id' => ['nullable', 'numeric'],
            'price' => ['nullable', 'numeric', 'max:999999'],
        ];
    }
}

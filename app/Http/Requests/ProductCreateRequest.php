<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'numeric', 'max:99'],
            'price' => ['required', 'numeric', 'max:999999'],
            'lang' => ['required', 'regex:/^[a-zA-Z]{2}$/'],
            'image' => ['nullable', 'image'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockAddRequest extends FormRequest
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
            'edition_id' => ['numeric', 'max:999'],
            'page' => ['numeric', 'max:999'],
            'quantity' => ['numeric', 'required', 'max:99'],
            'price' => ['numeric', 'required', 'max:999999'],
            'state' => ['in:MT,NM,EX,GD,LP,PL,PO']
        ];
    }
}

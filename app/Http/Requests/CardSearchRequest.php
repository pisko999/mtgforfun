<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardSearchRequest extends FormRequest
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

            'searchTextHidden' => ['alpha', 'max:255'],
            'searchedText' => ['alpha', 'max:255'],
            'edition' => ['required', 'numeric', 'max:999'],
            'color' => ['nullable'],
            'color.*' =>[ 'in:white,blue,black,red,green,multi,colorless'],
            'rarity' => ['required', 'alpha_num', 'in:0,C,U,R,M'],
            'foil' => ['required', 'numeric', 'in:-1,0,1'],
            'mkm' => ['required', 'numeric', 'in:0,1,2'],
            'lang' => ['required', 'regex:/^[a-zA-Z]{2}|0$/'],
            'onlyStock' => ['alpha', 'regex:/^on$/'],
            ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditionAddRequest extends FormRequest
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
            'cards_count' => ['required', 'numeric', 'max:999'],
            'sign' => ['required', 'alpha', 'max:9'],
            'type' => ['required', 'alpha', 'max:32'],
            'release_date' => ['nullable', 'date'],
        ];
    }
}

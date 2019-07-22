<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
        // required_without_all:code,capital,currencies,languages|  //doesn't seem to work as advertised  investigate later
        return [
            'name'  => 'sometimes|required_without_all:code,capital,currencies,languages|max:120',
            'code'  => 'sometimes|max:120',
            'capital'  => 'sometimes|max:120',
            'currencies'  => 'sometimes|max:120',
            'languages'  => 'sometimes|max:120'
        ];
    }

    public function messages()
    {
        return [
            'name.max' => "I'm sorry but the name input may be no longer than 120 characters",
            'code.max' => "I'm sorry but the code input may be no longer than 120 characters",
            'capital.max' => "I'm sorry but the capital input may be no longer than 120 characters",
            'currencies.max' => "I'm sorry but the currencies input may be no longer than 120 characters",
            'languages.max' => "I'm sorry but the languages input may be no longer than 120 characters"
        ];
    }
}

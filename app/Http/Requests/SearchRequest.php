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
        return [
            'country_name'  => 'max:120|alpha',
            'country_code'  => 'max:120|alpha',
            'capital_city'  => 'max:120|alpha',
            'currency_code'  => 'max:120|alpha',
            'language'  => 'max:120|alpha'
        ];
    }

    public function messages()
    {
        return [
            'country_name.alpha' => "The search string must contain letters only",
            'country_name.max' => "I'm sorry but the search string may be no longer than 120 characters",
            'country_code.alpha' => "The search string must contain letters only",
            'country_code.max' => "I'm sorry but the search string may be no longer than 120 characters",
            'capital_city.alpha' => "The search string must contain letters only",
            'capital_city.max' => "I'm sorry but the search string may be no longer than 120 characters",
            'currency_code.alpha' => "The search string must contain letters only",
            'currency_code.max' => "I'm sorry but the search string may be no longer than 120 characters",
            'language.alpha' => "The search string must contain letters only",
            'language.max' => "I'm sorry but the search string may be no longer than 120 characters"
        ];
    }
}

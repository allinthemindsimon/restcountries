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
            'name'  => 'max:120|alpha|nullable',
            'code'  => 'max:120|alpha|nullable',
            'capital'  => 'max:120|alpha|nullable',
            'currencies'  => 'max:120|alpha|nullable',
            'languages'  => 'max:120|alpha|nullable'
        ];
    }

    public function messages()
    {
        return [
            'name.alpha' => "The search input must contain letters only",
            'name.max' => "I'm sorry but the search input may be no longer than 120 characters",
            'code.alpha' => "The search input must contain letters only",
            'code.max' => "I'm sorry but the search input may be no longer than 120 characters",
            'capital.alpha' => "The search input must contain letters only",
            'capital.max' => "I'm sorry but the search input may be no longer than 120 characters",
            'currencies.alpha' => "The search input must contain letters only",
            'currencies.max' => "I'm sorry but the search input may be no longer than 120 characters",
            'languages.alpha' => "The search input must contain letters only",
            'languages.max' => "I'm sorry but the search input may be no longer than 120 characters"
        ];
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'capital',
        'region',
        'timezones',
        'code_2',
        'code_3',
        'calling_codes',
        'flag_location'
    ];

    public function currencyLinks()
    {
        return $this->hasMany('App\CountryCurrencyLink');
    }

    public function languageLinks()
    {
        return $this->hasMany('App\CountryLanguageLink');
    }
}

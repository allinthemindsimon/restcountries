<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryLanguageLink extends Model
{
    protected $fillable = [
        'country_id',
        'language_id'
    ];

    public function country()
    {
        return $this->hasOne('App\Country', 'id', 'country_id');
    }

    public function language()
    {
        return $this->hasOne('App\Language', 'id', 'language_id');
    }
}

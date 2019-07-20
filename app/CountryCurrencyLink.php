<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryCurrencyLink extends Model
{
    protected $fillable = [
        'country_id',
        'currency_id'
    ];

    public function country()
    {
        return $this->hasOne('App\Country', 'id', 'country_id');
    }

    public function currency()
    {
        return $this->hasOne('App\Currency', 'id', 'currency_id');
    }
}

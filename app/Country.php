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
        'currencies',
        'languages',
        'flag_location'
    ];
}

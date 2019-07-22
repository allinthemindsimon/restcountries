<?php

namespace App\Http\Controllers;

use App\Country;
use App\Http\Requests\SearchRequest;  //can't seem to get the external request validator to work as advetised, look at later

class CountryController extends Controller
{
    protected $url = "https://restcountries.eu/rest/v2/";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('country/search');
    }

    /**
     * Search Post.
     */
    public function search(SearchRequest $request)
    {
        //Check DB for existence of search data. ((Could break this into neater functions later, maybe make them services))

        //MySQL does not like "like statements" with "%%" means doing this the hard way
        //get where clause straight. Ready for direct injection into Laravel syntax

        //Start query for the '=' values
        $whereStart = "";
        $bindingsStart = "";
        //Continue query for the 'LIKE' values
        $whereCont = [];
        foreach ($request->all() as $key => $val) {
            if ($key[1] == 'o' && $val) {
                $val = htmlspecialchars(strip_tags($val));
                $whereStart = "`code_2` = '?' OR `code_3` = '?'";
                $bindingsStart = $val . ', ' . $val;
            }
            if ($key[0] != '_' && $key[1] != 'o' && $val) {
                $whereCont[$key] = htmlspecialchars(strip_tags($val));
            }
        }
        //put the two parts of the query together
        $whereClause = "";
        $bindingsLike = "";
        $first = true;
        foreach ($whereCont as $key => $val) {
            if ($first) {
                $whereClause .= "`$key` LIKE '?' ";
                $bindingsLike .= "%$val%";
                $first = false;
            } else {
                $whereClause .= " OR `$key` LIKE '?' ";
                $bindingsLike .= ", %$val%";
            }
        }
        if ($whereStart && $whereClause) {
            $whereClause = $whereStart . ' OR ' . $whereClause;
            $bindings = $bindingsStart . ', ' . $bindingsLike;
        } else {
            $whereClause = $whereStart . $whereClause;
            $bindings = $bindingsStart . $bindingsLike;
        }

        //get data from database.
        $country = Country::whereRaw($whereClause, $bindings)->get();

        if (count($country) === 1) {
            $data = $country->toArray();
            return $this::show($data[0]);
        }
        if (count($country) === 0) { //could rewrite this as a switch
            if ($request->name) {
                $req = $request->name;
                $data = json_decode(file_get_contents($this->url . 'name/' . $req));
                if (count($data) === 1) {
                    return $this::store($data[0]);
                };
            }
            if ($request->code) {
                $req = $request->code;
                $data = json_decode(file_get_contents($this->url . 'alpha/' . $req));
                if (count($data) === 1) {
                    return $this::store($data[0]);
                };
            }
            if ($request->capital) {
                $req = $request->capital;
                $data = json_decode(file_get_contents($this->url . 'capital/' . $req));
                if (count($data) === 1) {
                    return $this::store($data[0]);
                };
            }
            if ($request->currencies) {
                $req = $request->currencies;
                $data = json_decode(file_get_contents($this->url . 'currency/' . $req));
                if (count($data) === 1) {
                    return $this::store($data[0]);
                };
            }
            if ($request->languages) {
                $req = $request->languages;
                $data = json_decode(file_get_contents($this->url . 'lang/' . $req));
                if (count($data) === 1) {
                    return $this::store($data[0]);
                };
            }
            abort(404);
        };
    }

    function sanitiseInput($stringToBeSanitised)
    {
        $stringToBeSanitised = trim($stringToBeSanitised);
        $stringToBeSanitised = stripslashes($stringToBeSanitised);
        $stringToBeSanitised = htmlspecialchars($stringToBeSanitised);
        return $stringToBeSanitised;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($data)
    {
        //extract and tidy currency codes
        $currencyCodes = '';
        $first = true;
        foreach ($data->currencies as $currency) {
            if ($first) {
                $currencyCodes = $currency->code;
                $first = false;
            } else {
                $currencyCodes .= ", $currency->code";
            }
        }

        //extract and tidy language names
        $languages = '';
        $first = true;
        foreach ($data->languages as $language) {
            if ($first) {
                $languages = $language->name;
                $first = false;
            } else {
                $languages .= ", $language->name";
            }
        }

        $country = new Country;
        $country->name = $this->sanitiseInput($data->name);
        $country->capital = $this->sanitiseInput($data->capital);
        $country->region = $this->sanitiseInput($data->region);
        $country->timezones = $this->sanitiseInput(implode(", ", $data->timezones));
        $country->code_2 = $this->sanitiseInput($data->alpha2Code);
        $country->code_3 = $this->sanitiseInput($data->alpha3Code);
        $country->calling_codes = $this->sanitiseInput(implode(", ", $data->callingCodes));
        $country->currencies = $this->sanitiseInput($currencyCodes);
        $country->languages = $this->sanitiseInput($languages);
        //we could download the flag to a local file but cloud is probably better for now
        $country->flag_location = $this->sanitiseInput($data->flag);
        $country->save();
        $data = $country->toArray();
        return $this::show($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show($data)
    {
        return view('country.country', ['data' => $data]);
    }
}

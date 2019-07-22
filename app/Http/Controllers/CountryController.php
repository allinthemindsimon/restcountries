<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;  //can't seem to get the external request validator to work, look at later

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
        //Check DB for existence of search data. 

        //MySQL does not like "like statements" with "%%"
        //get where clause straight. Ready for direct injection into Laravel syntax

        //Start query for the '=' values
        $whereStart = "";
        //Continue query for the 'LIKE' values
        $whereCont = [];
        foreach ($request->all() as $key => $val) {
            if ($key[1] == 'o' && $val) {
                $val = htmlspecialchars(strip_tags($val));
                $whereStart = "`code_2` = '$val' OR `code_3` = '$val'";
            }
            if ($key[0] != '_' && $key[1] != 'o' && $val) {
                $whereCont[$key] = htmlspecialchars(strip_tags($val));
            }
        }
        //put the two parts of the query together
        $whereClause = "";
        foreach ($whereCont as $key => $val) {
            $whereClause .= "`$key` LIKE '%$val%' ";
        }
        if ($whereStart && $whereClause) {
            $whereClause = $whereStart . ' OR ' . $whereClause;
        } else {
            $whereClause = $whereStart . $whereClause;
        }
        //********************->setBindings([add bindings for PDO here once query working]) or after whereRaw variable and comma */
        // \DB::enableQueryLog();
        $country = Country::whereRaw($whereClause)->get();
        // dd(\DB::getQueryLog());
        if (count($country) === 1) {
            $data = $country->toArray();
            return $this::show($data[0]);
        }
        if (count($country) === 0) {
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
            return back();
        };
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
        $country->name = $data->name;
        $country->capital = $data->capital;
        $country->region = $data->region;
        $country->timezones = implode(", ", $data->timezones);
        $country->code_2 = $data->alpha2Code;
        $country->code_3 = $data->alpha3Code;
        $country->calling_codes = implode(", ", $data->callingCodes);
        $country->currencies = $currencyCodes;
        $country->languages = $languages;
        $country->flag_location = $data->flag;
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
    public function show($data = 'hello view')
    {
        return view('country.country', ['data' => $data]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;  //can't seem to get the external request validator to work, look at later

class CountryController extends Controller
{
    protected $url = "https://restcountries.eu/rest/v2/";

    /**
     * Search Post.
     */
    public function search(SearchRequest $request)
    {
        // dd($request->all());
        //Check DB for existence of search data. 
        //MySQL does not like "like statements" with "%%"

        // \DB::enableQueryLog();
        // dd($request->all());

        $whereStart = "";
        $whereCont = [];
        foreach ($request->all() as $key => $val) {
            if ($key[1] == 'o' && $val) {
                $val = htmlentities(strip_tags($val));
                $whereStart = "`code_2` = '$val' OR `code_3` = '$val'";
            }
            if ($key[0] != '_' && $key[1] != 'o' && $val) {
                $whereCont[$key] = htmlentities(strip_tags($val));
            }
        }
        $whereClause = "";
        foreach ($whereCont as $key => $val) {
            $whereClause .= " OR `$key` LIKE '%$val%'";
        }
        $whereClause = $whereStart . $whereClause;
        // $where = "$key, 'like', $val";
        // dd($whereClause);


        //get where clause straight. Ready for direct injection into Laravel syntax


        //********************->setBindings([add bindings for PDO here once query working]) or after whereRaw variable and comma */
        $country = Country::whereRaw($whereClause)->get();
        // dd(\DB::getQueryLog());
        dd($country);
        if (count($country) === 1) {
            $data = $country->toArray();
            dd($data);
            return Redirect::action('CountryController@show', $data);
        }
        if (empty($country)) {
            if ($request->country_name) {
                $data = json_decode(file_get_contents($this->url . 'name/' . $request->country_name));
                if (count($data) === 1) {
                    return $this::store($data);
                    return Redirect::action('CountryController@show', $data);
                };
            }
            if ($request->country_code) {
                $data = json_decode(file_get_contents($this->url . 'alpha/' . $request->country_code));
                if (count($data) === 1) {
                    return $this::store($data);
                    return view('country.country', ['data' => $data]);
                };
            }
            if ($request->capital_city) {
                $data = json_decode(file_get_contents($this->url . 'capital/' . $request->capital_city));
                if (count($data) === 1) {
                    return $this::store($data);
                    return Redirect::action('CountryController@show', $data);
                };
            }
            if ($request->currency_code) {
                $data = json_decode(file_get_contents($this->url . 'currency/' . $request->currency_code));
                if (count($data) === 1) {
                    return $this::store($data);
                    return Redirect::action('CountryController@show', $data);
                };
            }
            if ($request->language) {
                $data = json_decode(file_get_contents($this->url . 'lang/' . $request->language));
                if (count($data) === 1) {
                    return $this::store($data);
                    return Redirect::action('CountryController@show', $data);
                };
            }
            $default = "Please be more specific";
            return $default;
        };

        // dd(empty($country)); //return country data to blade
        //1- If not exist, go to https://restcountries.eu/rest/v2/ to get data,   ----WIP---
        //2- Add to database - done ----check-----
        //3- Reurn to Blade - done -----check----
        // return view('country/search');
    }

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($data)
    {
        $currencies = http_build_query($data->currencies, "", "\n");
        dd($currencies);
        $country = new Country;
        $country->name = $data->name;
        $country->capital = $data->capital;
        $country->region = $data->region;
        $country->timezones = implode(", ", $data->timezones);
        $country->code_2 = $data->alpha2Code;
        $country->code_3 = $data->alpha3Code;
        $country->calling_codes = implode(", ", $data->callingCodes);
        $country->currencies = http_build_query($data->currencies, "", "\n");
        $country->languages = http_build_query($data->languages, "", "\n");
        $country->flag_location = $data->flag;
        $country->save();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show($data)
    {
        dd($data);
        $view = View::make('data', $this->data);
    }
}

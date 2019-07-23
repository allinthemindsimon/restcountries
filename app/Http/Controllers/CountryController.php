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
                $whereStart = "`code_2` = ? OR `code_3` = ?";
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
                $whereClause .= "`$key` LIKE ? ";
                $bindingsLike .= "%$val%";
                $first = false;
            } else {
                $whereClause .= " OR `$key` LIKE ? ";
                $bindingsLike .= ",%$val%";
            }
        }
        if ($whereStart && $whereClause) {
            $whereClause = $whereStart . ' OR ' . $whereClause;
            $bindings = $bindingsStart . ', ' . $bindingsLike;
        } else {
            $whereClause = $whereStart . $whereClause;
            $bindings = $bindingsStart . $bindingsLike;
        }
        $bindings = explode(',', $bindings);
        //API seems to search alt spellings as well, so added name + alt spellings to DB and change name to search that in DB
        $whereClause = str_replace("name", "alt_spellings", $whereClause);
        //get data from database.
        $country = Country::whereRaw($whereClause, $bindings)->get();

        if (count($country) === 1) {
            $data = $country->toArray();
            return $this::show($data[0]);
        }
        if (count($country) > 1) {
            return back()->withErrors(['There is more than one country with those parameters']);
        }
        //nothing in the DB, off to the outside World to search for data
        if (count($country) === 0) { //could rewrite this as a switch
            if ($request->name) {
                $name = $this->getDataFromAPI('name', $request->name . '?fullText=true');
                if ($name['status'] === 'success') {
                    return $this::show($name[0]);
                }
                $name = $this->getDataFromAPI('name', $request->name);
                if ($name['status'] === 'success') {
                    return $this::show($name[0]);
                }
                return back()->withErrors([$name[0]]);
            }
            if ($request->code) {
                $code = $this->getDataFromAPI('alpha', $request->code);
                if ($code['status'] === 'success') {
                    return $this::show($code[0]);
                }
                return back()->withErrors([$code[0]]);
            }
            if ($request->capital) {
                $capital = $this->getDataFromAPI('capital', $request->capital);
                if ($capital['status'] === 'success') {
                    return $this::show($capital[0]);
                }
                return back()->withErrors([$capital[0]]);
            }
            if ($request->currencies) {
                $currencies = $this->getDataFromAPI('currency', $request->currencies);
                if ($currencies['status'] === 'success') {
                    return $this::show($currencies[0]);
                }
                return back()->withErrors([$currencies[0]]);
            }
            if ($request->languages) {
                $languages = $this->getDataFromAPI('lang', $request->languages);
                if ($languages['status'] === 'success') {
                    return $this::show($languages[0]);
                }
                return back()->withErrors([$languages[0]]);
            }
            return back()->withErrors(['There is not a country with those parameters']);
        };
    }

    function getDataFromAPI($urlParam, $requestParam)
    {
        if ($this->get_http_response_code($this->url . $urlParam . '/' . $requestParam) != '200') {
            return ['status' => 'error', 'There is not a country with those parameters'];
        } else {
            $data = json_decode(file_get_contents($this->url . $urlParam . '/' . $requestParam));
            if (count($data) === 1) {
                $insert = $this::store($data);
                return ['status' => 'success', $insert];
            } else {
                return ['status' => 'error', 'There is more than one country with those parameters'];
            }
        }
    }

    function get_http_response_code($url)
    {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
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
        //check if data coming through as the first member of an array or not
        //the API send through in different formats depending on search parameter
        if (is_array($data)) {
            $data = $data[0];
        }
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
        $country->alt_spellings = $this->sanitiseInput($data->name) . ', ' . $this->sanitiseInput(implode(", ", $data->altSpellings));
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
        return $data;
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

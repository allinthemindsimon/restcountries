<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
// use App\Http\Requests\SearchRequest;  //can't seem to get the external request validator to work, look at later

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
    public function search(Request $request)
    {
        //Check DB for existence of search data. 
        $country = Country::with(["language", "currency"])->where("name", "like", "%$request->country_name%")
            ->orWhere("code_2", "like", "%$request->country_code%")
            ->orWhere("code_3", "like", "%$request->country_code%")
            ->orWhere("capital", "like", "%$request->capital_city%")
            ->first();
        // ->orWhere("currency_code", "=", "$request->currency_code")
        // ->orWhere("language", "like", "%$request->language%")
        if (empty($country)) {
            if ($request->country_name) {
                $response = json_decode(file_get_contents($this->url . 'name/' . $request->country_name));
                if (count($response) === 1) {
                    //add data to database
                    return $response;
                };
            }
            if ($request->country_code) {
                $response = json_decode(file_get_contents($this->url . 'alpha/' . $request->country_code));
                if (count($response) === 1) {
                    dd($response);
                    //add data to database
                    return $response;
                };
            }
            if ($request->capital_city) {
                $response = json_decode(file_get_contents($this->url . 'capital/' . $request->capital_city));
                if (count($response) === 1) {
                    //add data to database
                    return $response;
                };
            }
            if ($request->currency_code) {
                $response = json_decode(file_get_contents($this->url . 'currency/' . $request->currency_code));
                if (count($response) === 1) {
                    //add data to database
                    return $response;
                };
            }
            if ($request->language) {
                $response = json_decode(file_get_contents($this->url . 'lang/' . $request->language));
                if (count($response) === 1) {
                    //add data to database
                    return $response;
                };
            }
            $response = "Please be more specific";
            return $response;
        };

        dd(empty($country)); //return country data to blade
        //1- If not exist, go to https://restcountries.eu/rest/v2/ to get data, 
        //2- Add to database
        //3- Reurn to Blade
        return view('country/search');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        //
    }
}

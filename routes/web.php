<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
//no need to secure these for this app.
Route::get('/home', 'CountryController@index')->name('country');
Route::get('/country', 'CountryController@show');
Route::post('/country/search', 'CountryController@search');

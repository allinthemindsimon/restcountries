@extends('layouts.header')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8"><h1>Country Information</h1></div><div class="col-md-4">Country Flag<img class="flag" src='{{$data['flag_location'] ?? ''}}'></div>
                    </div>
                </div>
                <div class="card-body form-group">
                    <form>
                        <div class="row mb-1">
                            <div class="col-md-4">Country Name</div><div class="col-md-8"><input class="form-control" type='text' name='country_name' value='{{$data['name'] ?? ''}}'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">International Dialling Code</div><div class="col-md-8"><input class="form-control" type='text' name='calling-codes' value='{{$data['calling_codes'] ?? ''}}'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Region</div><div class="col-md-8"><input class="form-control" type='text' name='region' value='{{$data['region'] ?? ''}}'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Capital</div><div class="col-md-8"><input class="form-control" type='text' name='capital' value='{{$data['capital'] ?? ''}}'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Timezones</div><div class="col-md-8"><input class="form-control" type='text' name='timezones' value='{{$data['timezones'] ?? ''}}'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Currencies</div><div class="col-md-8"><input class="form-control" type='text' name='currencies' value='{{$data['currencies'] ?? ''}}'></div>
                        </div>
                    </form>
                        <hr class="mb-4">
                        <div class="row mb-1">
                            <div class='col-md-12'>
                                <button onclick="window.location='/home'" class="btn btn-primary btn-lg btn-block">Back to Search Page</button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
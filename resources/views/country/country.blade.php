@extends('layouts.header')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8"><h1>Country Information</h1></div><div class="col-md-4">Flag</div>
                    </div>
                </div>
                {{dd($data)}}
                <div class="card-body form-group">
                    <form>
                        <div class="row mb-1">
                            <div class="col-md-4">Country Name</div><div class="col-md-6"><input class="form-control" type='text' name='country_name'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">International Dialling Code</div><div class="col-md-6"><input class="form-control" type='text' name='country_code'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Region</div><div class="col-md-6"><input class="form-control" type='text' name='capital_city'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Capital</div><div class="col-md-6"><input class="form-control" type='text' name='currency_code'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Timezone</div><div class="col-md-6"><input class="form-control" type='text' name='language'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Currencies</div><div class="col-md-6"><input class="form-control" type='text' name='language'></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
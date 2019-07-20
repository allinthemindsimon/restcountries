@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>Country Search</h1>
                </div>
                @if(session()->has('cowmessage'))
                    <div class="message is-success temp" id="cowmessage">
                        <p>{{ session()->get() }}</p>
                    </div>
                @endif
                <div class="card-body form-group">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action='/country/search' method='POST'>
                        {{ csrf_field() }}
                        <div class="row mb-1">
                            <div class="col-md-3">Country Name</div><div class="col-md-6"><input class="form-control" type='text' name='country_name'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3">Country Code</div><div class="col-md-6"><input class="form-control" type='text' name='country_code'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3">Capital City</div><div class="col-md-6"><input class="form-control" type='text' name='capital_city'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3">Currency Code</div><div class="col-md-6"><input class="form-control" type='text' name='currency_code'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3">Language</div><div class="col-md-6"><input class="form-control" type='text' name='language'></div>
                        </div>
                        <hr class="mb-4">
                        <div class="row mb-1">
                            <div class='col-md-12'>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
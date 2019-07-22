@extends('layouts.header')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>Country Search</h1>
                </div>
                <div class="card-body form-group">
                    @if (session('status'))
                        <div class="alert alert-warning temp" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action='/country/search' method='POST'>
                        {{ csrf_field() }}
                        <div class="row mb-1">
                            <div class="col-md-4">Country Name</div><div class="col-md-6"><input class="form-control" type='text' name='name'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Country Code</div><div class="col-md-6"><input class="form-control" type='text' name='code'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Capital City</div><div class="col-md-6"><input class="form-control" type='text' name='capital'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Currency Code</div><div class="col-md-6"><input class="form-control" type='text' name='currencies'></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">Language</div><div class="col-md-6"><input class="form-control" type='text' name='languages'></div>
                        </div>
                        <hr class="mb-4">
                        <div class="row">
                            <div class='col-md-12'>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Search</button>
                            </div>
                        </div>
                    </form>
                    @if ($errors->any())
                    <div class="col-md-12 alert-warning alert-block temp">
                        <div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
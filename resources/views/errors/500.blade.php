@extends('welcome')
@section('content')
<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-lg-8 lg-offset-2">
            <div class="clearfix">
                <h1 class="float-left display-3 mr-4">{{ Lang::get('errors.500') }}</h1>
                <h4 class="pt-3">{{ Lang::get('errors.500_title') }}</h4>
                <p>{{ Lang::get('errors.500_message') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
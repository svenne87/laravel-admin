@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="profile-tablist">
                <b-card no-body>
                    <b-tabs pills card vertical nav-wrapper-class="w-20">
                        <b-tab title="{{ Lang::get('general.profile') }}" active>
                            <profile-details :user="{{ Auth::user() }}"></profile-details>                          
                        </b-tab>
                        <b-tab title="{{ Lang::get('general.account') }}">
                            <profile-form :user="{{ Auth::user() }}"></profile-form>
                        </b-tab>
                    </b-tabs>
                </b-card>  
            </div>
        </div>
    </div>
</div>
@endsection

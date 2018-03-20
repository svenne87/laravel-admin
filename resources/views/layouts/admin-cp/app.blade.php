@extends('master')
@section('content')
<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-12">
            <div class="text-center">
                <transition name="fade" mode="out-in">
                    <router-view></router-view>
                </transition>
                <vue-progress-bar></vue-progress-bar>
            </div>
        </div>
    </div>
</div>
@endsection
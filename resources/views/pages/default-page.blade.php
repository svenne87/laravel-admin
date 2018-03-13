@extends('welcome')
@section('content')
<div class="container h-100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-md-12">
            <h1 class="text-center">{{ $page->title }}</h1>
            <div class="text-center">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
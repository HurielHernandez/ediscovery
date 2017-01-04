@extends('pragmarx/tracker::html')

@section('body')
    <div class="container">
        <div class="jumbotron">
            <h1>{{ $message }}</h1>
            <br>
            <p>
                <a class="btn btn-lg btn-primary" href="/admin" role="button">@lang('tracker::tracker.go_home')</a>
            </p>
        </div>
    </div>
@stop

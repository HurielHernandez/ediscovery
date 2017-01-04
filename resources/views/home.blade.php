@extends('master')

@section('page_header')
    <h1 class="page-title">

    </h1>
@stop


@section('content')
@if(!empty($message))
<div class="alert alert-danger" role="alert">{{$message}}</div>
@endif
<div class="page-content container-fluid">


    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
              

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


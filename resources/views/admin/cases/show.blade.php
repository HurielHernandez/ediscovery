@extends('voyager::master')

@section('head')
<link rel="stylesheet" href="/css/jquery.fileupload.css">
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i> Viewing Case {{$case->case_number}}
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                       <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">Case</h3>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <p>{{$case->case_number}}</p>
                        </div><!-- panel-body -->
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">Name</h3>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <p>{{$case->first_name}} {{$case->last_name}}</p>
                        </div>
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">JN</h3>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <p>{{$case->jn}}</p>
                        </div>
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">SID</h3>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <p>{{$case->sid}}</p>
                        </div>
                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">Offense Description</h3>
                        </div>
                        <div class="panel-body" style="padding-top:0;">
                            <p>{{$case->offense_description}}</p>
                        </div>

                </div>
                
                <table class="table">

                <tr>
                    <td>
                        <a href="/admin/cases/{{$case->id}}/files" class="btn btn-sm btn-primary ">
                           View Files
                        </a>
                    </td>
                    <td>
                        <a href="/admin/cases/{{$case->id}}/users" class="btn btn-sm btn-primary">
                           View Users
                        </a>
                    </td>
                </tr>
     
                </table>
            </div>

        </div>
        
@stop

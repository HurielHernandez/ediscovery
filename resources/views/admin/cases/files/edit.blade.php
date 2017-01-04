@extends('voyager::master')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-file-text"></i>Edit File
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <form role="form" action="/admin/cases/{{$case->id}}/files/{{$file->id}}/edit" method="POST" enctype="multipart/form-data">
            <!-- PUT Method if we are editing -->

                {{ method_field("Post") }}
   
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-12">
                    <!-- ### TITLE ### -->

                            <div class="form-group col-md-4">
                                <label for='original_name'>File Name</label>
                                <input type="text" class="form-control" name="original_name" placeholder="" value="{{$file->original_name}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for='mime'>Extension</label>
                                <input type="text" class="form-control" name="mime" placeholder="" value="{{$file->mime}}">
                            </div>
                            <br>
                             <button type="submit" class="btn btn-primary pull-left">
                                Update File
                            </button>
               

                    
                </div>
        </form>
    </div>
@stop

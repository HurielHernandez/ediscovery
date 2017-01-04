@extends('voyager::master')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i>Edit Case
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <form role="form" action="/admin/cases/{{$case->id}}/update" method="POST" enctype="multipart/form-data">
            <!-- PUT Method if we are editing -->
            @if(isset($case->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <!-- ### TITLE ### -->
                    <div class="panel">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for='case_number'>Case Number</label>
                                <input type="text" class="form-control" name="case_number" placeholder="Case Number" value="{{$case->case_number}}">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>First Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="John" value="{{$case->first_name}}">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>Last Name</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Doe" value="{{$case->last_name}}">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>JN</label>
                                <input type="text" class="form-control" name="jn" placeholder="" value="{{$case->jn}}">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>SID</label>
                                <input type="text" class="form-control" name="sid" placeholder="" value="{{$case->sid}}">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>Offense Description</label>
                                <textarea class="form-control" name="offense_description">{{$case->offense_description}}</textarea>
                            </div>
                        </div>
                    </div>

                     <button type="submit" class="btn btn-primary pull-right">
                        @if(isset($case->id)){{ 'Update Case' }}@else<?= '<i class="icon wb-plus-circle"></i> Create New Case'; ?>@endif
                     </button>
                </div>
                

      
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="/admin/cases/{{$case->id}}/update" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $case->slug }}">
        </form>
    </div>
@stop

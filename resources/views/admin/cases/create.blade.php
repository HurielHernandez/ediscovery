@extends('voyager::master')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i>New Case
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <form role="form" action="/admin/cases/create" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <!-- ### TITLE ### -->
                    <div class="panel">
                        <div class="panel-body">
                            <div class="form-group">
                                <label for='case_number'>Case Number</label>
                                <input type="text" class="form-control" name="case_number" placeholder="Case Number" value="">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>First Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="John" value="">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>Last Name</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Doe" value="">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>JN</label>
                                <input type="text" class="form-control" name="jn" placeholder="" value="">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>SID</label>
                                <input type="text" class="form-control" name="sid" placeholder="" value="">
                            </div>
                            <div class="form-group">
                                <label for='defendant'>Offense Description</label>
                                <textarea class="form-control" name="offense_description"></textarea>
                            </div>
                        </div>
                    </div>

                     <button type="submit" class="btn btn-primary pull-right">
                        <i class="icon wb-plus-circle"></i> Create New Case
                     </button>
                </div>
      
        </form>

    </div>
@stop

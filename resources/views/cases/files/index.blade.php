@extends('master')

@section('head')
<!-- <link rel="stylesheet" href="/css/jquery.fileupload.css"> -->
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i> Viewing Files from Case {{$case->case_number}}
    </h1>
@stop

@section('page_header_actions')

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
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            <h3 class="panel-title">
                <i class="voyager-file-text"></i> Files
            </h3>
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Extension</th>
                                    <th>Uploaded</th>
                                    <th>Downloads Left</th>
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($files))        
                                @foreach($files as $file)
                                    <tr>  
                                        <td>
                                           {{$file->original_name}}
                                        </td>
                                        <td>
                                            {{ $file->mime }}
                                        </td>
                                        <td>
                                            {{$file->created_at->format('M d, Y - H:i:s')}}
                                        </td>
                                        <td>
                                            {{$file->count() }}
                                        </td>
                                        @if( $file->count() )
                                        <td class="no-sort no-click">
                                            <a href="/cases/{{$case->id}}/files/{{$file->id}}/download" class="btn-sm btn-success pull-right">
                                                <i class="voyager-download"></i> Download
                                            </a>
                                        </td>
                                        @else
                                        <td class="no-sort no-click">
                                            <a href="/cases/{{$case->id}}/files/{{$file->id}}/request" class="btn-sm btn-warning pull-right">
                                                <i class=""></i> Request
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <!-- DataTables -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({ "order": [] });
        });

        function parseActionUrl(action, id) {
            return action.match(/\/[0-9]+$/)
                ? action.replace(/([0-9]+$)/, id)
                : action + '/' + id;
        }
    </script>
@stop
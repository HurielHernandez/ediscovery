@extends('voyager::master')

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
                <a href="/admin/cases/{{$case->id}}/files/upload" class="btn btn-success">
                    <i class="voyager-plus"></i> Upload
                </a>
            </h3>
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Extension</th>
                                    <th>Uploaded</th>
                                    <th>Downloaded</th>
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
                                            {{2 - $file->count()  }}
                                        </td>
                                        <td class="no-sort no-click">
                                            <div class="btn-sm btn-danger pull-right delete" data-id="{{ $file->id }}" id="delete-{{ $file->id }}">
                                                <i class="voyager-trash"></i> Delete
                                            </div>
                                            <a href="/admin/cases/{{$case->id}}/files/{{$file->id}}/edit" class="btn-sm btn-primary pull-right edit">
                                                <i class="voyager-edit"></i> Edit
                                            </a>
                                            <a href="/admin/cases/{{$case->id}}/files/{{$file->id}}/download" class="btn-sm btn-success pull-right">
                                                <i class="voyager-download"></i> Download
                                            </a>
                                        </td>
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
    @if(!empty($file))
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Are you sure you want to delete this File?</h4>
                </div>
                <div class="modal-footer">
                    <form action="/admin/cases/{{$case->id}}/files/{{$file->id}}" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="Yes, Delete This File">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @endif

@stop

@section('javascript')
    <!-- DataTables -->
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({ "order": [] });
        });

        $('td').on('click', '.delete', function (e) {
            var form = $('#delete_form')[0];

            form.action = parseActionUrl(form.action, $(this).data('id'));

            $('#delete_modal').modal('show');
        });

        function parseActionUrl(action, id) {
            return action.match(/\/[0-9]+$/)
                ? action.replace(/([0-9]+$)/, id)
                : action + '/' + id;
        }
    </script>
@stop
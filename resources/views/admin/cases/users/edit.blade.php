@extends('voyager::master')

@section('head')
<script src="/js/inputmask.min.js"></script>
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-person"></i> Editing User Access from Case {{$case->case_number}}
    </h1>
@stop

@section('page_header_actions')

@stop

@section('content')
    <div class="page-content container-fluid">
    <form role="form" action="/admin/cases/{{$case->id}}/users/{{$users->first()->id}}/edit" method="POST" enctype="multipart/form-data">

            {{ method_field("patch") }}
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-12">
                            <div class="form-group col-md-4">
                                <label for='case_number'>Access From</label>
                                <input id="access_from" type="text" class="form-control" name="access_from" placeholder="mm/dd/yyyy hh:mm" value="{{ \Carbon\Carbon::parse($users->first()->pivot->access_on)->format('m/d/Y H:i:s') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for='defendant'>Access Expires</label>
                                <input id="revoke_on" type="text" class="form-control" name="revoke_on" placeholder="mm/dd/yyyy hh:mm" value="{{ \Carbon\Carbon::parse($users->first()->pivot->revoke_on)->format('m/d/Y H:i:s') }}">
                            </div>
                            <br>
                             <button type="submit" class="btn btn-primary pull-left">
                                Update Access
                            </button>
                </div>
            </div>      
      
        </form>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                        <div class="panel-body">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>Extension</th>
                                        <th>Uploaded</th>
                                        <th>Downloads Left</th>
                                        <th class="actions">Downloads</th>
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
                                                {{$file->count($users->first()->id) }}
                                            </td>
                                            <td class="no-sort no-click">
                                                <a href="/admin/cases/{{$case->id}}/users/{{$users->first()->id}}/files/{{$file->id}}/decrease" class="btn-sm btn-danger pull-right">
                                                    <i class="voyager-angle-down"></i> Decrease
                                                </a>
                                                <a href="/admin/cases/{{$case->id}}/users/{{$users->first()->id}}/files/{{$file->id}}/increase" class="btn-sm btn-success pull-right">
                                                    <i class="voyager-angle-up"></i> Increase
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
    </div>
    @if(!empty($users))
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-lock"></i> Are you sure you want to revoke access to {{$users->first()->name}}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="/admin/cases/{{$case->id}}/users/{{$users->first()->id}}" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="Yes, revoke access">
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
    <script>

 
    


  </script>
@stop
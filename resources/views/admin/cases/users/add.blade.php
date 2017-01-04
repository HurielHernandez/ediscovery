@extends('voyager::master')

@section('head')
<link rel="stylesheet" href="/css/jquery.fileupload.css">
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-person"></i>Grant User Access to Case {{$case->case_number}}
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Created</th>
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($users))        
                                @foreach($users as $user)
                                    <tr>  
                                        <td>
                                           {{$user->name}}
                                        </td>
                                        <td>
                                            {{$user->created_at->format('M d Y - H:i:s')}}
                                        </td>
                                        <td class="no-sort no-click">
                                            <a href="/admin/cases/{{$case->id}}/users/{{$user->id}}/grant" class="btn-sm btn-success pull-right">
                                                <i class="voyager-plus"></i> Grant Access
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
            @if(!empty($user))
            <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-trash"></i> Are you sure you want to Revoke Access?</h4>
                        </div>
                        <div class="modal-footer">
                            <form action="/admin/cases/{{$case->id}}/files/{{$user->id}}" id="delete_form" method="POST">
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

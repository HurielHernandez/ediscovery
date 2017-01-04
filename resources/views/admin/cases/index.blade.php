@extends('voyager::master')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i> Cases
        <a href="/admin/cases/create" class="btn btn-success">
            <i class="voyager-plus"></i> Add New
        </a>
    </h1>
@stop

@section('page_header_actions')

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
                                    <th>Case</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>JN</th>
                                    <th>SID</th>
                                    <th>Offense</th>
                                    <th>Uploaded</th>
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($cases))
                                @foreach($cases as $case)
                                    <tr>  
                                        <td>
                                           {{$case->case_number}}
                                        </td>
                                        <td>
                                            {{$case->last_name}}
                                        </td>
                                        <td>
                                            {{$case->first_name}}
                                        </td>
                                        <td>
                                            {{$case->jn}}
                                        </td>
                                        <td>
                                            {{$case->sid}}
                                        </td>
                                        <td>
                                            {{$case->offense_description}}
                                        </td>
                                        <td>
                                            {{$case->created_at->format('M d, Y - H:i:s')}}
                                        </td>
                                        <td class="no-sort no-click">
                                            <div class="btn-sm btn-danger pull-right delete" data-id="{{ $case->id }}" id="delete-{{ $case->id }}">
                                                <i class="voyager-trash"></i> Delete
                                            </div>
                                            <a href="/admin/cases/{{$case->id}}/edit" class="btn-sm btn-primary pull-right edit">
                                                <i class="voyager-edit"></i> Edit
                                            </a>
                                            <a href="/admin/cases/{{$case->id}}" class="btn-sm btn-warning pull-right">
                                                <i class="voyager-eye"></i> View
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
    @if(!empty($case))
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Are you sure you want to delete
                        this Case?</h4>
                </div>
                <div class="modal-footer">
                    <form action="/admin/cases/{{$case->id}}" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
                               value="Yes, Delete This Case">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
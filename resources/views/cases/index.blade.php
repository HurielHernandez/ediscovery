@extends('master')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-folder"></i> Cases
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
                                            <a href="/cases/{{$case->id}}" class="btn-sm btn-warning pull-right">
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
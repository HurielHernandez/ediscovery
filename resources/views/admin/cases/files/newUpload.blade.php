@extends('voyager::master')

@section('head')
<link rel="stylesheet" href="/vendor/jildertmiedema/laravel-plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css">

<meta name="csrf-token" content="{{ csrf_token() }}">

@stop

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-upload"></i>Upload Files to Case {{$case->case_number}}
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                {!! 
                    Plupload::init([
                    'url' => '/admin/cases/'.$case->id.'/files/upload',
                    'chunk_size' => '100kb',
                    ])->withPrefix('js')->createHtml();
                !!}
            </div>
        </div>    
    </div>        
@stop



@section('javascript')



<script type="text/javascript">
$(document).ready(function() {
    // Setup html5 version
    $("#js-container").pluploadQueue({
        // General settings
        runtimes : 'html5,flash,silverlight,html4',
        
        // Fake server response here 
        // url : '../upload.php',
        url: "/admin/cases/{{$case->id}}/files/upload",
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        // chunks : {
        //     size: '7mb',
        //     send_chunk_number: true // set this to true, to send chunk and total chunk numbers instead of offset and total bytes
        },
        chunk_size: '2000kb',
        max_retries: 3,
        rename : false,
        dragdrop: true, 
        multipart_params : {
            "case_id" : {{$case->id}},
        },

        flash_swf_url : 'http://rawgithub.com/moxiecode/moxie/master/bin/flash/Moxie.cdn.swf',
        silverlight_xap_url : 'http://rawgithub.com/moxiecode/moxie/master/bin/silverlight/Moxie.cdn.xap'
    });
});
</script>
<script src="/vendor/jildertmiedema/laravel-plupload/js/jquery.plupload.queue/jquery.plupload.queue.min.js"></script>

@stop

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>ERP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/simple-ine/css/simple-line-icons.css')}}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/custom.css') }}">
</head>

<body class="" ng-app="app">
    <div class="wrapper @if(Auth::check()) user-{{ Auth::user()->user_type}} @endif ">
        <!-- <div class="page-menu">
            @include('page_menu')
        </div> -->
        <div class="main">
            @include('page_header')
            <div class="content">
                @yield("content")
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var base_url = "{{ url('/') }}";
        var api_token = "{{ Auth::user()->api_token }}";
        var client_id = "{{ Auth::user()->client_id }}";
    </script>
    <script src="{{ url('assets/js/jquery-3.7.1.slim.min.js') }}"></script>
    <script src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/js/bootbox.min.js') }}"></script>

    <!----angular related -->
    <script type="text/javascript" src="{{url('assets/plugins/admin/angular.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/angular-sanitize.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/ng-file-upload.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/ng-file-upload-shim.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/jcs-auto-validate.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/jcs-auto-validate.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/core/custom.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/core/app.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/core/services.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/plugins/admin/core/staff/staff_attendance_controller.js')}}" ></script>
    <!----angular related end-->
</body>

</html>

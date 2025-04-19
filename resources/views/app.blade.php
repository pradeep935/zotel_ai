<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ config('app.name', 'Internal23Watts') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/plugins/simple-ine/css/simple-line-icons.css')}}" />
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/custom.css') }}">
    @inertiaHead
</head>

<body class="">
    <div class="wrapper @if(Auth::check()) user-{{ Auth::user()->user_type}} @endif ">
        <!-- <div class="page-menu">
            @include('page_menu')
        </div> -->
        <div class="main">
            @include('page_header')
            <div class="content">
                @inertia
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var base_url = "{{ url('/') }}";
    </script>
    <script src="{{ url('assets/js/jquery-3.7.1.slim.min.js') }}"></script>
    <script src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/js/bootbox.min.js') }}"></script>
    @vite(['resources/js/app.js'])
</body>

</html>

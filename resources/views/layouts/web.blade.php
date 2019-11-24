<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name')}}</title>

    <!-- Favicon -->
    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    {{--core css--}}
    <link type="text/css" href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('css/argon.css') }}" rel="stylesheet">
</head>
<body>

{{--header--}}
@include('shared.web_header')
{{--endheader--}}

@yield('content')

@include('shared.web_footer')

<!-- Optional plugins -->
{{--<script src="/assets/vendor/PLUGIN_FOLDER/PLUGIN_SCRIPT.js"></script>--}}

<!--Core and Theme JS -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/argon.js') }}"></script>

@stack("script")
</body>
</html>

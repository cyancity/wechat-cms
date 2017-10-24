<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="{{ config('blog.meta.keywords') }}">
    <meta name="description" content="{{ config('blog.meta.description') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ config('blog.default_icon') }}">
    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('parts.navbar')
        <div class="main">
            @yield('content')
        </div>
        @include('parts.footer')
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/home.js') }}"></script>
    @yield('scripts')
    <script>
        $(function () {
            $("[data-toogle='tooltip'].tooltip()")
        })
    </script>
</body>
</html>

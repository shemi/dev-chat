<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <script>
        window.App = @json([
            'base_url' => config('app.url'),
            'socket_url' => '//' . env('SOCKET_PATH') . ':' . env('SOCKET_PORT'),
            'api_base' => route('api.start')
        ])
    </script>

</head>
<body>
    <div id="app">
        @yield('base-content')
    </div>

    <!-- Scripts -->
    <script src="//{{ env('SOCKET_PATH') }}:6001/socket.io/socket.io.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>

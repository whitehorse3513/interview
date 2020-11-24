<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}" />
    <!-- ios support -->
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icons/icon-72x72.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icons/icon-96x96.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icons/icon-128x128.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icons/icon-144x144.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icons/icon-152x152.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icons/icon-192x192.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icons/icon-384x384.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/icons/icon-512x512.png') }}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/icons/icon-72x72.png') }}"/>
    <meta name="apple-mobile-web-app-status-bar" content="#8163df" />
    <meta name="theme-color" content="#8163df" />

    <title>Interview application</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/webcam.min.js') }}"></script>
    <script src="{{ asset('assets/js/a076d05399.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style_responsive.css') }}" />
    @yield('style')
</head>
<body>

@yield('section')

<script src="{{ asset('assets/js/app.js') }}"></script>
@yield('script')
</body>
</html>

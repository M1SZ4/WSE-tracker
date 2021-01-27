<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.head')

<body class="bg-gradient-primary">

@yield('content')

</body>

@include('layouts.scripts')

</html>

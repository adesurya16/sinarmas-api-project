<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title')</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{ asset('/bower_components/materialize/dist/css/materialize.min.css') }}"  media="screen,projection"/>

    @yield('css')
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>

  <body @yield('content-body')>
    @yield('content')
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/bower_components/materialize/dist/js/materialize.min.js') }}"></script>
    @yield('script')
  </body>
</html>

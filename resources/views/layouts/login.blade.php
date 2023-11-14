<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PAGE TITLE HERE -->
    <title>{{ config('app.name', 'Cafeteria') }}</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/') }}/vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="{{ url('assets/') }}/css/vendors/simplebar.css">
</head>

<body>

    @yield('content')

    
    <script src="{{ url('assets/') }}/vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="{{ url('assets/') }}/vendors/simplebar/js/simplebar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>
    @yield('scripts')

</body>

</html>
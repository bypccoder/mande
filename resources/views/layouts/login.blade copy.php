<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="FoodDesk - Online Food Delivery Admin Dashboard" />
    <meta property="og:title" content="FoodDesk - Online Food Delivery Admin Dashboard" />
    <meta property="og:description" content="FoodDesk - Online Food Delivery Admin Dashboard" />
    <meta property="og:image" content="https://fooddesk.dexignlab.com/xhtml/social-image.png" />
    <meta name="format-detection" content="telephone=no">

    <!-- PAGE TITLE HERE -->
    <title>{{ config('app.name', 'Cafeteria') }}</title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/') }}/favicon.ico" />
    <link href="{{ url('assets/') }}/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/vendor/swiper/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/fontawesome.min.css" integrity="sha512-SgaqKKxJDQ/tAUAAXzvxZz33rmn7leYDYfBP+YoMRSENhf3zJyx3SBASt/OfeQwBHA1nxMis7mM3EV/oYT6Fdw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="body">

    @yield('content')

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ url('assets/') }}/vendor/global/global.min.js"></script>
    <script src="{{ url('assets/') }}/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="{{ url('assets/') }}/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
    <script src="{{ url('assets/') }}/js/custom.js"></script>
    <script src="{{ url('assets/') }}/js/dlabnav-init.js"></script>


    <script src="{{ url('assets/') }}/js/demo.js"></script>

</body>

</html>

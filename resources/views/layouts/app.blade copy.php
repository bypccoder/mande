<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PAGE TITLE HERE -->
    <title>{{ config('app.name', 'Cafeteria') }}</title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/') }}/favicon.ico" />
    <link href="{{ url('assets/') }}/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/vendor/swiper/css/swiper-bundle.min.css" rel="stylesheet">

    <link href="{{ url('assets/') }}/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    @yield('styles')
    <link href="{{ url('assets/') }}/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/fontawesome.min.css" integrity="sha512-SgaqKKxJDQ/tAUAAXzvxZz33rmn7leYDYfBP+YoMRSENhf3zJyx3SBASt/OfeQwBHA1nxMis7mM3EV/oYT6Fdw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index.html" class="brand-logo">
               <img style="width:60px" src="{{ url('assets/') }}/images/logo-full.png" class="mb-3" alt="">
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->


        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="container d-block my-0">
                        <div class="d-flex align-items-center justify-content-sm-between justify-content-end">
                            <div class="header-left">
                                <div class="nav-item d-flex align-items-center">
                                    <!--<div class="d-flex header-bx">

                                    </div>-->
                                </div>
                            </div>

                            <ul class="navbar-nav header-right ">

                                <li class="nav-item d-flex align-items-center">

                                </li>
                                <li>

                                    <div class="dropdown header-profile2 ">
                                        <a class="nav-link " href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                            <div class="header-info2 d-flex align-items-center">
                                                <div class="d-flex align-items-center sidebar-info">
                                                    <div>
                                                        <h6 class="font-w500 mb-0 ms-2">{{ auth()->user()->name }}</h6>
                                                    </div>
                                                    <i class="fas fa-chevron-down"></i>
                                                </div>

                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="{{ url('/logout') }}" class="dropdown-item ai-icon ">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>Cerrar Sesion
                                             </a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav border-right">
            <div class="dlabnav-scroll">
                <p class="menu-title style-1"> Menu Principal</p>
                <ul class="metismenu" id="menu">
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <span class="nav-text">Almacen</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('products.index') }}">Productos</a></li>
                            <li><a href="{{ route('inventories.index') }}">Ingresos</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                            <span class="nav-text">Categorias</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ route('categories.index') }}">Grupos</a></li>
                            <li><a href="{{ route('sub-categories.index') }}">Categorias</a></li>
                        </ul>
                    </li>

                </ul>

            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container">
                @yield('content')
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ url('assets/') }}/vendor/global/global.min.js"></script>
    <script src="{{ url('assets/') }}/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="{{ url('assets/') }}/vendor/jquery-nice-select/js/jquery.nice-select.min.js"></script>
    <script src="{{ url('assets/') }}/js/custom.js"></script>
    <script src="{{ url('assets/') }}/js/dlabnav-init.js"></script>
    <script src="{{ url('assets/') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="{{ url('assets/') }}/js/demo.js"></script>

    @yield('scripts')

</body>

</html>

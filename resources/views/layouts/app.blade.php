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
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    @yield('styles')
    <link href="{{ url('assets/') }}/css/style.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/css/appAdmin.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/') }}/vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="{{ url('assets/') }}/css/vendors/simplebar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css"
        crossorigin="anonymous">
    <link href="{{ url('assets/') }}/vendors/bootstrap-fileinput-master/css/fileinput.css" media="all"
        rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/') }}/vendors/bootstrap-fileinput-master/themes/explorer-fa5/theme.css" media="all"
        rel="stylesheet" type="text/css" />
</head>

<body>

    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
        <div class="sidebar-brand d-none d-md-flex">
            <img class="sidebar-brand-full" width="118" src="{{ url('assets/') }}/images/logo.png"
                alt="La Cafeteria" />
        </div>
        <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.index') }}"><i class="las la-tachometer-alt"
                        style="margin:5px 10px 0 0"></i> Inicio</a>
            </li>

            @if (auth()->user()->rol_id == 1)
                <!-- START ADMIN -->
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-store"></i> &nbsp Configuración</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('import_employees.index') }}"><i
                                    class="las la-arrow-right"></i> Colaboradores</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-store"></i> &nbsp Ventas</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}"><i
                                    class="las la-arrow-right"></i> Pedidos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('transaction.index') }}"><i
                                    class="las la-arrow-right"></i> Transacciones</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-utensils"></i></i> &nbsp Menus</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('menus.index') }}"><i
                                    class="las la-arrow-right"></i> Listado</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-utensils"></i></i> &nbsp Combos</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('combos.index') }}"><i
                                    class="las la-arrow-right"></i> Listado</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-boxes"></i> &nbsp Almacen</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i
                                    class="las la-arrow-right"></i> Productos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('inventories.index') }}"><i
                                    class="las la-arrow-right"></i> Ingresos</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-tags"></i> &nbsp Categorias</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}"><i
                                    class="las la-arrow-right"></i> Categorias</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('subcategories.index') }}"><i
                                    class="las la-arrow-right"></i> Subcategorias</a></li>
                    </ul>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" href="{{ route('close_sales.index') }}"><i class="las la-tachometer-alt"
                            style="margin:5px 10px 0 0"></i> Cierre Atencion</a>
                </li>-->
                <!-- END ADMIN -->
            @elseif (auth()->user()->rol_id == 2)
                <!-- START VENDEDOR -->
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-store"></i> &nbsp Ventas</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}"><i
                                    class="las la-arrow-right"></i> Pedidos</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-utensils"></i></i> &nbsp Menus</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('menus.index') }}"><i
                                    class="las la-arrow-right"></i> Listado</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('close_sales.index') }}"><i class="las la-tachometer-alt"
                            style="margin:5px 10px 0 0"></i> Cierre Atencion</a>
                </li>
                <!-- END VENDEDOR -->
            @elseif (auth()->user()->rol_id == 3)
                <!-- START COBRANZAS -->
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-store"></i> &nbsp Configuración</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('import_employees.index') }}"><i
                                    class="las la-arrow-right"></i> Colaboradores</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-utensils"></i></i> &nbsp Menus</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('menus.index') }}"><i
                                    class="las la-arrow-right"></i> Listado</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-utensils"></i></i> &nbsp Cobranzas</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('descargar_xml_cdr.index') }}"><i
                                    class="las la-arrow-right"></i> XML/CDR</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders_income.index') }}"><i
                                    class="las la-arrow-right"></i> Documentos: Cobranzas</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('generate_factura.index') }}"><i
                                    class="las la-arrow-right"></i> Generar Facturas</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" href="{{ route('generate_notacredito.index') }}"><i
                                    class="las la-arrow-right"></i> Generar Nota Crédito</a></li> --}}
                        <li class="nav-item"><a class="nav-link" href="{{ route('cobranza.index') }}"><i
                                    class="las la-arrow-right"></i> Generar Reportes</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-store"></i> &nbsp Ventas</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}"><i
                                    class="las la-arrow-right"></i> Pedidos</a></li>
                    </ul>
                </li>
                <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
                        <i class="las la-boxes"></i> &nbsp Almacen</a>
                    <ul class="nav-group-items">
                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i
                                    class="las la-arrow-right"></i> Productos</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('inventories.index') }}"><i
                                    class="las la-arrow-right"></i> Ingresos</a></li>
                    </ul>
                </li>
                <!-- END COBRANZAS -->
            @endif

        </ul>
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
        <header class="header header-sticky mb-4">
            <div class="container-fluid">
                <button class="header-toggler px-md-0 me-md-3" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                    <i class="las la-bars fs-3"></i>
                </button>

                <ul class="header-nav ms-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="las la-sign-out-alt"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>

        </header>
        <div class="body flex-grow-1 px-3">
            @yield('content')
        </div>
    </div>
    <script src="{{ url('assets/') }}/vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="{{ url('assets/') }}/vendors/simplebar/js/simplebar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    @yield('scripts')

</body>

</html>

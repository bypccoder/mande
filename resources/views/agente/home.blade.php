<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PAGE TITLE HERE -->
    <title>{{ config('app.name', 'Cafeteria') }}</title>


    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/') }}/favicon.ico" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="{{ url('assets/') }}/css/style.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/css/app.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/css/client.css" rel="stylesheet">

</head>

<body>


    <div class="container position-relative mt-3">
        <div class="row">
            <div class="col-lg-6">
                <input id="search-input" type="text" class="form-control" placeholder="Busca tu producto aqui..">
            </div>
            <div class="col-lg-6 text-end">
                <a id="btn-cancel-order" href="{{ route('kiosk.cancel') }}"
                    class="btn btn-lg btn-light tra-salmon-hover">
                    <i class="las la-trash-alt pe-none"></i>
                    Cancelar Pedido
                </a>
                <button type="button" class="btn btn-lg btn-salmon tra-salmon-hover btn-cart resumen-mostrar">
                    <i class="las la-shopping-cart pe-none"></i>
                    Procesar Pedido
                    <span id="cart-counter"
                        class="badge badge-circle badge-danger badge-sm text-white float-end position-absolute pe-none">0</span>
                </button>
            </div>
        </div>
    </div>
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper" class="d-block">

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 my-2">
                        <div class="text-center">
                            <h1>Hola <span class="text-danger text-uppercase">{{ session('person')->name }}</span>,</h1>
                            <h1>Selecciona la categoria de productos que deseas</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="bg-categories">
                <div class="container">
                    <div class="row">
                        <div id="categoria-container" class="col-xl-12 section bg-primary-1">

                        </div>
                    </div>
                </div>
            </div>
            <!-- product list -->
            <div class="py-5">
                <div id="productos-wrapper" class="d-none">
                </div>
            </div>
            <!-- /product list -->
        </div>

    </div>



    <div id="resumen-wrapper" class="d-none">

        <div class="container">
            <div class="card shadow border-0">
                <div class="card-body bg-white">
                    <div class="row">
                        <div class="col-lg-4 order-lg-2 mb-4 p-3">

                            <form id="frm-order-resume" class="needs-validation" novalidate>
                                <h4 class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">Resumen de tu Pedido</span>
                                    <span class="badge badge-primary badge-pill">3</span>
                                </h4>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <h6 class="my-0">Subtotal</h6>
                                        <div id="sub-total" class="fw-bolder">S/. <span>0</span></div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <h6 class="my-0">IGV (18%)</h6>
                                        <div id="igv" class="fw-bolder">S/. <span>0</span></div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between lh-condensed bg-light">
                                        <h6 class="my-0">Total</h6>
                                        <div id="total" class="fw-bolder">S/. <span>0</span></div>
                                    </li>
                                </ul>

                                <h4 class="mb-3">Metodo de Pago</h4>

                                <div class="d-block my-3">
                                    @foreach ($paymentMethods as $paymentMethod)
                                        <div class="form-check custom-radio mb-2 container-payment">
                                            <input name="paymentMethod" id="{{ strtolower($paymentMethod->name) }}" required value="{{ $paymentMethod->id }}"
                                                type="radio" class="form-check-input">
                                            <label class="form-check-label"
                                                for="credit">{{ $paymentMethod->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <h4 class="mb-3">Tipo de Comprobante</h4>

                                <div class="d-block my-3">
                                    @foreach ($voucherTypes as $voucherType)
                                        <div class="form-check custom-radio mb-2 container-voucher">
                                            <input name="voucherType" id="{{ str_replace(' ','_',strtolower($voucherType->name)) }}" required value="{{ $voucherType->id }}"
                                                type="radio" class="form-check-input">
                                            <label class="form-check-label"
                                                for="credit">{{ $voucherType->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="my-3" id="divInvoiceType" style="display: none">
                                    <h4 class="mb-3">Tipo de Factura</h4>
                                    <select name="invoiceType" id="invoiceType" class="form-select">
                                        <option value="">Seleccione..</option>
                                        <option value="1">Crédito</option>
                                        <option value="2">Contado</option>
                                    </select>

                                    <div id="divAdditionalFields" style="display: none">
                                        <h4 class="mb-3 mt-3">Detalle Factura Crédito</h4>
                                        <label for="startDate" class="mb-1">Fecha de Inicio:</label>
                                        <input class="form-control" type="date" name="startDate" id="startDate">
                                        <label for="endDate" class="mb-1">Fecha de Fin:</label>
                                        <input class="form-control" type="date" name="endDate" id="endDate">
                                    </div>
                                </div>


                                <button type="submit"
                                    class="btn btn-success w-100 fs-3 btn-order-submit text-white mt-4"><i
                                        class="las la-cash-register"></i> Procesar Compra</button>
                            </form>
                        </div>
                        <div class="col-lg-8 order-lg-1 p-3">
                            <h4 class="mb-3">Carrito de Compras</h4>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-list-product">
                                </tbody>
                            </table>

                            <div class="mb-4"></div>

                            <a class="btn btn-lg btn-salmon tra-salmon-hover" onClick="changeWapperShow('main')"
                                id="btnEmpty"><i class="las la-store-alt"></i> Seguir Comprando</a>
                            <a class="btn btn-light btn-lg" onClick="emptyCart()" id="btnEmpty"><i
                                    class="las la-trash-alt"></i> Vaciar Carrito</a>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>



    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ url('assets/') }}/vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>

    <script src="{{ url('js/util.js') }}"></script>
    <script>
        var BASE_PATH = "{{ url('/') }}"
    </script>
    <script src="{{ url('js/cart.js') }}"></script>
    <script src="{{ url('js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('input[name="voucherType"]').change(function() {
                var selectedVoucherType = parseInt($('input[name="voucherType"]:checked').val());

                if (selectedVoucherType === 2) {
                    $('#divInvoiceType').show();
                } else {
                    $('#divInvoiceType').hide();
                }
            });

            $('#invoiceType').change(function() {
                var selectedInvoiceType = parseInt($(this).val())

                if (selectedInvoiceType === 1) {
                    $('#divAdditionalFields').show();
                } else {
                    $('#divAdditionalFields').hide();
                }
            });
        });
    </script>


</body>

</html>

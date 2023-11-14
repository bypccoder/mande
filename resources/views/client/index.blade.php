<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="{{ url('assets/') }}/css/style.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/css/app.css" rel="stylesheet">


</head>

<body>


    <div class="container">
        <div class="row">
            <div class="col-lg-12 bg-categories history mt-5">
                <div class="tab-content">
                    <div class="tab-pane active position-relative" id="auth">
                        <div class="auth-form">

                            <h5 class="">para visualizar el historial de pedidos</h5>
                            <h2 class="h2-xl text-secondary">Ingresa tu Documento</h2>
                            <div class="input-group mt-5">
                                <input id="document" autocomplete="off" autofocus class="form-control document"
                                    type="text" placeholder="...">
                            </div>
                            <a id="validate-document" class="btn btn-lg btn-salmon tra-salmon-hover mt-4">CONTINUAR
                                <i class="las la-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="tab-pane position-relative" id="register">
                        <table id="dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>COLABORADOR</th>
                                    <th>TIPO PEDIDO</th>
                                    <th>CODIGO INTERNO</th>
                                    <th>MONTO</th>
                                    <th>FECHA PEDIDO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <div class="d-flex justify-content-between">
                            <a href="#auth" class="btn btn-salmon tra-salmon-hover btn-tabs" onclick="changeTab(this)"
                                data-coreui-toggle="tab" data-coreui-target="#auth"><i class="las la-arrow-left"></i>
                                Volver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modal-details" tabindex="-1" aria-labelledby="modal-details-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-details-label">Detalle Pedido</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="order-info" class="col-md-6">
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col-md-12">
                            <table id="order-detail" class="table">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase fw-bold" style="font-size:10px">#</th>
                                        <th class="text-uppercase fw-bold" style="font-size:10px">Producto</th>
                                        <th class="text-uppercase fw-bold" style="font-size:10px">Cantidad</th>
                                        <th class="text-uppercase fw-bold" style="font-size:10px">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="order-amount" class="d-flex justify-content-center bg-dark text-white p-4">
                    </div>


                </div>
            </div>
        </div>
    </div>



    <!-- #/ container -->
    <!-- Common JS -->
    <script src="{{ url('assets/') }}/vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="{{ url('assets/') }}/vendors/simplebar/js/simplebar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ url('js/util.js') }}"></script>

    <script>
        const tabAuth = document.querySelector('#auth')
        const tabRegister = document.querySelector('#register')

        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: "{{ route('client.historyOrders') }}",
            order: [
                [0, 'desc']
            ],
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'person',
                    name: 'person'
                },
                {
                    data: 'order_type',
                    name: 'order_type'
                },
                {
                    data: 'internal_code',
                    name: 'internal_code'
                },
                {
                    data: 'monto',
                    name: 'monto'
                },
                {
                    data: 'date_order',
                    name: 'date_order'
                }, {
                    data: 'details',
                    name: 'details'
                }
            ],

            language: {
                paginate: {
                    previous: '<<',
                    next: '>>'
                }
            }
        });

        const FORM = {
            validateDocument: (documento) => {
                CRUD.sendData('/client/history', {
                    documento: documento
                }).then((response) => {
                    console.log(response)
                    /*
                    if (response.code == 200) {
                        tabAuth.classList.toggle('active')
                        tabRegister.classList.toggle('active')
                    } else if (response.code == 500) {
                        tabAuth.classList.toggle('active')
                        tabRegister.classList.toggle('active')
                    }*/
                })
            }
        }

        document.getElementById('validate-document').addEventListener('click', function(e) {
            e.preventDefault()
            var documento = document.getElementById('document').value;
            FORM.validateDocument(documento)
        });

        document.querySelector('#dataTable').addEventListener('click', (e) => {
            e.preventDefault()

            if (e.target.classList.contains('viewDetail')) {
                let data = e.target.getAttribute('data-order');
                viewDetails(data)
            }
        })

        function viewDetails(data) {
            let sendData = {
                'order': data
            }
            CRUD.sendData('/admin/orders/detail', sendData).then((response) => {
                if (response.code !== 200) {
                    mostrarMensajeAlertation(response.code, response.message)
                }
                const orderData = response.data.order
                const orderDetaislData = response.data.details
                const orderAmount = response.data.orderAmount

                const infoTemplate =
                    '<p class="pb-1 mb-1 border-bottom border-light text-uppercase">:key: : <span class="fw-bolder">:value:</span></p>'
                const detailTemplate =
                    '<tr><td>:nro:</td><td>:product:</td><td>:quantity:</td><td>S/. :total:</td></tr>'
                const amountemplate =
                    '<div class="py-3 px-5 text-right"><div class="mb-2 text-capitalize">:key:</div><div class="h2 font-weight-light">S/. :value:</div></div>'

                var infoHtml = '',
                    detailHtml = '',
                    amountHtml = ''

                // Fill to info in ORDER TABLE
                for (var key in orderData) {
                    if (orderData.hasOwnProperty(key)) {
                        infoHtml += infoTemplate.replace(':key:', key).replace(':value:', orderData[key])
                    }
                }

                // Fill to info in DETAIL ORDER TABLE
                for (var i = 0; i < orderDetaislData.length; i++) {
                    var item = orderDetaislData[i];
                    detailHtml += detailTemplate.replace(':product:', item.product).replace(':quantity:', item
                            .quantity).replace(':total:', item.total.toFixed(2))
                        .replace(':nro:', (i + 1))
                }

                // Fill to info in AMOUNTS DETAILS
                for (var key in orderAmount) {
                    if (orderAmount.hasOwnProperty(key)) {
                        amountHtml += amountemplate.replace(':key:', key).replace(':value:', orderAmount[key])
                    }
                }

                console.log(amountHtml);

                document.querySelector('#order-info').innerHTML = infoHtml
                document.querySelector('#order-detail tbody').innerHTML = detailHtml
                document.querySelector('#order-amount').innerHTML = amountHtml

            })
        }

        function changeTab(element) {
            let id = element.getAttribute('href').slice(1)
            tabAuth.classList.toggle('active')
            tabRegister.classList.toggle('active')
        }
    </script>
</body>

</html>

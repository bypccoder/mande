@extends('layouts.app')

@section('template_title')
    Documentos: Cobranzas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="mb-5">
                    <h3 class="mb-0"><span id="card_title">{{ __('Pedidos') }}</span></h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-md-flex">
                        <div class="flex-grow-1">

                        </div>
                        <div class="mt-3 mt-md-0">
                            <a href="#!" class="btn btn btn-outline-secondary text-black ms-2"
                                data-coreui-toggle="offcanvas" data-coreui-target="#modal-filters">Exportar</a>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>FECHA PEDIDO</th>
                                        <th>CLIENTE</th>
                                        <th>MONTO</th>
                                        <th>TIPO COMPROBANTE</th>
                                        <th>METODO PAGO</th>
                                        <th>ESTADO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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

    <!-- Modal -->
    <div class="modal fade" id="modal-nota-credito" tabindex="-1" aria-labelledby="modal-nota-credito-label"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-details-label">Nota de Credito</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="frm-nota-credito" method="POST" action="/">
                        <input type="text" id="orderId" name="orderId" class="d-none">
                        {{-- <div class="mb-3">
                            <label for="" class="form-label">Documento</label>
                            <select class="form-control" name="docBoletaOFactura" id="docBoletaOFactura">
                            </select>
                        </div> --}}
                        <div class="mb-3">
                            <label for="" class="form-label">Motivo Nota Crédito</label>
                            <select class="form-control" name="cboMotivoNotaCredito" id="cboMotivoNotaCredito">
                                <option value="">Seleccione..</option>
                                <option value="01-Anulación de la operación">Anulación de la operación</option>
                                <option value="02-Anulación por error en el RUC">Anulación por error en el RUC</option>
                                <option value="03-Corrección por error en la descripción">Corrección por error en la
                                    descripción</option>
                                <option value="04-Corrección por error en la descripción">Descuento global</option>
                                <option value="05-Descuento por ítem">Descuento por ítem</option>
                                <option value="06-Devolución total">Devolución total</option>
                                <option value="07-Devolución por ítem">Devolución por ítem</option>
                                <option value="08-Bonificación">Bonificación</option>
                                <option value="09-Disminución en el valor">Disminución en el valor</option>
                                <option value="10-Otros conceptos">Otros conceptos</option>
                                <option value="11-Ajustes de operaciones de exportación">Ajustes de operaciones de
                                    exportación</option>
                                <option value="12-Ajustes afectos al IVAP">Ajustes afectos al IVAP</option>
                                <option
                                    value="13-Corrección del monto neto pendiente de pago y/o la(s) fechas(s) de vencimiento del pago
            único o de las cuotas y/o los montos correspondientes a cada cuota, de ser el caso">
                                    Corrección del monto neto pendiente de pago y/o la(s) fechas(s) de vencimiento del pago
                                    único o de las cuotas y/o los montos correspondientes a cada cuota, de ser el caso
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-lg w-100 text-white btn-success">Generar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="modal-filters" aria-labelledby="modal-filtersLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="modal-filtersLabel">Filtros</h5>
            <button type="button" class="btn-close" data-coreui-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body small">
            <div class="row">
                <div class="col">
                    <form id="frm_filters" action="/">
                        <h6>Seleccionar el rango de fechas a exportar</h6>
                        <div class="form-group">
                            <label class="mb-1 fw-bold" style="font-size:10px" for="">FECHA INICIO</label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="date_start" />
                                <span class="input-group-text"><i class="las la-calendar"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="mb-1 fw-bold" style="font-size:10px" for="">FECHA FIN</label>
                            <div class="input-group mb-3">
                                <input class="form-control" type="text" name="date_end" />
                                <span class="input-group-text"><i class="las la-calendar"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success w-100 text-white"><i
                                    class="las la-file-download"></i> Generar Reporte</button>
                        </div>
                    </form>
                    <div id="download-report"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('scripts')
    <script src="{{ url('') }}/js/util.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            $('input[name="date_start"]').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('input[name="date_end"]').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $('#docBoletaOFactura').select2({
                placeholder: 'Buscar..',
                theme: 'bootstrap-5',
                minimumInputLength: 3,
                dropdownParent: $("#modal-nota-credito"),
                ajax: {
                    url: "orders/chargecode",
                    type: "POST",
                    dataType: 'JSON',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(response) {
                        let arrResult = []
                        response.forEach(item => {
                            console.log(item)
                            arrResult.push({
                                id: item.charge_code,
                                text: item.charge_code
                            })
                        })

                        console.log(arrResult)
                        return {
                            results: arrResult
                        };
                    },
                    cache: true
                }
            });
        })

        document.getElementById('frm_filters').addEventListener('submit', (e) => {
            e.preventDefault()
            submitFilters(e)
        })

        document.getElementById('frm-nota-credito').addEventListener('submit', (e) => {
            e.preventDefault()
            Swal.fire({
                title: 'Generar Nota Credito',
                text: 'Confirme si desea generar la nota de credito, el cambio es irreversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, deseo generar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitNotaCredito(e)

                }
            })
        })

        // Obtener referencia al formulario y a la tabla

        document.querySelector('#dataTable').addEventListener('click', (e) => {
            e.preventDefault()

            if (e.target.classList.contains('viewDetail')) {
                let data = e.target.getAttribute('data-order');
                viewDetails(data)
            } else if (e.target.classList.contains('cancelOrderSunat')) {
                cancelOrderSunat(e)
            } else if (e.target.classList.contains('viewVoucher')) {
                viewVoucher(e)
            } else if (e.target.classList.contains('modalNotaCredito')) {
                document.querySelector('#orderId').value = e.target.getAttribute('data-order');
                const modalNotaCredito = new coreui.Modal('#modal-nota-credito', {
                    keyboard: true
                })
                modalNotaCredito.show()
            } else if (e.target.classList.contains('destroy')) {
                let data = e.target.getAttribute('data-order');

                Swal.fire({
                    title: 'Cancelar Venta',
                    text: 'Confirme si desea cancelar la venta seleccionada',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, deseo anular!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        destroy(data)
                    }
                })

            }
        })

        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('orders_income.index') }}",
            order: [
                [0, 'desc']
            ],
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'date_order',
                    name: 'date_order'
                },
                {
                    data: 'person',
                    name: 'person'
                },
                {
                    data: 'monto',
                    name: 'monto'
                },
                {
                    data: 'voucher_type',
                    name: 'voucher_type'
                },
                {
                    data: 'payment_method',
                    name: 'payment_method'
                },
                {
                    data: 'estado',
                    name: 'estado'
                },
                {
                    data: 'details',
                    name: 'details',
                    orderable: false,
                    searchable: false
                }
            ],

            language: {
                paginate: {
                    previous: '<<',
                    next: '>>'
                }
            }
        });

        function submitFilters(e) {
            const formData = new FormData(e.target)

            const urlParams = new URLSearchParams(formData.entries());
            const queryString = urlParams.toString();

            window.location.href = 'orders/report/?' + queryString
        }

        function submitNotaCredito(e) {
            const formData = new FormData(e.target)

            CRUD.sendDataNoJson('cobranzas/nota-credito', formData).then(response => {
                const modalNotaCredito = new coreui.Modal('#modal-nota-credito');
                modalNotaCredito.hide();
                mostrarMensajeAlerta(response.code, response.message)
                table.ajax.reload()
                console.log(response)
            })
        }

        function cancelOrderSunat(e) {
            let element = e.target
            let orderId = element.getAttribute('data-order')

            CRUD.sendData('sunat/anular', {
                order: orderId
            }).then(response => {
                mostrarMensajeAlerta(response.code, response.message)
                console.log(response)
            })
        }

        function viewVoucher(e) {
            let element = e.target
            let dataPath = element.getAttribute('data-pdf')
            if (dataPath == '') {
                mostrarMensajeAlerta('404', 'Archivo no disponible');
                return false;
            }
            let resultPath = dataPath.replace("storage/files/", "");
            window.location.href = 'sunat/comprobante/' + resultPath
        }

        function viewDetails(data) {
            let sendData = {
                'order': data
            }
            CRUD.sendData('orders/detail', sendData).then((response) => {
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
                    detailHtml += detailTemplate.replace(':product:', item.description_income).replace(':quantity:', item
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

        function destroy(data) {
            CRUD.delete('admin/orders/destroy', data).then((response) => {
                mostrarMensajeAlerta(response.code, response.message)
                if (response.code == 200) {
                    table.ajax.reload()
                }
            })
        }
    </script>
@endsection

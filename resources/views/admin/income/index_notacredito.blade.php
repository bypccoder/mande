@extends('layouts.app')

@section('template_title')
    Generar Factura
@endsection

@section('content')
    <style>
        /* Estilos personalizados */
        .ui-dialog .ui-dialog-titlebar {
            background: #8e1f4f !important;
            border: #8e1f4f !important;
        }

        .ui-dialog .ui-dialog-buttonpane button {
            background: #8e1f4f !important;
            border: #8e1f4f !important;
            color: #fff !important;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #8e1f4f;
        }

        table {
            width: 100%;
            background-color: #ffffff;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .subtotal,
        .total {
            font-weight: bold;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
        }

        .invoice-container h1,
        .invoice-container label {
            color: #ffffff;
        }

        #invoice-table_info {
            color: #ffffff;
        }

        table.dataTable thead th div.DataTables_sort_wrapper {
            color: #000;
        }
    </style>
    <section class="generate-invoice">
        <div class="container">
            <div class="invoice-container">
                <form id="invoice-form" name="invoice-form" method="POST">
                    @csrf
                    <div class="invoice-header my-3">
                        <div class="col-12">
                            <h1 class="text-center lead fs-2 fw-bold">Nota de Crédito</h1>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="documento_old">Boleta/Factura (Serie-Correlativo) actual:</label>
                                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="documento_old">Boleta/Factura (Serie-Correlativo) actual:</label>
                                        <select class="form-control" id="documento_old" name="documento_old"
                                            data-placeholder="Buscar..." required></select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="documento_new">Boleta/Factura (Serie-Correlativo) a reemplazar:</label>
                                        <select class="form-control" id="documento_new" name="documento_new"
                                            data-placeholder="Buscar..." required></select>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <label for="motivo">Motivo Nota Crédito:</label>
                                        <select class="form-control" id="motivo" name="motivo" required>
                                            <option value="">Seleccione..</option>
                                            <option value="01-Anulación de la operación">Anulación de la operación</option>
                                            <option value="02-Anulación por error en el RUC">Anulación por error en el RUC
                                            </option>
                                            <option value="03-Corrección por error en la descripción">Corrección por error
                                                en la descripción</option>
                                            <option value="04-Corrección por error en la descripción">Descuento global
                                            </option>
                                            <option value="05-Descuento por ítem">Descuento por ítem</option>
                                            <option value="06-Devolución total">Devolución total</option>
                                            <option value="07-Devolución por ítem">Devolución por ítem</option>
                                            <option value="08-Bonificación">Bonificación</option>
                                            <option value="09-Disminución en el valor">Disminución en el valor</option>
                                            <option value="10-Otros conceptos">Otros conceptos</option>
                                            <option value="11-Ajustes de operaciones de exportación">Ajustes de operaciones
                                                de exportación</option>
                                            <option value="12-Ajustes afectos al IVAP">Ajustes afectos al IVAP</option>
                                            <option
                                                value="13-Corrección del monto neto pendiente de pago y/o la(s) fechas(s) de vencimiento del pago único o de las cuotas y/o los montos correspondientes a cada cuota, de ser el caso">
                                                Corrección del monto neto pendiente de pago y/o la(s) fechas(s) de
                                                vencimiento del pago único o de las cuotas y/o los montos correspondientes a
                                                cada cuota, de ser el caso
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="btnGenerar" id="btnGenerar" class="btn btn-lg btn-primary">Generar
                            Nota de Crédito</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <link href="https://cdn.datatables.net/buttons/1.5.4/css/buttons.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.datatables.net/buttons/1.5.4/js/dataTables.buttons.js"></script>
    <link href="https://cdn.datatables.net/select/1.2.6/css/select.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.datatables.net/select/1.2.6/js/dataTables.select.js"></script>
    <link href="https://sandbox.scoretility.com/static/js/Editor-1.8.1/css/editor.dataTables.css" rel="stylesheet"
        type="text/css" />
    <script src="https://sandbox.scoretility.com/static/js/Editor-1.8.1/js/dataTables.editor.js"></script>
    <link rel=stylesheet type=text/css href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
    <link rel=stylesheet type=text/css href="https://cdn.datatables.net/1.10.18/css/dataTables.jqueryui.css?v=1.10.18">
    <link rel=stylesheet type=text/css href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.jqueryui.css?v=1.5.2">
    <link rel=stylesheet type=text/css
        href="https://cdn.datatables.net/fixedcolumns/3.2.5/css/fixedColumns.jqueryui.css?v=3.2.5">
    <link rel=stylesheet type=text/css
        href="https://sandbox.scoretility.com/static/js/Editor-1.8.1/css/editor.jqueryui.css?v=1539093792">
    <link rel=stylesheet type=text/css href="https://cdn.datatables.net/select/1.2.6/css/select.jqueryui.css?v=1.2.6">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/dataTables.jqueryui.js?v=1.10.18"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.jqueryui.js?v=1.5.2"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.js?v=3.2.5"></script>
    <script src="https://sandbox.scoretility.com/static/js/Editor-1.8.1/js/editor.jqueryui.js"></script>
    <script src="{{ url('') }}/js/util.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#documento_old').select2({
            placeholder: 'Buscar...',
            theme: 'bootstrap-5',
            minimumInputLength: 3,
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
                    let arrResult = response.map(item => ({
                        id: item.charge_code,
                        text: item.charge_code
                    }));

                    console.log(arrResult);
                    return {
                        results: arrResult
                    };
                },
                cache: true
            }
        })
        $('#documento_new').select2({
            placeholder: 'Buscar...',
            theme: 'bootstrap-5',
            minimumInputLength: 3,
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
                    let arrResult = response.map(item => ({
                        id: item.charge_code,
                        text: item.charge_code
                    }));

                    console.log(arrResult);
                    return {
                        results: arrResult
                    };
                },
                cache: true
            }
        });

        $('#motivo').select2({
            placeholder: 'Buscar...',
            theme: 'bootstrap-5'
        });

        document.getElementById('invoice-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            Swal.fire({
                title: 'Generar Nota de Crédito',
                text: 'Confirme si desea generar la nota de crédito, el cambio es irreversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, deseo generar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(e.target);
                    let formIsValid = true;

                    const requiredFields = ['documento_old','documento_new', 'motivo', 'fecha'];

                    requiredFields.forEach(fieldName => {
                        const field = document.getElementById(fieldName);
                        if (formData.get(fieldName) === '') {
                            formIsValid = false;
                            field.classList.add('is-invalid');
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

                    if (formIsValid) {
                        try {
                            const response = await CRUD.sendDataNoJson('generate-notacredito',
                                formData);

                            if (response.code === 200) {
                                requiredFields.forEach(fieldName => {
                                    document.getElementById(fieldName).value = '';
                                });
                                mostrarMensajeAlerta(response.code, response.message);
                            } else {
                                mostrarMensajeAlerta(response.code, response.message);
                            }
                        } catch (error) {
                            console.error('Error al generar la nota de crédito:', error);
                            mostrarMensajeAlerta(500, 'Error interno al generar la factura.');
                        }
                    }
                }
            });
        });
    </script>
@endsection

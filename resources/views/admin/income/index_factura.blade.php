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
                <div class="invoice-header my-3">
                    <div class="col-12">
                        <h1 class="text-center lead fs-2 fw-bold">Factura Electrónica</h1>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fecha">Fecha:</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="tipo">Tipo de Factura:</label>
                                    <select class="form-control" id="tipo" name="tipo" required>
                                        <option value="1">Contado</option>
                                        <option value="2">Crédito</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="nombre">Nombre del Cliente:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        placeholder="Ingresa el nombre del cliente" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="dni">RUC:</label>
                                    <input type="text" class="form-control" id="ruc" name="ruc"
                                        placeholder="Ingresa el RUC" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="fechaCreditoContainer" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fechaInicio">Fecha de Inicio:</label>
                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                                </div>
                                <div class="col-md-6">
                                    <label for="fechaFin">Fecha de Fin:</label>
                                    <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <form id="invoice-form" name="invoice-form" method="POST">
                    @csrf
                    <table id="invoice-table" class="display my-3">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>P.U.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total:</td>
                                <td>S/ 0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="text-center">
                        <button type="submit" name="btnGenerar" id="btnGenerar" class="btn btn-lg btn-primary">Generar
                            Factura</button>
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

    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.18/js/dataTables.jqueryui.js?v=1.10.18"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.jqueryui.js?v=1.5.2"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.js?v=3.2.5"></script>
    <script src="https://sandbox.scoretility.com/static/js/Editor-1.8.1/js/editor.jqueryui.js"></script>
    <script src="{{ url('') }}/js/util.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script>
        let data = [];
        let table;

        var editor = new $.fn.dataTable.Editor({
            table: '#invoice-table',
            idSrc: 'id',
            fields: [{
                    label: 'Producto',
                    name: 'txtProducto'
                },
                {
                    label: 'Cantidad',
                    name: 'txtCantidad',
                    def: 1, // Valor predeterminado para cantidad
                    min: 1 // Valor mínimo permitido es 1
                },
                {
                    label: 'Precio Unitario',
                    name: 'txtPrecioUnitario',
                    def: 0, // Valor predeterminado para precio unitario
                    min: 1 // Valor mínimo permitido es 0.01
                },
            ],
        });

        editor.field('txtCantidad').input().on('blur', function() {
            if (this.value !== '') {
                if (!/^\d+(\.\d{1,2})?$/.test(this.value)) {
                    this.value = ''; // Limpiar el campo si no es un número válido
                }
            }
        });

        editor.field('txtPrecioUnitario').input().on('blur', function() {
            if (this.value !== '') {
                if (!/^\d+(\.\d{2})?$/.test(this.value)) {
                    this.value = ''; // Limpiar el campo si no es un número válido con dos decimales
                }
            }
        });


        editor.on('preSubmit', function(e, data, action) {
            var txtProducto = data.data[0].txtProducto;
            var txtCantidad = data.data[0].txtCantidad;
            var txtPrecioUnitario = data.data[0].txtPrecioUnitario;

            if (!txtProducto || !txtCantidad || !txtPrecioUnitario) {
                mostrarMensajeAlerta(400, 'Por favor, complete todos los campos antes de guardar.')
                return false; // Detener la acción de guardar si faltan campos
            }
        });

        // Función para calcular el subtotal
        function calcularSubtotal(cantidad, precioUnitario) {
            return cantidad * precioUnitario;
        }

        table = $('#invoice-table').DataTable({
            dom: 'lBfrtip',
            data: data,
            columns: [{
                    data: null,
                    defaultContent: '',
                    className: 'select-checkbox',
                    orderable: false
                },
                {
                    data: 'txtProducto',
                    className: 'dt-body-center'
                },
                {
                    data: 'txtCantidad',
                    className: 'dt-body-center'
                },
                {
                    data: 'txtPrecioUnitario',
                    className: 'dt-body-center'
                },
                {
                    data: null,
                    className: 'dt-body-center', // Crear columna para el subtotal
                    render: function(data, type, row) {
                        // Calcular el subtotal
                        var cantidad = parseFloat(row.txtCantidad) || 1;
                        var precioUnitario = parseFloat(row.txtPrecioUnitario) || 0;
                        var subtotal = calcularSubtotal(cantidad, precioUnitario);

                        // Actualizar el campo 'txtSubtotal' en el objeto de datos
                        row.txtSubtotal = subtotal.toFixed(2);

                        // Formatear y mostrar el subtotal
                        return 'S/ ' + subtotal.toFixed(2);
                    }
                }
            ],
            select: true,
            buttons: [{
                    extend: 'create',
                    editor: editor,
                    text: 'Nuevo'
                },
                {
                    extend: 'edit',
                    editor: editor,
                    text: 'Editar'
                },
                {
                    extend: 'remove',
                    editor: editor,
                    text: 'Eliminar'
                },
            ],
            drawCallback: function() {
                var api = this.api();
                var total = calcularTotal(api);

                $(api.table().footer()).html(
                    '<tr><td style="background-color: #ccc;"></td><td style="background-color: #ccc;"></td><td style="background-color: #ccc;"></td><td style="font-weight: bold;color: #000;background: #8e1f4f4a;">Total:</td><td style="text-align: center;background: #8e1f4f4a;">S/ ' +
                    total + '</td></tr>');
            }
        });

        // Función para calcular el total
        function calcularTotal(api) {
            let total = 0;
            api.rows().every(function() {
                var data = this.data();
                var subtotal = parseFloat(data.txtSubtotal) || 0;
                total += subtotal;
            });
            return total.toFixed(2);
        }


        const tipoFacturaSelect = document.getElementById('tipo');
        const fechaCreditoContainer = document.getElementById('fechaCreditoContainer');
        const fechaInicioInput = document.getElementById('fechaInicio');
        const fechaFinInput = document.getElementById('fechaFin');


        tipoFacturaSelect.addEventListener('change', () => {

            if (tipoFacturaSelect.value === '2') {
                fechaCreditoContainer.style.display = 'block';
                fechaInicioInput.setAttribute('required', true);
                fechaFinInput.setAttribute('required', true);
                const fechaInicio = new Date(fechaInicioInput.value);
                fechaInicio.setDate(fechaInicio.getDate() + 15);
                const fechaFinFormat = fechaInicio.toISOString().split('T')[0];
                fechaFinInput.setAttribute('min', fechaFinFormat);

            } else if (tipoFacturaSelect.value === '1') {
                fechaCreditoContainer.style.display = 'none';
                fechaInicioInput.removeAttribute('required');
                fechaFinInput.removeAttribute('required');
                fechaInicioInput.removeAttribute('min');
            }
        });


        document.getElementById('invoice-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const fecha = $("#fecha").val();
            const tipoFactura = $("#tipo").val();
            const ruc = $("#ruc").val();
            const nombre = $("#nombre").val();
            const fechaInicio = $("#fechaInicio").val();
            const fechaFin = $("#fechaFin").val();

            if (!fecha) {
                mostrarMensajeAlerta(400,
                    'Por favor, complete los campos de fecha antes de generar la factura.'
                );
            } else if (!ruc) {
                mostrarMensajeAlerta(400,
                    'Por favor, complete los campos de ruc antes de generar la factura.'
                );
            } else if (!nombre) {
                mostrarMensajeAlerta(400,
                    'Por favor, complete los campos de nombre antes de generar la factura.'
                );
            } else if (!fechaInicio) {
                mostrarMensajeAlerta(400,
                    'Por favor, complete los campos de fecha inicio antes de generar la factura.'
                );
            } else if (!fechaFin) {
                mostrarMensajeAlerta(400,
                    'Por favor, complete los campos de fecha fin antes de generar la factura.'
                );
            } else if (!tipoFactura) {
                mostrarMensajeAlerta(400,
                    'Por favor, complete los campos de tipo fin antes de generar la factura.'
                );
            } else if (table.rows().count() === 0) {
                mostrarMensajeAlerta(400,
                    'Agregue al menos un registro a la tabla antes de generar la factura.');
            } else {
                const formData = new FormData(e.target);

                formData.append('fecha', fecha);
                formData.append('ruc', ruc);
                formData.append('nombre', nombre);
                formData.append('fechaInicio', fechaInicio);
                formData.append('fechaFin', fechaFin);
                formData.append('tipoFactura', tipoFactura);

                const total = parseFloat(calcularTotal(table));
                formData.append('total', total);

                const productos = [];
                const tableData = table.rows().data();
                tableData.each(function(data) {
                    const nombre_producto = data.txtProducto;
                    const cantidad = parseInt(data.txtCantidad);
                    const precio_unitario = parseFloat(data.txtPrecioUnitario);
                    const producto = {
                        nombre_producto: nombre_producto,
                        cantidad: cantidad,
                        precio_unitario: precio_unitario,
                        subtotal: (cantidad * precio_unitario).toFixed(2)
                    };
                    productos.push(producto);
                });

                formData.append('productos', JSON.stringify(productos));

                try {
                    const response = await CRUD.sendDataNoJson('generate-factura', formData);

                    if (response.code === 200) {
                        table.clear().draw();
                        document.getElementById('nombre').value = "";
                        document.getElementById('fecha').value = "";
                        document.getElementById('ruc').value = "";
                        document.getElementById('tipo').value = "";
                        document.getElementById('fechaInicio').value = "";
                        document.getElementById('fechaFin').value = "";
                        mostrarMensajeAlerta(response.code, response.message);
                    } else {
                        mostrarMensajeAlerta(response.code, response.message);
                    }
                } catch (error) {
                    console.error('Error al generar la factura:', error);
                    mostrarMensajeAlerta(500, 'Error interno al generar la factura.');
                }
            }
        });
    </script>
@endsection

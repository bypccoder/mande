@extends('layouts.app')

@section('template_title')
    Inventory
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-tab-1">
                    <div class="card-header pb-0 d-flex justify-content-between">
                        <ul class="nav nav-tabs border-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="#" data-coreui-toggle="tab"
                                    data-coreui-target="#tab-listado"><i class="la la-home me-2"></i> Listado de Ingreso</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-coreui-toggle="tab"
                                    data-coreui-target="#tab-agregar"><i class="la la-user me-2"></i> Agregar Ingreso</a>
                            </li>
                        </ul>
                        <div>
                            <button class="btn btn-dark btn-sm" type="button" data-coreui-toggle="offcanvas"
                                data-coreui-target="#modal-filters"><i class="las la-file-download"></i> Exportar </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content bordered">
                            <div class="tab-pane fade show active" id="tab-listado" role="tabpanel">
                                <div class="">
                                    <table id="dataTable" class="table" style="width: 100%;">
                                        <thead class="thead">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Nro.Comprobante</th>
                                                <th>Nro.Serie</th>
                                                <th>Impuesto</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-agregar">
                                <div class="pt-4">
                                    <form id="frm-ingreso" novalidate class="row needs-validation">
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Tipo Comprobante</label>
                                            <select required name="voucherType" id="voucherType" class="form-control">
                                                <option value="0" selected disabled>Seleccionar</option>
                                                @foreach ($voucherTypes as $voucherType)
                                                    <option value="{{ $voucherType->id }}">{{ $voucherType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Serie Comprobante</label>
                                            <input required name="voucherSerial" id="voucherSerial" type="text"
                                                class="form-control">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label class="form-label">Numero Comprobante</label>
                                            <input required name="voucherNumber" id="voucherNumber" type="text"
                                                class="form-control">
                                        </div>
                                    </form>

                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="m-0">Detalle Ingreso</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <form id="frm-detail-ingreso" class="row">
                                                    <div class="mb-3 col-md-3">
                                                        <label class="form-label">Producto</label>
                                                        <select name="producto" id="producto" class="form-control">
                                                            <option value="0" selected disabled>Seleccionar</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 col-md-2">
                                                        <label class="form-label">Stock</label>
                                                        <input name="stock" id="stock" readonly disabled type="text" class="form-control">
                                                    </div>
                                                    <div class="mb-3 col-md-2">
                                                        <label class="form-label">Cantidad</label>
                                                        <input name="cantidad" type="text" class="form-control">
                                                    </div>
                                                    <div class="mb-3 col-md-2">
                                                        <label class="form-label">Precio Compra</label>
                                                        <input name="precio-compra" type="text" class="form-control">
                                                    </div>
                                                    <div class="mb-3 col-md-2">
                                                        <label class="form-label">Precio Venta</label>
                                                        <input name="precio-venta" type="text" class="form-control">
                                                    </div>
                                                    <div class="mb-3 col-md-3 d-flex align-items-center">
                                                        <button type="submit" class="btn btn-info text-white"><i
                                                                class="las la-check-circle"></i> Agregar elemento</button>
                                                    </div>
                                                </form>

                                                <table id="tbl-detail-ingreso" class="table">
                                                    <thead class="table-secondary">
                                                        <tr>
                                                            <th>Accion</th>
                                                            <th>Producto</th>
                                                            <th>Cantidad</th>
                                                            <th>Precio Compra</th>
                                                            <th>Precio Venta</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" class="text-end border border-0">Total</td>
                                                            <td class="border border-0"><input type="number"
                                                                    class="form-control" name="total" readonly></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6 m-auto text-center">
                                            <a href="#"
                                                class="btn btn-success btn-save btn-lg text-white rounded-pill my-4"><i
                                                    class="las la-plus-circle"></i> Guardar registro</a>
                                            <button type="reset" class="btn btn-light btn-lg rounded-pill my-4"><i
                                                    class="las la-broom"></i> Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
        })
    </script>
    <script>
        $(function() {

            // Obtener referencia al formulario y a la tabla        
            const frmIngreso = document.getElementById('frm-ingreso')
            const frmDetailIngreso = document.getElementById('frm-detail-ingreso')
            const tabla = document.getElementById('tbl-detail-ingreso')

            // Crear una matriz para almacenar los dataDetailIngreso en la sesión
            var dataDetailIngreso = [];

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                order: [
                    [0, 'desc']
                ],
                ajax: "{{ route('inventories.index') }}",
                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'voucherNumber',
                        name: 'voucherNumber'
                    },
                    {
                        data: 'voucherSerial',
                        name: 'voucherSerial'
                    },
                    {
                        data: 'voucherTax',
                        name: 'voucherTax'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

                language: {
                    paginate: {
                        previous: '<<',
                        next: '>>'
                    }
                }
            });

            function agregarFila(producto, cantidad, precioCompra, precioVenta, subTotal) {
                // Crear una nueva fila
                const fila = document.createElement('tr');

                // Crear celdas para cada dato
                const celdaProducto = document.createElement('td');
                celdaProducto.textContent = producto;

                const celdaCantidad = document.createElement('td');
                celdaCantidad.textContent = cantidad;

                const celdaPrecioCompra = document.createElement('td');
                celdaPrecioCompra.textContent = precioCompra;

                const celdaPrecioVenta = document.createElement('td');
                celdaPrecioVenta.textContent = precioVenta;

                const celdaSubtotal = document.createElement('td');
                celdaSubtotal.textContent = subTotal;

                const celdaEliminar = document.createElement('td');
                const botonEliminar = document.createElement('a');
                botonEliminar.textContent = 'X';
                botonEliminar.classList.add('btn', 'btn-warning','btn-sm','rounded-pill');
                botonEliminar.addEventListener('click', () => eliminarFila(fila));
                celdaEliminar.appendChild(botonEliminar);

                // Agregar las celdas a la fila
                fila.appendChild(celdaEliminar);
                fila.appendChild(celdaProducto);
                fila.appendChild(celdaCantidad);
                fila.appendChild(celdaPrecioCompra);
                fila.appendChild(celdaPrecioVenta);
                fila.appendChild(celdaSubtotal);

                // Agregar la fila a la tabla
                tabla.querySelector('tbody').appendChild(fila);
            }

            function eliminarFila(fila) {
                // Eliminar la fila de la tabla
                fila.remove();

                // Actualizar los dataDetailIngreso en la matriz eliminando el elemento correspondiente
                dataDetailIngreso = dataDetailIngreso.filter((dato) => dato.fila !== fila);
            }

            function calculateTotal() {

            }

            function handleSubmit(event) {
                event.preventDefault();

                const frmData = new FormData(frmDetailIngreso);
                // Obtener los valores del formulario
                const producto = frmDetailIngreso.elements['producto'].options[frmDetailIngreso.elements['producto']
                    .selectedIndex].value;
                const productoTxt = frmDetailIngreso.elements['producto'].options[frmDetailIngreso.elements[
                    'producto'].selectedIndex].text;
                const cantidad = frmDetailIngreso.elements['cantidad'].value;
                const precioCompra = frmDetailIngreso.elements['precio-compra'].value;
                const precioVenta = frmDetailIngreso.elements['precio-venta'].value;
                const subTotal = precioCompra * cantidad;

                // Agregar los dataDetailIngreso a la matriz y la fila a la tabla
                const fila = {
                    producto,
                    cantidad,
                    precioCompra,
                    precioVenta,
                    subTotal
                };
                dataDetailIngreso.push({
                    fila
                });
                agregarFila(productoTxt, cantidad, precioCompra, precioVenta, subTotal);

                // Restablecer los valores del formulario
                frmDetailIngreso.reset();
            }

            // Escuchar el evento de envío del formulario
            frmDetailIngreso.addEventListener('submit', handleSubmit);
            document.getElementById('frm_filters').addEventListener('submit', (e) => {
                e.preventDefault();
                submitFilters(e)
            })
            document.querySelector('#tab-agregar .btn-save').addEventListener('click', agregarIngreso)


            /**
             * Agregar Ingreso
             */
            function agregarIngreso(e) {
                e.preventDefault()
                if (!frmIngreso.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    mostrarMensajeAlerta(400, 'El formulario contiene campos obligatorios')                    
                    frmIngreso.classList.add('was-validated')
                    return
                }else if(dataDetailIngreso.length < 1){
                    event.preventDefault()
                    event.stopPropagation()
                    mostrarMensajeAlerta(400, 'El detalle debe tener al menos un elemento')                    
                    frmIngreso.classList.add('was-validated')
                    return
                }


                let formData = new FormData(frmIngreso)


                const formDataJSON = {};
                for (const [key, value] of formData) {
                    formDataJSON[key] = value;
                }

                console.log(formDataJSON)


                formDataJSON['details'] = dataDetailIngreso

                CRUD.create('admin/inventories', formDataJSON).then((response) => {
                    mostrarMensajeAlerta(response.code)

                    if (response.code == 200) {
                        frmIngreso.reset()
                        frmDetailIngreso.reset()
                        dataDetailIngreso = []
                        tabla.querySelector('tbody').innerHTML = ""
                    }
                })

            }

            function submitFilters(e) {
                const formData = new FormData(e.target)

                const urlParams = new URLSearchParams(formData.entries());
                const queryString = urlParams.toString();

                window.location.href = 'inventories/report/?' + queryString
            }

            $('#producto').select2({
                placeholder: 'Buscar..',
                theme: 'bootstrap-5',
                minimumInputLength: 3,
                ajax: {
                    url: "inventories/products",
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
                                id: item.id,
                                text: item.name
                            })
                        })
                        return {
                            results: arrResult
                        };
                    },
                    cache: true
                }
            });
            $('#producto').on('select2:select', function (e) {
                let id = e.params.data.id;
                CRUD.sendData('products/stock',{id: id}).then((response) => {
                    document.querySelector('#stock').value = response
                })
            });

        })
    </script>
@endsection

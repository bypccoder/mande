@extends('layouts.app')

@section('template_title')
    Product
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="mb-5">
                    <h3 class="mb-0"><span id="card_title">{{ __('Menu') }}</span></h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card bg-white">
                    <div class="card-header d-md-flex">
                        <div class="flex-grow-1">
                            <a href="#!" onclick="formChange()" data-coreui-toggle="modal"
                                data-coreui-target="#crudModal" class="btn btn-primary btn-modal text-white">+ Agregar</a>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <a href="#!" class="btn btn btn-outline-secondary text-black ms-2"
                                data-coreui-toggle="offcanvas" data-coreui-target="#modal-filters">Exportar</a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="dataTable" class="table">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Estado</th>
                                        <th>Tipo</th>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Cover</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="crudModal" tabindex="-1" aria-labelledby="crudModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="frmProduct" class="needs-validation" novalidate data-type="store" action="/"
                enctype="multipart/form-data">
                <input class="d-none" readonly name="id" id="id" value="" type="text">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crudModalLabel">Agregar / Editar</h5>
                        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="type">Tipo</label>
                            <select class="form-control" name="type" id="type" required>
                                <option disabled selected value="0">Seleccionar..</option>
                                @foreach ($productTypes as $productType)
                                    <option value="{{ $productType->id }}">{{ $productType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Descripcion</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="cover">Cover</label>
                            <input type="file" class="form-control" id="cover" name="cover"
                                accept="image/png, image/gif, image/jpeg" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
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
    <script>
        function formChange() {
            console.log('click')
            frmProduct.reset()
            frmProduct.classList.remove('was-validated')
            document.querySelector('#cover').setAttribute('required', 'required')
        }
    </script>
@endsection

@section('scripts')
    <script src="{{ url('') }}/js/util.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var table = null
        const frmProduct = document.getElementById('frmProduct')
        const crudModal = new coreui.Modal('#crudModal', {
            keyboard: false
        })

        $(function() {

            // Inicializamos componentes
            init()

            // Escuchar el evento de envío del formulario
            //frmProduct.addEventListener('submit', handleSubmit);
            frmProduct.addEventListener('submit', (event) => {
                if (!frmProduct.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    mostrarMensajeAlerta(400, 'El formulario contiene campos obligatorios')
                } else {
                    handleSubmit(event)
                }

                frmProduct.classList.add('was-validated')
            })

            document.getElementById('frm_filters').addEventListener('submit', submitFilters)

            document.querySelector('#dataTable').addEventListener('click', (e) => {
                e.preventDefault()
                if (e.target.classList.contains('destroy')) {
                    Swal.fire({
                        title: 'Eliminar registro',
                        text: 'Confirme si desea eliminar el registro seleccionado.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, deseo eliminar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let data = e.target.getAttribute('data-product')
                            destroy(data)
                        }
                    })
                } else if (e.target.classList.contains('view')) {
                    let data = e.target.getAttribute('data-product')
                    frmProduct.setAttribute('data-type', 'update')
                    show(data)
                } else if (e.target.classList.contains('enable')) {
                    let data = e.target.getAttribute('data-product')
                    enable(data)
                } else if (e.target.classList.contains('preview')) {
                    let data = e.target.getAttribute('href')
                    window.open(data, '_blank')
                }
            });

        })

        function init() {
            // Initialize datatable
            table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('menus.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'state',
                        name: 'state'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },                    
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'image_preview',
                        name: 'image_preview',
                        orderable: false,
                        searchable: false
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
            })

            // Initialize daterangepicker
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
        }

        function show(data) {

            frmProduct.classList.remove('was-validated')

            CRUD.sendData('menus/show', {
                id: data
            }).then((response) => {
                let data = response.data;
                let inputs = ['id', 'name', 'description', 'product_type_id']

                inputs.forEach(key => {
                    if (key == 'sub_category_id') {
                        document.querySelector('#category').value = data[key]
                    } else if (key == 'product_type_id') {
                        document.querySelector('#type').value = data[key]
                    } else {
                        document.querySelector('#' + key).value = data[key]
                    }

                })

                document.querySelector('#cover').removeAttribute('required')

                crudModal.show()
            })
        }

        function enable(data) {
            Swal.fire({
                title: 'Ingresar cantidad',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                showLoaderOnConfirm: true,
                preConfirm: (quantity) => {
                    console.log(quantity)
                    return CRUD.sendData('menus/enable', {
                        id: data,
                        quantity: quantity
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((response) => {
                if (response.isConfirmed) {
                    mostrarMensajeAlerta(response.value.code)
                    if (response.value.code == 200) {
                        table.ajax.reload()
                    }
                }
            })

        }

        function destroy(data) {

            CRUD.delete('admin/menus/destroy', data).then((response) => {
                mostrarMensajeAlerta(response.code, response.message)
                if (response.code == 200) {
                    table.ajax.reload()
                }
            })
        }

        function submitFilters(e) {
            e.preventDefault()

            const formData = new FormData(e.target)

            const urlParams = new URLSearchParams(formData.entries());
            const queryString = urlParams.toString();

            window.location.href = 'menus/report/?' + queryString
        }

        function handleSubmit(event) {
            event.preventDefault()
            var element = event.target

            const frmData = new FormData(frmProduct);

            var FN = (response) => {
                if (response.code == 200) {
                    frmProduct.reset()

                    table.ajax.reload()
                    element.closest('.modal').querySelector('.btn-secondary').click()
                    mostrarMensajeAlerta(response.code)

                    frmProduct.setAttribute('data-type', 'store')
                }
            }

            if (element.getAttribute('data-type') == 'store') {
                frmData.delete('id')
                CRUD.sendDataNoJson('menus/store', frmData).then((response) => {
                    FN(response)
                })
            } else {
                CRUD.sendDataNoJson('menus/update', frmData).then((response) => {
                    FN(response)
                })
            }
        }
    </script>
@endsection

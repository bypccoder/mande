@extends('layouts.app')

@section('template_title')
    Category
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="mb-5">
                    <h3 class="mb-0"><span id="card_title">{{ __('Categorias') }}</span></h3>
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
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Imagen Portada</th>
                                        <th>Acciones</th>
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
    <div class="modal fade" id="crudModal" tabindex="-1" aria-labelledby="crudModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="frmCategory" class="needs-validation" novalidate data-type="store" action="/"
                enctype="multipart/form-data">
                <input class="d-none" readonly name="id" id="id" value="" type="text">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crudModalLabel">Agregar / Editar</h5>
                        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Descripcion</label>
                            <textarea type="text" class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="cover">Imagen Portada</label>
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
        $(function() {

            const frmCategory = document.getElementById('frmCategory')
            const crudModal = new coreui.Modal('#crudModal', {
                keyboard: false
            })


            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('categories.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
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
            });

            function submitFilters(e) {
                const formData = new FormData(e.target)

                const urlParams = new URLSearchParams(formData.entries());
                const queryString = urlParams.toString();

                window.location.href = 'categories/report/?' + queryString
            }

            function show(data) {
                CRUD.sendData('categories/show', {
                    id: data
                }).then((response) => {
                    let data = response.data;
                    let inputs = ['id', 'name', 'description']

                    inputs.forEach(key => {
                        document.querySelector('#' + key).value = data[key]
                    })

                    document.querySelector('#cover').removeAttribute('required')

                    crudModal.show()
                })
            }

            function destroy(data) {

                CRUD.delete('admin/categories/destroy', data).then((response) => {
                    mostrarMensajeAlerta(response.code, response.message)
                    if (response.code == 200) {
                        table.ajax.reload()
                    }
                })
            }

            function handleSubmit(event) {
                event.preventDefault()
                var element = event.target

                const frmData = new FormData(frmCategory);

                var FN = (response) => {
                    if (response.code == 200) {
                        frmCategory.reset()

                        table.ajax.reload()
                        element.closest('.modal').querySelector('.btn-secondary').click()
                        mostrarMensajeAlerta(response.code)

                        frmCategory.setAttribute('data-type', 'store')
                    }
                }

                if (element.getAttribute('data-type') == 'store') {
                    frmData.delete('id')
                    CRUD.sendDataNoJson('categories/store', frmData).then((response) => {
                        FN(response)
                    })
                } else {
                    CRUD.sendDataNoJson('categories/update', frmData).then((response) => {
                        FN(response)
                    })
                }
            }

            // Escuchar el evento de envío del formulario
            frmCategory.addEventListener('submit', (event) => {
                if (!frmCategory.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    mostrarMensajeAlerta(400, 'El formulario contiene campos obligatorios')
                } else {
                    handleSubmit(event)
                }

                frmCategory.classList.add('was-validated')
            })

            document.getElementById('frm_filters').addEventListener('submit', (e) => {
                e.preventDefault();
                submitFilters(e)
            })
            document.querySelector('.card-body').addEventListener('click', (e) => {
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
                            let data = e.target.getAttribute('data-id')
                            destroy(data)
                        }
                    })
                } else if (e.target.classList.contains('view')) {
                    let data = e.target.getAttribute('data-id')
                    frmCategory.setAttribute('data-type', 'update')
                    show(data)
                } else if (e.target.classList.contains('preview')) {
                    let data = e.target.getAttribute('href')
                    window.open(data, '_blank')
                }
            });

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
@endsection

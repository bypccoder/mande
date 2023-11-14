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
                    <h3 class="mb-0"><span id="card_title">{{ __('Productos') }}</span></h3>
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
                            <table id="dataTable" class="table table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Categoria</th>
                                        <th>Codigo</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Precio Compra</th>
                                        <th>Precio Venta</th>
                                        <th>Imagen Portada</th>
                                        <th></th>
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
        <div class="modal-dialog modal-lg">
            <form id="frmProduct" class="needs-validation" novalidate data-type="store" action="/"
                enctype="multipart/form-data">
                <input class="d-none" readonly name="id" id="id" value="" type="text">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crudModalLabel">Agregar / Editar</h5>
                        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group mb-3 d-none">
                                    <label for="category">Categoria</label>
                                    <select class="form-control" name="category" id="category">
                                        <option value="0" selected disabled>Seleccionar</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                    <label for="buy_price">Precio Compra</label>
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">S/.</span>
                                        <input value="0" type="number" step="0.01" min="0"
                                            class="form-control" id="buy_price" name="buy_price">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="sales_price">Precio Venta</label>
                                    <div class="input-group flex-nowrap">
                                        <span class="input-group-text" id="addon-wrapping">S/.</span>
                                        <input value="0" type="number" step="0.01" min="0"
                                            class="form-control" id="sales_price" name="sales_price" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="cover">Cover</label>
                                    <input type="file" class="form-control" id="cover" name="cover"
                                        accept="image/png, image/gif, image/jpeg" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div>
                                    <label for="">Categorias Disponibles</label>
                                    <select id="lista1" class="form-control bg-light" size="7">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="py-3">
                                    <a href="#" id="assoc" class="btn btn-sm btn-info text-white"><i
                                            class="las la-angle-down pe-none"></i></a>
                                    <a href="#" id="unassoc" class="btn btn-sm btn-info text-white"><i
                                            class="las la-angle-up pe-none"></i></a>
                                </div>
                                <div>
                                    <label for="">Categorias Asociadas</label>
                                    <select id="lista2" class="form-control" size="7"></select>

                                </div>
                            </div>
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
        function cleanCategoriesOnProducts() {
            // Quitamos / Agregamos las categorias asociadas
            var select1 = document.getElementById("lista1");
            var select2 = document.getElementById("lista2");

            // Restauramos las categorias a la Lista 1
            while (select2.options.length > 0) {
                var option = select2.options[0];
                select1.appendChild(option);
            }
        }

        function formChange() {
            frmProduct.reset()
            frmProduct.classList.remove('was-validated')
            document.querySelector('#cover').setAttribute('required', 'required')

            // Restauramos las categorias a la Lista 1
            cleanCategoriesOnProducts()
        }
    </script>
@endsection

@section('scripts')
    <script src="{{ url('') }}/js/util.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var table = null;

        $(function() {
            const frmProduct = document.getElementById('frmProduct')
            const crudModal = new coreui.Modal('#crudModal', {
                keyboard: false
            })
            const lista1 = document.getElementById('lista1');
            const lista2 = document.getElementById('lista2');
            const assocCategory = document.getElementById('assoc');
            const unassocCategory = document.getElementById('unassoc');

            // Inicitalizamos los componentes
            init();

            function submitFilters(e) {
                const formData = new FormData(e.target)

                const urlParams = new URLSearchParams(formData.entries());
                const queryString = urlParams.toString();

                window.location.href = 'orders/report/?' + queryString
            }

            function show(data) {
                CRUD.sendData('products/show', {
                    id: data
                }).then((response) => {
                    let data = response.data;
                    let inputs = ['id', 'name', 'description', 'buy_price', 'sales_price',
                        'sub_category_id'
                    ]

                    inputs.forEach(key => {
                        if (key == 'sub_category_id') {
                            document.querySelector('#category').value = data[key]
                        } else if (key == 'product_type_id') {
                            document.querySelector('#type').value = data[key]
                        } else {
                            document.querySelector('#' + key).value = data[key]
                        }
                    })

                    // Quitamos / Agregamos las categorias asociadas
                    var select1 = document.getElementById("lista1");
                    var select2 = document.getElementById("lista2");

                    // Restauramos las categorias a la Lista 1
                    cleanCategoriesOnProducts()

                    for (var i = select1.options.length - 1; i >= 0; i--) {
                        var opcion = select1.options[i];

                        // Comprueba si el valor de la opción está en el array    
                        if (data.categories.includes(parseInt(opcion.value))) {
                            // Clona la opción y la agrega al segundo select                            
                            var opcionClonada = opcion.cloneNode(true);
                            select2.appendChild(opcionClonada);

                            // Elimina la opción del primer select
                            select1.removeChild(opcion);
                        }
                    }

                    document.querySelector('#cover').removeAttribute('required')

                    crudModal.show()
                })
            }

            function destroy(data) {

                CRUD.delete('admin/products/destroy', data).then((response) => {
                    mostrarMensajeAlerta(response.code, response.message)
                    if (response.code == 200) {
                        table.ajax.reload()
                    }
                })
            }

            function control_stock(data) {
                CRUD.sendData('products/control-stock', data).then((response) => {
                    mostrarMensajeAlerta(response.code, response.message)
                    if (response.code == 200) {
                        table.ajax.reload()
                    }
                })
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

                // Obtenemos el listado de categorias asociadas y lo agregamos al formulario
                var select = document.getElementById("lista2");
                var selectedOptions = [];

                for (var i = 0; i < select.options.length; i++) {
                    var option = select.options[i];
                    selectedOptions.push(option.value);
                }

                frmData.append('categories', JSON.stringify(selectedOptions))


                if (element.getAttribute('data-type') == 'store') {
                    frmData.delete('id')

                    CRUD.sendDataNoJson('products/store', frmData).then((response) => {
                        FN(response)
                    })
                } else {
                    CRUD.sendDataNoJson('products/update', frmData).then((response) => {
                        FN(response)
                    })
                }
            }

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

            document.getElementById('frm_filters').addEventListener('submit', (e) => {
                e.preventDefault();
                submitFilters(e)
            })

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
                } else if (e.target.classList.contains('preview')) {
                    let data = e.target.getAttribute('href')
                    window.open(data, '_blank')
                } else if (e.target.classList.contains('control')) {

                    let action = e.target.getAttribute('data-action')
                    let title = (action == 'enable') ? 'Habilitar' : 'Deshabilitar'
                    Swal.fire({
                        title: title + ' control de stock',
                        text: 'Confirme si desea ' + title +
                            ' el control de stock de este producto',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, deseo ' + title + '!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let id = e.target.getAttribute('data-product')
                            control_stock({
                                id: id,
                                action: action
                            })
                        }
                    })
                }
            })

            assocCategory.addEventListener('click', () => {
                const selectedOption = lista1.options[lista1.selectedIndex];
                if (selectedOption) {
                    lista2.appendChild(selectedOption);
                }
            });

            unassocCategory.addEventListener('click', () => {
                const selectedOption = lista2.options[lista2.selectedIndex];
                if (selectedOption) {
                    lista1.appendChild(selectedOption);
                }
            });

            function submitFilters(e) {
                const formData = new FormData(e.target)

                const urlParams = new URLSearchParams(formData.entries());
                const queryString = urlParams.toString();

                window.location.href = 'products/report/?' + queryString
            }

        })

        function init() {
            table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'categories',
                        name: 'categories'
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
                        data: 'buy_price',
                        name: 'buy_price'
                    },
                    {
                        data: 'sales_price',
                        name: 'sales_price'
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
    </script>
@endsection

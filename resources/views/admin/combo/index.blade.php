@extends('layouts.app')

@section('template_title')
    Combos
@endsection


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="mb-5">
                    <h3 class="mb-0"><span id="card_title">{{ __('Combos') }}</span></h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card bg-white">
                    <div class="card-header d-md-flex">
                        <div class="flex-grow-1">
                            <a href="#!" data-coreui-toggle="modal" data-coreui-target="#crudModal"
                                class="btn btn-primary btn-modal text-white">+ Agregar</a>
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
                                        <th>Imagen</th>
                                        <th>Estado</th>
                                        <th>Creado el</th>
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
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crudModalLabel">Agregar / Editar</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form id="frm" action="/" data-type="store" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="id" value="">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre del Combo</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripcion</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="sales_price" class="form-label">Precio Venta</label>
                                    <input type="number" class="form-control" id="sales_price" name="sales_price"
                                        step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cover_image" class="form-label">Imagen</label>
                                    <input type="file" class="form-control" id="cover_image" name="cover_image">
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <select name="" id="products">Productos</select>

                            <div id="combo-container" class="pt-3">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-store">Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ url('') }}/js/util.js"></script>
    <script>
        var table = null
        const comboContainer = document.querySelector('#combo-container')
        const tableContainer = document.querySelector('#dataTable')
        const form = document.querySelector('#frm')
        const btnStore = document.querySelector('.btn-store')
        const crudModal = new coreui.Modal('#crudModal', {
            keyboard: false
        })

        $(() => {

            var MODEL = {
                store: (frmData) => {
                    frmData.delete('id')

                    var inputs = comboContainer.querySelectorAll('input[name="id"]');
                    var valores = [];
                    for (var i = 0; i < inputs.length; i++) {
                        valores.push(inputs[i].value);
                    }

                    frmData.append('products', JSON.stringify(valores))

                    CRUD.sendDataNoJson('combos/store', frmData).then((response) => {
                        if (response.code == 200) {
                            resetForm(form)
                            
                            table.ajax.reload()
                            form.setAttribute('data-type', 'store')
                        }
                        mostrarMensajeAlerta(response.code)
                    })
                },
                update: (frmData) => {
                    
                    var inputs = comboContainer.querySelectorAll('input[name="id"]');
                    var valores = [];
                    for (var i = 0; i < inputs.length; i++) {
                        valores.push(inputs[i].value);
                    }

                    frmData.append('products', JSON.stringify(valores))

                    CRUD.sendDataNoJson('combos/update', frmData).then((response) => {
                        if (response.code == 200) {                            
                            resetForm(form)

                            table.ajax.reload()
                            form.setAttribute('data-type', 'store')

                            crudModal.hide()
                        }
                        mostrarMensajeAlerta(response.code)
                    })
                },
                delete: (id) => {
                    CRUD.delete('admin/combos/destroy', id).then((response) => {
                        if (response.code == 200) {
                            table.ajax.reload()
                        }
                        mostrarMensajeAlerta(response.code, response.message)
                    })
                },
                show: (id) => {
                    CRUD.sendData('combos/show', {
                        id: id
                    }).then((response) => {
                        // clean container combos
                        comboContainer.innerHTML = ''
                        
                        let data = response.data;
                        let inputs = ['id', 'name', 'description', 'sales_price']

                        inputs.forEach(key => {
                            document.querySelector('#' + key).value = data[key]
                        })

                        if (data.products.length > 0) {
                            data.products.forEach(product => {
                                renderProduct(product)
                            })
                        }

                        //document.querySelector('#cover_image').removeAttribute('required')

                        crudModal.show()
                    })
                }
            }

            // Inicializar componentes
            init()
            //----------------------------------------------------------------
            // Eventos
            //----------------------------------------------------------------

            // Agregar producto a contenedor de productos segun seleccion en select2
            $('#products').on('select2:select', function(e) {
                let data = e.params.data
                renderProduct(data)
            })

            tableContainer.addEventListener('click', (e) => {
                e.preventDefault()
                // Boton EDITAR
                if (e.target.classList.contains('btn-show')) {
                    let id = e.target.getAttribute('data-id')
                    form.setAttribute('data-type', 'update')
                    MODEL.show(id)
                } else if (e.target.classList.contains('btn-deleted')) {
                    let id = e.target.getAttribute('data-id')
                    MODEL.delete(id)
                }
            })

            comboContainer.addEventListener('click', (e) => {
                e.preventDefault()
                let element = e.target
                if (element.classList.contains('btn-deleted')) {
                    element.closest('.item').remove()
                }
            })

            btnStore.addEventListener('click', (e) => {
                e.preventDefault()

                let formData = new FormData(form)
                let formType = form.getAttribute('data-type')

                if (formType == 'store') {
                    MODEL.store(formData)
                } else if (formType == 'update') {
                    MODEL.update(formData)
                }
            })


            function renderProduct(data) {
                let template =
                    `<div class="row justify-content-between item"><input class="d-none" name="id" value=":id:" /><div class="col-12 col-md-7"><div class="d-flex flex-column flex-sm-row">
                            <img class="img-fluid" src=":cover_image:" width="62" height="62"><div class="media-body my-auto">
                            <div class="row"><div class="col-auto"><p class="mb-0"><b>:title:</b></p><small class="text-muted">:description:</small>
                            </div></div></div></div></div><div class="col-auto my-auto pl-0 flex-sm-col"><input type="number" min="1" max="100" value="1">
                            </div><div class="col-auto my-auto pl-0 flex-sm-col"><p><b>S/ <span>:price:</span></b></p></div><div class="col-auto my-auto pl-0 flex-sm-col">
                            <a href="#" class="btn btn-sm btn-danger text-white rounded-pill btn-deleted"><i class="las la-trash-alt pe-none"></i></a></div><hr class="my-2"></div>`
                let html = template.replace(':title:', data.text)
                    .replace(':price:', (data.sales_price) ? data.sales_price.toFixed(2) : 0.00 )
                    .replace(':description:', data.description)
                    .replace(':cover_image:', '/storage/' + data.cover_image)
                    .replace(':id:', data.id)

                comboContainer.innerHTML += html
            }

            function resetForm(frm){
                frm.reset()
                $('#products').val(null).trigger('change')
                comboContainer.innerHTML = ''
            }



        });
    </script>
    <script>
        function init() {

            $('#products').select2({
                placeholder: 'Buscar..',
                theme: 'bootstrap-5',
                minimumInputLength: 3,
                dropdownParent: $('#crudModal'),
                ajax: {
                    url: "./combos/searchProducts",
                    type: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function(response) {
                        let arrResult = []

                        if (response.products.length < 1) return 0

                        response.products.forEach(item => {
                            arrResult.push({
                                id: item.id,
                                text: item.name,
                                sales_price: item.sales_price,
                                cover_image: item.cover_image,
                                description: item.description
                            })
                        })
                        return {
                            results: arrResult
                        };
                    },
                    cache: true
                }
            });

            table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('combos.index') }}",
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
                        data: 'cover_image',
                        name: 'cover_image'
                    },
                    {
                        data: 'product_enable',
                        name: 'product_enable'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
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
        }
    </script>
@endsection

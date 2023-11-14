@extends('layouts.app')

@section('template_title')
    Transacciones
@endsection


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="mb-5">
                    <h3 class="mb-0"><span id="card_title">{{ __('Transacciones') }}</span></h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card bg-white">
                    <div class="card-header d-md-flex btn-filters">
                        <div class="flex-grow-1">
                            <a href="#!" class="btn btn btn-outline-secondary text-black ms-2 btn-filter active"
                                data-type="regular">Productos</a>
                            <a href="#!" class="btn btn btn-outline-secondary text-black ms-2 btn-filter"
                                data-type="menu">Menus</a>
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
                                        <th>PRODUCTO</th>
                                        <th>TIPO PRODUCTO</th>
                                        <th>CANTIDAD</th>
                                        <th>TIPO TRANSACCION</th>
                                        <th>USUARIO</th>
                                        <th>CREADO EL</th>
                                    </tr>
                                </thead>
                            </table>
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
@endsection
@section('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <script src="{{ url('') }}/js/util.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var table = null

        $(() => {

            // Inicializar componentes
            init()

            //----------------------------------------------------------------
            // Eventos
            //----------------------------------------------------------------
            document.querySelector('.btn-filters').addEventListener('click', (e) => {
                e.preventDefault()
                if (e.target.classList.contains('btn-filter')) {                    
                    let urlProductos = "{{ route('transaction.index') }}?tipo=" + e.target.getAttribute(
                        'data-type');
                    table.ajax.url(urlProductos).load();

                    document.querySelectorAll('.btn-filter').forEach( (el) => {
                        el.classList.remove('active')
                    })
                    e.target.classList.add('active')
                }
            })

            document.getElementById('frm_filters').addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(e.target)
                formData.append('type', document.querySelector('.btn-filters .active').getAttribute('data-type'))

                const urlParams = new URLSearchParams(formData.entries());
                const queryString = urlParams.toString();

                window.location.href = 'transactions/report/?' + queryString
            })
        });
    </script>
    <script>
        function init() {

            table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('transaction.index') }}?tipo=regular",
                    type: 'GET',
                    data: {
                        type: 'regular'
                    },
                },
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'product_type',
                        name: 'product_type'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'transaction_type',
                        name: 'transaction_type'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
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

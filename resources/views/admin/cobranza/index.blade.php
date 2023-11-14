@extends('layouts.app')

@section('template_title')
    Cobranza
@endsection


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <!-- Page header -->
                <div class="mb-5">
                    <h3 class="mb-0"><span id="card_title">{{ __('Cobranza') }}</span></h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card bg-white">
                    <div class="card-header d-md-flex">
                        <div class="flex-grow-1">
                            Generacion de Reportes
                        </div>
                        <div class="mt-3 mt-md-0">

                        </div>
                    </div>

                    <div class="card-body">
                        <div id="container-export" class="alert alert-secondary bg-util-info alert-dismissible fade show">
                            <h5>Seleccionar el tipo de reporte</h5>
                            <hr class="my-1">
                            <div class="d-grid gap-2 d-md-block my-3 btn-reports">
                                <button type="button" class="btn btn-light active" data-type="ventas"><i
                                        class="las la-file-excel"></i>
                                    Reporte de Ventas</button>
                                <button type="button" class="btn btn-light" data-type="registro"><i
                                        class="las la-file-excel"></i>
                                    Registro de Ventas</button>
                            </div>
                            <h5>Seleccionar rango de exportacion</h5>
                            <hr class="my-1">
                            <p class="form-text text-muted">(El rango de exportacion toma como referencia el campo
                                CREADO_EL)</p>
                            <form id="frm" class="row">
                                <div class="col-auto">
                                    <label class="col-form-label">Fecha Inicio</label>
                                </div>
                                <div class="col-auto">
                                    <input type="date" name="fecha_inicio" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <label class="col-form-label">Fecha Fin</label>
                                </div>
                                <div class="col-auto">
                                    <input type="date" name="fecha_fin" class="form-control">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success text-white btn-generar">
                                        <i class="las la-cogs"></i> Generar Archivo
                                    </button>
                                    <a href="#" class="btn btn-success btn-labeled d-none btn-download"><i
                                            class="las la-file-download"></i> Descargar
                                        Archivo</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
@endsection
@section('scripts')
    <script src="{{ url('') }}/js/util.js"></script>

    <script>
        const containerExport = document.querySelector('#container-export');
        const frm = document.querySelector('#frm')

        $(() => {

            REPORT = {
                download: (frmData) => {

                    const urlParams = new URLSearchParams(frmData.entries());
                    const queryString = urlParams.toString();

                    window.location.href = 'report/?' + queryString

                }
            }

            // Inicializar componentes
            init()

            //----------------------------------------------------------------
            // Eventos
            //----------------------------------------------------------------
            frm.addEventListener('submit', (e) => {
                e.preventDefault()
                var typeReport = null

                document.querySelectorAll('.btn-reports .btn').forEach(el => {
                    if (el.classList.contains('active')) {
                        typeReport = el.getAttribute('data-type');
                        return 0
                    }
                })

                let formData = new FormData(frm)
                formData.append('type', typeReport)


                REPORT.download(formData)

                frm.reset()
            })

        });
    </script>
    <script>
        function init() {
            document.querySelector('.btn-reports').addEventListener('click', (e) => {
                e.preventDefault()
                if (e.target.classList.contains('btn')) {
                    document.querySelectorAll('.btn-reports .btn').forEach(btn => {
                        btn.classList.remove('active')
                    })
                    e.target.classList.add('active')
                }
            })
        }
    </script>
@endsection

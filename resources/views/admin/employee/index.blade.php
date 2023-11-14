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
                    <h3 class="mb-0"><span id="card_title">{{ __('Empleados') }}</span></h3>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card bg-white">
                    <div class="card-header d-md-flex">
                        <div class="flex-grow-1">
                            <a donwload="FormatoColaboradores_v1.xlsx" href="{{ url('') }}/assets/format/employee/FormatoColaboradores_v1.xlsx" class="btn btn-primary btn-modal text-white mb-3"> Descargar Formato</a>
                            <div id="respuestaMensaje" class="mx-3"></div>
                            <input type="file" id="fileEmployees" name="fileEmployees"  data-show-preview="false">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="tablaVistaPrevia" class="table">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('scripts')
    <script src="{{ url('') }}/js/util.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="{{ url('') }}/assets/vendors/bootstrap-fileinput-master/js/plugins/buffer.min.js" type="text/javascript"></script>
    <script src="{{ url('') }}/assets/vendors/bootstrap-fileinput-master/js/plugins/filetype.min.js" type="text/javascript"></script>
    <script src="{{ url('') }}/assets/vendors/bootstrap-fileinput-master/js/plugins/piexif.js" type="text/javascript"></script>
    <script src="{{ url('') }}/assets/vendors/bootstrap-fileinput-master/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.6.0/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="{{ url('') }}/assets/vendors/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
    <script src="{{ url('') }}/assets/vendors/bootstrap-fileinput-master/js/locales/es.js" type="text/javascript"></script>
    <script src="{{ url('') }}/assets/vendors/bootstrap-fileinput-master/themes/fa5/theme.js" type="text/javascript"></script>
    <script src="{{ url('') }}/assets/vendors/bootstrap-fileinput-master/themes/explorer-fa5/theme.js" type="text/javascript"></script>

    <script>
        $.fn.dataTable.ext.errMode = 'none';
        let  dataTable;
        function mostrarVistaPrevia(data) {
            const workbook = XLSX.read(data, { type: 'array' });
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            const dataArray = XLSX.utils.sheet_to_json(sheet, { header: 1 });

            // Verificar que hay datos para mostrar
            if (dataArray.length <= 1) {
                alert('No hay datos para mostrar en la vista previa.');
                return;
            }

            const dataTableColumns = dataArray[0].map((header) => ({ title: header }));
            // Iterar a través de los datos y convertir campos de fecha
            const dateColumnIndexes = [8,10]; // Ajuste estos índices según sus datos de Excel
            dataArray.slice(1).forEach((rowData) => {
                dateColumnIndexes.forEach((colIndex) => {
                    if (rowData[colIndex]) {
                        const excelDateValue = rowData[colIndex];
                        const jsDate = excelDateToJSDate(excelDateValue);
                        const day = jsDate.getDate().toString().padStart(2, '0');
                        const month = (jsDate.getMonth() + 1).toString().padStart(2, '0');
                        const year = jsDate.getFullYear();
                        const formattedDate = `${day}/${month}/${year}`;
                        rowData[colIndex] = formattedDate;
                    }
                });
            });


            // Create the DataTable
            dataTable = $('#tablaVistaPrevia').DataTable({
                data: dataArray.slice(1), // Slice to remove the header row from the data
                columns: dataArray[0].map((header) => ({ title: header })),
                deferRender: true,
                paging: true, // Enable pagination
                lengthChange: false, // Disable length change (rows per page)
                pageLength: 10, // Set the number of rows per page
            });

            // Habilitar el botón de carga después de mostrar la vista previa
            $("#fileEmployees").fileinput('enable');
        }

        function excelDateToJSDate(excelDate) {
            const excelEpoch = new Date(Date.UTC(1900, 0, 0));
            const excelDateAsMs = excelEpoch.getTime() + (excelDate - 1) * 24 * 60 * 60 * 1000;
            return new Date(excelDateAsMs);
        }


        $(document).on('change', '#fileEmployees', function () {
            const file = $(this).prop('files')[0];

            if (!file) {
                return;
            }

            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (['xls', 'xlsx'].indexOf(fileExtension) === -1) {
                // El archivo no tiene la extensión correcta, muestra un mensaje de error o realiza alguna acción adecuada.
                alert('Formato de archivo incorrecto. Por favor, seleccione un archivo .xls o .xlsx');
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                const data = e.target.result;
                mostrarVistaPrevia(data);
            };

            reader.readAsArrayBuffer(file);
            });

            $(document).ready(function () {

                $('#fileEmployees').fileinput({
                    uploadUrl: 'upload-employees',
                    language: 'es',
                    'allowedFileExtensions': ['xls', 'xlsx'],
                    initialPreviewAsData: true,
                    uploadExtraData: {
                        _token: '{{ csrf_token() }}' // Agrega el token CSRF a los datos de carga
                    }
                }).on('fileuploaded', function (event, data, previewId, index) {
                var response = data.response;
                    $("#respuestaMensaje").html('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                    setTimeout(function () {
                        $("#respuestaMensaje").html('');
                        dataTable.clear().destroy();
                        $(".fileinput-upload").addClass("disabled");
                        location.href="import-employees";
                    }, 3000);

                }).on('fileuploaderror', function(event, data, msg) {
                    var response = data.jqXHR.responseJSON;
                    $("#respuestaMensaje").html('<div class="alert alert-danger" role="alert">'+response.message+'</div>');
                    setTimeout(function() {
                        dataTable.clear().destroy();
                        //$('#vista-previa').html('');
                        $(".fileinput-upload").addClass("disabled");
                        location.href="import-employees";
                    }, 3000);

                }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
                        let progressBar = $('#' + preview).find('.file-upload-progress');
                        if(typeof preview === null){
                            progressBar.removeClass('bg-danger').addClass('bg-success');
                        }else{
                            progressBar.removeClass('bg-success').addClass('bg-danger');
                        }
                        dataTable.clear().destroy();
                        $('#vista-previa').html('');
                });

                $('#fileEmployees').on('fileclear', function(event) {
                    dataTable.clear().destroy();
                    //$('#vista-previa').html('');
                    $(".fileinput-upload").removeClass("disabled");
                    location.href="import-employees";
                })
            });
    </script>
@endsection

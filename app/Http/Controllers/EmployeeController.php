<?php

namespace App\Http\Controllers;

//use App\Exports\InventoryExport;
use App\Models\Employee;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DateTime;

/**
 * Class EmployeeController
 * @package App\Http\Controllers
 */
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('admin.employee.index');
    }

    public function import(Request $request)
    {
        $response = [
            'code' => 400,
            'status' => 'error',
            'message' => 'Ocurrió un error al subir el archivo.'
        ];

        if ($request->hasFile('fileEmployees') && $request->file('fileEmployees')->isValid()) {
            $folderDate = date('Y/m/d/');
            $uploadDir = storage_path('app/public/employees/' . $folderDate);

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $file = $request->file('fileEmployees');
            $fileName = $file->getClientOriginalName();
            $fileExt = strtolower($file->getClientOriginalExtension());

            $allowedExts = ['xls', 'xlsx'];
            $maxFileSize = 60 * 1024 * 1024;

            if (in_array($fileExt, $allowedExts) && $file->getSize() <= $maxFileSize) {
                $fileMimeType = $file->getMimeType();
                $allowedMimeTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    $uniqueFileName = uniqid() . '.' . $fileExt;

                    $file->storeAs('public/employees/' . $folderDate, $uniqueFileName);

                    // Leer columnas del excel
                    $spreadsheet = IOFactory::load($uploadDir . $uniqueFileName);

                    // Seleccionar la primera hoja del archivo (puedes cambiar el índice para cargar otras hojas)
                    $sheet = $spreadsheet->getSheet(0);

                    // Obtener la última fila y columna con datos en la hoja
                    $lastRow = $sheet->getHighestRow();
                    $lastColumn = $sheet->getHighestColumn();

                    // Convertir la letra de la columna a su índice (A -> 0, B -> 1, ...)
                    $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);

                    // Leer cada columna del archivo
                    $allRowsData = array();
                    for ($row = 2; $row <= $lastRow; $row++) { // Comenzar desde la segunda fila (ignorar cabeceras)
                        $rowData = array();
                        for ($column = 1; $column <= $lastColumnIndex; $column++) {
                            $cellValue = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                            $rowData[] = $cellValue;
                        }
                        //$allRowsData[] = $rowData;

                        $documento = $rowData[0];
                        $tipo_documento =  explode('-',$rowData[1]);
                        $nombre = $rowData[2];
                        $appat = $rowData[3];
                        $apmat = $rowData[4];
                        $telefono = $rowData[5];
                        $correo = $rowData[6];
                        $tipo_colaborador = explode('-',$rowData[7]);
                        $fecha_inicio_contrato_numerico = $rowData[8];
                        $fecha_fin_contrato_numerico = $rowData[10];
                        $empresa = $rowData[9];

                        if (empty($documento)) {
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo DOCUMENTO_COLABORADOR no debe estar vacío.'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }

                        if (strtoupper($tipo_documento[1]) !== 'DNI' && strtoupper($tipo_documento[1]) !== 'CARNET EXTRANJERIA' && strtoupper($tipo_documento[1]) !== 'RUC' && strtoupper($tipo_documento[1]) !== 'PASAPORTE') {
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo TIPO_DOCUMENTO_COLABORADOR debe ser "DNI", "CARNET DE EXTRANJERIA","RUC" o "PASAPORTE".'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }

                        if (empty($nombre)) {
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo NOMBRE_COLABORADOR no debe estar vacío.'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }

                        if (empty($appat)) {
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo APELLIDO_PATERNO_COLABORADOR no debe estar vacío.'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }

                        if (empty($apmat)) {
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo APELLIDO_MATERNO_COLABORADOR no debe estar vacío.'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }

                        if (strtoupper($tipo_colaborador[1]) !== 'REGULAR' && strtoupper($tipo_colaborador[1]) !== 'VIP') {
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo TIPO_COLABORADOR debe ser "REGULAR" o "VIP".'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }

                        try{
                            if ($fecha_inicio_contrato_numerico!='-') {

                                $fechaBase = new DateTime('1900-01-01');
                                $fechaCalculada = $fechaBase->add(new \DateInterval('P' . ($fecha_inicio_contrato_numerico - 2) . 'D'));
                                $fechaFormateada = $fechaCalculada->format('Y-m-d');

                                $dateTimeObj = DateTime::createFromFormat('Y-m-d', $fechaFormateada);

                                // Verificar si la fecha es válida
                                if (!$dateTimeObj || $dateTimeObj->format('Y-m-d') !== $fechaFormateada) {
                                    $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo FECHA_INICIO_CONTRATO no es válida o no está en el formato dd/mm/yyyy.'];
                                    http_response_code(400);
                                    return response()->json($response);
                                    exit();
                                }

                                $fecha_inicio_contrato = $fechaFormateada;

                            }else if(empty($fecha_inicio_contrato_numerico)){
                                $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo FECHA_INICIO_CONTRATO no puede ser un campo vacío.'];
                                http_response_code(400);
                                return response()->json($response);
                                exit();
                            }
                        }catch(Exception $e){
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo FECHA_INICIO_CONTRATO no es válida o no está en el formato dd/mm/yyyy.'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }

                        try{
                            if ($fecha_fin_contrato_numerico!='-') {

                                $fechaBaseF = new DateTime('1900-01-01');
                                $fechaCalculadaF = $fechaBaseF->add(new \DateInterval('P' . ($fecha_fin_contrato_numerico - 2) . 'D'));
                                $fechaFormateadaF = $fechaCalculadaF->format('Y-m-d');

                                $dateTimeObjF = DateTime::createFromFormat('Y-m-d', $fechaFormateadaF);

                                // Verificar si la fecha es válida
                                if (!$dateTimeObjF || $dateTimeObjF->format('Y-m-d') !== $fechaFormateadaF) {
                                    $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo FECHA_FIN_CONTRATO no es válida o no está en el formato dd/mm/yyyy.'];
                                    http_response_code(400);
                                    return response()->json($response);
                                    exit();
                                }

                                $fecha_fin_contrato = $fechaFormateadaF;

                            }else if(empty($fecha_fin_contrato_numerico)){
                                $fecha_fin_contrato = '';
                            }
                        }catch(Exception $e){
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo FECHA_FIN_CONTRATO no es válida o no está en el formato dd/mm/yyyy.'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }


                        if (empty($empresa)) {
                            $response = ['code' => 400, 'status' => 'error', 'message' => 'COLABORADOR - '.$rowData[0].' El campo EMPRESA no debe estar vacío.'];
                            http_response_code(400);
                            return response()->json($response);
                            exit();
                        }

                        //Guardar o Actualizar campos

                        $people_det = Person::select('*')->where('document', '=',  $documento)->first();
                        if ($people_det) {
                            //editar
                            $people_det->name = $nombre;
                            $people_det->lastname_1 = $appat;
                            $people_det->lastname_2 = $apmat;
                            $people_det->phone = $telefono;
                            $people_det->email = $correo;
                            $people_det->status_id = $fecha_fin_contrato == '' ? 1 : 2;
                            $people_det->save();

                            $employee_det = Employee::select('*')->where('person_id', '=',  $people_det->id)->first();
                            $employee_det->company = $empresa;
                            $employee_det->contract_start_date = $fecha_inicio_contrato;
                            $employee_det->contract_end_date = $fecha_fin_contrato;
                            $employee_det->type_employee = $tipo_colaborador[0];
                            $employee_det->status_employee = $fecha_fin_contrato == '' ? 1 : 2;
                            $employee_det->save();


                        }else{
                            $data_person = [
                                'name' => $nombre,
                                'lastname_1' => $apmat,
                                'lastname_2' => $apmat,
                                'document_type_id' => $tipo_documento[0],
                                'document' => $documento,
                                'phone' => $telefono,
                                'email' => $correo,
                                'status_id' => $fecha_fin_contrato == '' ? 1 : 2
                            ];

                            $person = Person::create($data_person);
                            $person_id = $person->id;

                            $data_employee = [
                                'person_id' => $person_id,
                                'company' => $empresa,
                                'contract_start_date' => $fecha_inicio_contrato,
                                'contract_end_date' => $fecha_fin_contrato,
                                'type_employee' => $tipo_colaborador[0],
                                'status_employee' => $fecha_fin_contrato == '' ? 1 : 2
                            ];

                            $employee = Employee::create($data_employee);
                        }


                        $response['code'] = 200;
                        $response['status'] = 'success';
                        $response['message'] = 'Se actualizo correctamente';


                    }

                    $response = array('success' => true, 'message' => 'Archivo subido correctamente.');
                    http_response_code(200);

                    $response = [
                        'success' => true,
                        'message' => 'Archivo subido y procesado correctamente.'
                    ];
                } else {
                    $response['message'] = 'Tipo de archivo no válido. Solo se permiten archivos xls y xlsx.';
                }
            } else {
                $response['message'] = 'Archivo no válido o tamaño excede el límite permitido.';
            }
        }

        return response()->json($response);
    }

}

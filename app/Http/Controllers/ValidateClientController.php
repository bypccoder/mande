<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidateClientController extends Controller
{
    public function validateDocument(Request $request)
    {
        $response = ['code' => 400, 'status' => 'error', 'message' => 'invalid', 'data' => []];
        $documento = $request->documento;

        $queryRaw = DB::table('people')
            ->leftJoin('companies', 'companies.person_id', '=', 'people.id')
            ->leftJoin('employees', 'employees.person_id', '=', 'people.id')
            ->leftJoin('no__employees', 'no__employees.person_id', '=', 'people.id')
            ->leftJoin('clients_vip', 'clients_vip.person_id', '=', 'people.id')
            ->where('people.document', $documento)
            ->select(
                'companies.id as company_id',
                'employees.id as employee_id',
                'employees.status_id as employees_status_id',
                'companies.company as company_name',
                'clients_vip.id as clientsvip_id',
                'clients_vip.status_id as clients_vip_status_id', 
                'no__employees.id as no_employee_id',
                'no__employees.status_id as no_employee_status_id', 
                'people.name',
                'people.document',
                'people.id',
                'people.document_type_id',
                'people.document',
                'people.email',
                'people.lastname_1',
                'people.lastname_2',                
                DB::raw('CONCAT(people.lastname_1, " " ,people.lastname_2) as last_name'),
                'employees.contract_start_date',
                'employees.company',
                'employees.type_employee'
            );

        if ($queryRaw->count() > 0) {
            $query = $queryRaw->first();

            if (empty($query->email)) {

                $response['code'] = 500;
                $response['status'] = 'update-email';
                $response['message'] = 'Al guardar este formulario, tu dirección de correo electrónico se guardará para enviarte el resumen de tu pedido con tu comprobante de pago';
                $response['data'] =  ['id' => $query->id, 'name' => $query->name, 'lastname_1' => $query->lastname_1, 'lastname_2' => $query->lastname_2, 'number_document' => $query->document, 'document_type_id' => $query->document_type_id];
            } else {

                // Cuantas bases se encuentra el cliente
                $clientBases = count($this->evaluateClient($query));

                if ( $clientBases > 1 ) {
                    // Usuario se encuentra en la tabla 'company' y al menos en una de las otras tablas
                    $response['code'] = 500;
                    $response['status'] = 'danger';
                    $response['message'] = 'El usuario tiene mas de un perfil, por favor comuniquese con el administrador del sistema para poder regularizar el registro.';
                } else {                    
                    if ($query->company_id) {
                        $response['code'] = 200;
                        $response['status'] = 'success';
                        $response['message'] = 'Se encontro el registro en compañias';

                        session()->put('person', $query);
                    } elseif ($query->employee_id) {
                        $response['code'] = 200;
                        $response['status'] = 'success';
                        $response['message'] = 'Se encontro el registro en employees';

                        session()->put('person', $query);
                    } elseif ($query->no_employee_id) {
                        $response['code'] = 200;
                        $response['status'] = 'success';
                        $response['message'] = 'Se encontro el registro en noEmployees';
                        session()->put('person', $query);
                    } elseif ($query->clientsvip_id) {
                        $response['code'] = 200;
                        $response['status'] = 'success';
                        $response['message'] = 'Se encontro el registro en clientsVip';
                        session()->put('person', $query);
                    }
                }
            }
        } else {
            $response['code'] = 500;
            $response['status'] = 'info';
            $response['message'] = 'Completa la siguiente informacion, por unica vez.';
        }


        return response()->json($response);
    }

    private function evaluateClient($client)
    {
        $data = ["employee_id" ,"clientsvip_id" ,"no_employee_id"];
        $dataStatus = ["employee_id" => 'employees_status_id' ,"clientsvip_id" => 'clients_vip_status_id' ,"no_employee_id" => 'no_employee_status_id'];
        
        $notNullProperties = [];
        foreach ($client as $property => $value) {
            if (in_array($property, $data) && $value !== null) {
                $value = $dataStatus[$property];
                if( $client->$value ){
                    $notNullProperties[$property] = $value;
                }                
            }
        }
        return $notNullProperties;
    }

    public function remove(Request $request)
    {
        session()->forget('SubCategoriesInCart');
    }
}

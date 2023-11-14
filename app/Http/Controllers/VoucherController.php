<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function store(Request $request)
    {

        $response = ['code' => 400, 'status' => 'error', 'message' => 'invalid'];
        $person_id = session('person')->id;

        $data = [
            'voucher_type_id' => '',
            'order_id' => '',
            'charge_code' => '',
            'document' => '',
            'client' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'payment_condition' => '',
            'vat' => '',
            'subtotal' => '',
            'total' => '',
            'cash' => '',
            'change' => '',
            'status_id' => ''
            
        ];

        $order = Voucher::create($data);

        if ($order) {
            $response['code'] = 200;
            $response['status'] = 'success';
            $response['message'] = 'Se agrego correctamente';
        }

        return response()->json($response);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FormMethod;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CloseSalesController extends Controller
{
    public function index(Request $request)
    {
        $fecha = date('Y-m-d', strtotime('2023-07-14'));

        $consulta = Order::select('payment_methods.payment_method AS payment_method', DB::raw('SUM(orders.amount) as total_amount'))
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
            ->where('orders.date_order', $fecha)
            ->groupBy('payment_methods.payment_method')
            ->get();

        // Calcular el total de la suma de los montos
        $totalSumaMontos = $consulta->sum('total_amount');

        if ($request->expectsJson()) {

            $model = [];

            $colors = [];
            $hoverColors = [];
            $methods = [];
            #$model = PaymentMethod::select('payment_method','color')->get();

            foreach ($consulta as $item){
                $model = PaymentMethod::select('color')->where('payment_method','=',$item->payment_method)->first();
                
                $methods[] = $item->payment_method;
                $colors[] = "#". $model->color;
                //get rgb colors from color column
                list($r, $g, $b) = sscanf($model->color, "%02x%02x%02x");

                $hoverColors[] = "rgb( $r $g $b / 82%)";
            }

            // Calcular los porcentajes para cada metodo de pago
            $consulta->transform(function ($item) use ($totalSumaMontos) {
                $item->porcentaje = round(($item->total_amount / $totalSumaMontos) * 100,2);
                return $item;
            });

            $response = [
                'code' => 200,
                'status' => 'success',
                'data' => [
                    'methods' => $methods,
                    'colors' => $colors,
                    'hoverColors' => $hoverColors,
                    'values' => $consulta
                ]
            ];
            return response()->json($response);
        }

        $data = [
            'total_sales' => $consulta,
            'total' => $totalSumaMontos
        ];

        return view('kiosk.close_sales.index', $data);
    }
}

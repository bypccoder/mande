<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Cobranza1Report implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function title(): string
    {
        return "Reporte Ventas";
    }

    public function collection()
    {
        $data = Order::select(
            'orders.id',
            'order_types.name as order_type',
            'orders.internal_code',
            'vouchers.charge_code',
            'vouchers.client',
            'employees.company',
            'people.document',
            'orders.date_order',
            DB::raw('DATE_FORMAT(orders.created_at, "%H:%i:%s") as created_time',),
            'payment_methods.payment_method',
            'orders.internal_code as code',
            'sub_categories.name as sub_category',
            'products.name as product_name',
            'order_details.quantity',
            'order_details.total',
            'orders.date_order as fecha',
            DB::raw('MONTH(orders.created_at) as month',),
            DB::raw('DAYOFWEEK(orders.created_at) as day',)
        )
            ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
            ->leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
            ->leftJoin('voucher_types', 'voucher_types.id', '=', 'vouchers.voucher_type_id')
            ->leftJoin('order_types', 'order_types.id', '=', 'orders.order_type_id')
            ->leftJoin('people', 'people.id', '=', 'orders.person_id')
            ->leftJoin('employees', 'employees.id', '=', 'people.id')
            ->leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
            ->leftJoin('products', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
            ->where('orders.date_order', '>=', $this->params['fecha_inicio'])
            ->where('orders.date_order', '<=', $this->params['fecha_fin'])
            ->get();

        $data->transform(function ($item) {
            $item->month = $this->numeroAMes($item->month);
            $item->day = $this->numeroADia($item->day - 1);

            return $item;
        });

        return $data;
    }



    public function headings(): array
    {
        return [
            'ID',
            'TIPO DE PEDIDO',
            'PEDIDO',
            'CPE',
            'NOMBRE',
            'RAZON SOCIAL',
            'DNI',
            'FECHA',
            'HORA',
            'FORMA PAGO',
            'COD. INTERNO',
            'CATEGORIA',
            'PRODUCTO',
            'CANTIDAD',
            'IMPORTE',
            'FECHA',
            'MES',
            'DIA'
        ];
    }

    function numeroADia($numero)
    {
        $dias = [
            'Domingo',
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes',
            'Sábado',
        ];

        return $dias[$numero];
    }

    function numeroAMes($numero)
    {
        $meses = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre',
        ];

        return $meses[$numero - 1]; // Resta 1 para ajustar el índice del array
    }
}

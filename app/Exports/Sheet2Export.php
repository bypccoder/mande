<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Sheet2Export implements FromCollection, WithTitle, WithHeadings
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function collection()
    {
        $data = OrderDetail::select(
            'order_details.id as order_detail',
            'orders.id as order_id',
            'order_types.name as order_type',
            'orders.id as order_id_',
            'vouchers.client',
            'vouchers.document',
            'vouchers.created_at',
            'vouchers.payment_condition',
            'sub_categories.name as sub_category',
            'products.name as product',
            'order_details.quantity',
            'order_details.total',
            'orders.status_sunat'
        )
            ->leftJoin('orders', 'orders.id', '=', 'order_details.order_id')
            ->leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
            ->leftJoin('order_types', 'order_types.id', '=', 'orders.order_type_id')
            ->leftJoin('products', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
            ->where('orders.date_order', '>=', $this->params['date_start'])
            ->where('orders.date_order', '<=', $this->params['date_end'])
            ->get();
        /*
            $strSql = 'select `order_details`.`id`, `orders`.`id`, `order_types`.`name`, `orders`.`id`, `vouchers`.`client`, `vouchers`.`document`, `vouchers`.`created_at`, `vouchers`.`payment_condition`, `sub_categories`.`name`, `products`.`name`, `order_details`.`quantity`, `order_details`.`total` 
            from `order_details` 
            left join `orders` on `orders`.`id` = `order_details`.`order_id` 
            left join `vouchers` on `vouchers`.`order_id` = `orders`.`id` 
            left join `order_types` on `order_types`.`id` = `orders`.`order_type_id` 
            left join `products` on `products`.`id` = `order_details`.`product_id` 
            left join `sub_categories` on `sub_categories`.`id` = `products`.`sub_category_id`';

            $datas = DB::select($strSql);*/

        $data->transform(function ($item) {
            
            if ($item->status_sunat === 0) {
                $item->total *= -1; // Multiplicamos por -1 para hacer el valor negativo
                $item->status_sunat = 'ANULADO';
            }else{
                $item->status_sunat = 'ENVIADO';
            }
            return $item;
        });

        return $data;
    }

    public function title(): string
    {
        return 'DETALLE PEDIDOS';
    }

    public function headings(): array
    {
        return [
            'ID DETALLE PEDIDO',
            'ID PEDIDO',
            'TIPO PEDIDO',
            'COD.INTERNO',
            'CLIENTE',
            'DOCUMENTO',
            'CREADO EL',
            'FORMA DE PAGO',
            'CATEGORIA',
            'PRODUCTO',
            'CANTIDAD',
            'PRECIO',
            'ESTADO'
        ];
    }
}

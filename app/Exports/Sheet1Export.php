<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Sheet1Export implements FromCollection, WithTitle, WithHeadings
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function collection()
    {
        $data = Order::select('orders.id','order_types.name as order_type','voucher_types.name' , 'vouchers.charge_code', 'vouchers.number_ticket' ,'vouchers.client', 'vouchers.document', 'vouchers.total','orders.status_sunat','vouchers.created_at')
            ->leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
            ->leftJoin('voucher_types', 'voucher_types.id', '=', 'vouchers.voucher_type_id')
            ->leftJoin('order_types', 'order_types.id', '=', 'orders.order_type_id')
            ->where('orders.date_order', '>=', $this->params['date_start'])
            ->where('orders.date_order', '<=', $this->params['date_end'])
            ->get();

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
        return 'PEDIDOS';
    }

    public function headings(): array
    {
        return [
            'ID PEDIDO',
            'TIPO PEDIDO',    
            'TIPO COMPROBANTE',
            'COD.INTERNTO',
            'TICKET.IMPR',
            'NOMBRE',
            'DOCUMENTO',
            'TOTAL',
            'ESTADO',
            'CREADO EL'
        ];
    }
}

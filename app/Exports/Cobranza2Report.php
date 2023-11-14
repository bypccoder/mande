<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Order;
use DateTime;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Cobranza2Report implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function title(): string
    {
        return "Registro de Ventas";
    }

    public function collection()
    {
        $data = Order::select(
            DB::raw("DATE(vouchers.created_at)"),
            'voucher_types.sunat_code',
            DB::raw("SUBSTRING_INDEX(vouchers.charge_code, '-', 1) as serie"),
            DB::raw("SUBSTRING_INDEX(vouchers.charge_code, '-', -1) as number"),
            'vouchers.document',
            'vouchers.client',
            'vouchers.subtotal',
            'vouchers.vat',
            DB::raw("'0,00' as inafecta"),
            DB::raw("'0,00' as exonerado"),
            DB::raw("'0,00' as expo"),
            DB::raw("'0,00' as perc"),
            'vouchers.total',
            DB::raw("1000 as tc"),
            DB::raw("'' as type_document_ref"),
            DB::raw("'' as fecha_emi_ref"),
            DB::raw("'' as serie_ref"),
            DB::raw("'' as numero_ref"),
            DB::raw("'70111.01' as cuenta_venta"),
            DB::raw("'01' as cond_pago"),
            DB::raw("'01' as cta_cobranza"),
            DB::raw("'' as med_pago"),
            DB::raw("'' as nro_pago"),
            DB::raw("orders.date_end_credit as fecha_pago"),
            DB::raw("vouchers.total as tot_pago"),
            DB::raw("'' as plantilla"),
            DB::raw("orders.date_end_credit as fecha_vencimiento"),
            DB::raw("'' as tipo_documento"),
            DB::raw("orders.status_sunat as estado_al"),
            DB::raw("'' as guias")
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
            # Documento de referencia
            if ($item->sunat_code == '07') {
                $item->type_document_ref = (substr($item->serie, 1) == 'F') ? '01' : '03';

                $beforeOrder = Order::find($item->number);
                $item->fecha_emi_ref = $beforeOrder->date_order;

                $item->serie_ref = $item->serie;
                $item->numero_ref = $item->number;
                $item->cuenta_venta = '';
            }

            $status_sunat = [
                '1' => 'Enviado',
                '2' => 'Anulado',
                '3' => 'Rechazado por SUNAT'
            ];

            $item->estado_al = (isset($status_sunat[$item->estado_al])) ? $status_sunat[$item->estado_al] : '';

            return $item;
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'FEC. EMI.',
            'TD',
            'SERIE',
            'NUMERO',
            'RUC',
            'RAZON SOCIAL',
            'SUBTOTAL',
            'IGV',
            'INAFECTA',
            'EXONERADO',
            'EXPO',
            'PERC',
            'TOTAL',
            'T/C',
            'TIPO DOC REF',
            'FECHA EMI REF',
            'SERIE REF',
            'NUMERO REF',
            'CTA VENTA',
            'Cond. Pago',
            'CTA COBRANZA',
            'MED PAGO',
            'NRO PAGO',
            'FEC PAGO',            
            'TOT PAGO',
            'PLANTILLA',
            'FEC.VCTO',
            'TIP. DOC IDENTIDAD	',
            'ESTADO (AL '. $this->params['fecha_fin'] .')',
            'GUIAS'
        ];
    }
}

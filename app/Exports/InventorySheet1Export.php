<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class InventorySheet1Export implements FromCollection, WithHeadings, WithTitle
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function title(): string
    {
        return "INVENTARIO";
    }

    public function collection()
    {
        $data = Inventory::select(
            'inventories.id',
            'inventories.description',
            'voucher_types.name as voucher_type',
            'inventories.voucherSerial',
            'inventories.voucherNumber',
            'inventories.voucherTax',
            'inventories.created_at'
        )
        
        ->leftJoin('voucher_types', 'voucher_types.id', '=', 'inventories.voucherType')
            ->where('inventories.created_at', '>=', $this->params['date_start'])
            ->where('inventories.created_at', '<=', $this->params['date_end'])
            ->get();

        return $data;
    }


    public function headings(): array
    {
        return [
            'ID',
            'DESCRIPCION',
            'COMPROBANTE TIPO',
            'COMRPOBANTE SERIAL',
            'COMPROBANTE NUMERO',
            'COMPROBANTE IGV',
            'CREADO EL'
        ];
    }
}

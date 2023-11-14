<?php

namespace App\Exports;

use App\Models\Inventory_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class InventorySheet2Export implements FromCollection, WithHeadings, WithTitle
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function title(): string
    {
        return "INVENTARIO DETALLE";
    }

    public function collection()
    {
        $data = Inventory_detail::select(
            'inventories_details.id',
            'inventories_details.inventory_id',
            'products.name as product',
            'inventories_details.quantity',
            'inventories_details.purchase_price',
            'inventories_details.sale_price',
            'inventories_details.subtotal',
            'inventories_details.created_at',
        )
            ->leftJoin('products', 'products.id', '=', 'inventories_details.product_id')
            ->where('inventories_details.created_at', '>=', $this->params['date_start'])
            ->where('inventories_details.created_at', '<=', $this->params['date_end'])
            ->get();

        return $data;
    }


    public function headings(): array
    {
        return [
            'ID INVENTARIO',
            'ID DETALLE',
            'PRODUCTO',
            'CANTIDAD',
            'PRECIO COMPRA',
            'PRECIO VENTA',
            'SUBTOTAL',
            'CREADO EL',
        ];
    }
}

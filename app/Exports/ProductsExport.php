<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings, WithTitle
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function collection()
    {
        $data = Product::select(
            'products.id',
            'sub_categories.name as sub_category',
            'products.code',
            'products.name',
            'products.description',
            'products.buy_price',
            'products.sales_price',
            'products.cover_image',
            'product_types.name as product_type',
            'products.created_at',
        )
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
            ->leftJoin('product_types', 'product_types.id', '=', 'products.id')
            ->where('products.created_at', '>=', $this->params['date_start'])
            ->where('products.created_at', '<=', $this->params['date_end'])
            ->get();

        return $data;
    }

    public function title(): string
    {
        return "PRODUCTOS";
    }

    public function headings(): array
    {
        return [
            'id',
            'SUB CATEGORIA',
            'CODIGO',
            'NOMBRE',
            'DESCRIPCION',
            'PRECIO COMPRA',
            'PRECIO VENTA',
            'IMAGEN',
            'TIPO DE PRODUCTO',
            'CREADO EL',
        ];
    }
}

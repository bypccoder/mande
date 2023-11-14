<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExport implements FromCollection, WithHeadings, WithTitle
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function title(): string
    {
        return "CATEGORIAS";
    }
    
    public function collection()
    {
        $data = Category::select(
            'id',
            'name',
            'description',
            'cover_image',
            'created_at',
        )
            ->where('categories.created_at', '>=', $this->params['date_start'])
            ->where('categories.created_at', '<=', $this->params['date_end'])
            ->where('categories.status_id', '!=', 0)
            ->get();

        return $data;
    }



    public function headings(): array
    {
        return [
            'ID',
            'NOMBRE',
            'DESCRIPCION',
            'IMAGEN',
            'CREADO EL'
        ];
    }
}

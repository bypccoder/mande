<?php

namespace App\Exports;

use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubCategoryExport implements FromCollection, WithHeadings, WithTitle
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function title(): string
    {
        return "GRUPOS";
    }

    public function collection()
    {
        $data = SubCategory::select(            
            'sub_categories.id',
            'categories.name as category',
            'sub_categories.name',
            'sub_categories.description',
            'sub_categories.cover_image',
            'sub_categories.created_at',
        )
            ->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id')
            ->where('sub_categories.created_at', '>=', $this->params['date_start'])
            ->where('sub_categories.created_at', '<=', $this->params['date_end'])
            ->get();

        return $data;
    }


    public function headings(): array
    {
        return [
            'ID',
            'CATEGORIA PADRE',
            'NOMBRE',
            'DESCRIPCION',
            'IMAGEN',
            'CREADO EL'
        ];
    }
}

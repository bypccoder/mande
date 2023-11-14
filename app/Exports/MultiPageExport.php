<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiPageExport implements WithMultipleSheets
{
    protected $params;

    use Exportable;

    public function __construct($params){
        $this->params = $params;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Página 1
        $sheets[] = new Sheet1Export($this->params);

        // Página 2
        $sheets[] = new Sheet2Export($this->params);

        return $sheets;
    }
}

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InventoryExport implements WithMultipleSheets
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
        $sheets[] = new InventorySheet1Export($this->params);

        // Página 2
        $sheets[] = new InventorySheet2Export($this->params);

        return $sheets;
    }
}

<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionExport implements FromCollection, WithHeadings, WithTitle
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function title(): string
    {
        return "TRANSACCIONES";
    }

    public function collection()
    {
        // Default regular type
        $arrTypes = ['REGULAR'];
        if( $this->params['type'] == 'menu' ){
            $arrTypes = ['FONDO','ENTRADA','ADICIONAL'];
        }

        $data = Transaction::select(
            'transactions.id',
            'transactions.product_name',
            'transactions.product_type',
            'transactions.quantity',
            'transaction_types.name',
            'transactions.user_name',
            'transactions.created_at'
        )
            ->leftJoin('product_types', 'product_types.id', '=', 'transactions.product_type')
            ->leftJoin('transaction_types', 'transaction_types.id', '=', 'transaction_type_id')
            ->where('transactions.created_at', '>=', $this->params['date_start'] . ' 00:00:00')
            ->where('transactions.created_at', '<=', $this->params['date_end'] . ' 23:59:59')
            ->whereIn('transactions.product_type',$arrTypes)
            ->get();

        return $data;
    }



    public function headings(): array
    {
        return [
            'ID',
            'PRODUCTO',
            'TIPO PRODUCTO',
            'CANTIDAD',
            'TIPO TRANSACCION',
            'USUARIO',
            'CREADO EL'
        ];
    }
}

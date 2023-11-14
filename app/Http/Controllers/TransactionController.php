<?php

namespace App\Http\Controllers;

use App\Exports\TransactionExport;
use App\Models\ProductType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $type = ($request->tipo == 'regular') ? ['REGULAR'] : ['FONDO','ENTRADA','ADICIONAL'];


            $combos = Transaction::select('*')
                ->whereIn('product_type', $type)
                ->get();

            return DataTables::of($combos)
                ->addIndexColumn()                
                ->addColumn('transaction_type', function ($row) {
                    $html = ($row->transaction_type_id == 1) ? '<span class="badge bg-info">Ingreso</span>' : '<span class="badge bg-danger">Salida</span>';
                    return $html;
                })
                ->rawColumns(['transaction_type'])
                ->make(true);
        }

        return view('admin.transaction.index');
    }

    public function export(Request $request)
    {
        return Excel::download(new TransactionExport($request->all()), 'archivo_excel.xlsx');
    }
}

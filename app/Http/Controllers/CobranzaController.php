<?php

namespace App\Http\Controllers;

use App\Exports\Cobranza1Report;
use App\Exports\Cobranza2Report;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

/**
 * Class CategoryController
 * @package App\Http\Controllers
 */
class CobranzaController extends Controller
{
    public function index(Request $request)
    {


        return view('admin.cobranza.index');
    }

    public function generateReport(Request $request)
    {
        $typeReport = trim($request->type);

        $randomFileName = 'cobranza_report_' . time() . '_' . Str::random(6) . '.xlsx';

        if ($typeReport == 'ventas') {
            return Excel::download(new Cobranza1Report($request->all()), $randomFileName);            
        }else if ($typeReport == 'registro') {
            return Excel::download(new Cobranza2Report($request->all()), $randomFileName);            
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MultiPageExport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ExcelController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download(new MultiPageExport($request->all()), 'archivo_excel.xlsx');
    }
}

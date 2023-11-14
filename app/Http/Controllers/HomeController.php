<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Stock;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $stocks = Stock::select('stocks.id','products.name as product','stocks.quantity')
                ->leftJoin('products', 'products.id', '=', 'stocks.product_id')                
                ->get();

            return DataTables::of($stocks)
                ->addIndexColumn()
                ->addColumn('alert', function ($row) {
                    $badge = '<span class="badge rounded-pill text-bg-success text-white">NORMAL</span>';
                    if($row->quantity = 0) {    
                        $badge = '<span class="badge rounded-pill text-bg-danger text-white">SIN STOCK</span>';
                    }else if($row->quantity < 5 ) {
                        $badge = '<span class="badge rounded-pill text-bg-warning text-white">ALERTA</span>';
                    }
                    return $badge;
                })
                ->rawColumns(['alert'])
                ->make(true);
        }

        $today = Carbon::now()->toDateString();

        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        $countOrdersToday = Order::whereDate('created_at', $today)->count();
        $countOrdersWeek = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

        $data = [
            'ordersToday' => $countOrdersToday,
            'ordersWeek' => $countOrdersWeek
        ];

        return view('admin.home.dashboard', $data);
    }
}

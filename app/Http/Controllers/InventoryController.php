<?php

namespace App\Http\Controllers;

use App\Exports\InventoryExport;
use App\Models\Inventory;
use App\Models\Inventory_detail;
use App\Models\Product;
use App\Models\VoucherType;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

/**
 * Class InventoryController
 * @package App\Http\Controllers
 */
class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Inventory::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    //$actionBtn = '<a href="javascript:void(0)" data-product="' . $row->id . '" class="view  btn btn-light rounded-pill btn-sm"><i class="las la-pen pe-none"></i></a> 
                    //              <a href="javascript:void(0)" data-product="' . $row->id . '" class="destroy  btn btn-light rounded-pill btn-sm"><i class="lar la-trash-alt pe-none"></i></a>';
                    $actionBtn = '<a href="javascript:void(0)" data-product="' . $row->id . '" class="destroy  btn btn-light rounded-pill btn-sm"><i class="lar la-trash-alt pe-none"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $voucherTypes = VoucherType::select('id', 'name')->get();

        $data = ['voucherTypes' => $voucherTypes];

        return view('inventory.index', $data);
    }

    public function create()
    {
        $inventory = new Inventory();
        return view('inventory.create', compact('inventory'));
    }

    public function store(Request $request)
    {
        //request()->validate(Inventory::$rules);

        $response = [
            'code' => 400
        ];

        $dataInventory = [
            'person_id' => session('person')->id,
            'voucherNumber' => $request->voucherNumber,
            'voucherSerial' => $request->voucherSerial,
            'voucherTax' => $request->voucherTax,
            'voucherType' => $request->voucherType
        ];

        $inventory = Inventory::create($dataInventory);

        if ($inventory->id) {
            foreach ($request->details as $x => $value) {
                $detail = $value['fila'];

                $dataInventoryDetails = [
                    'inventory_id' => $inventory->id,
                    'product_id' => $detail['producto'],
                    'quantity' => $detail['cantidad'],
                    'purchase_price' => $detail['precioCompra'],
                    'sale_price' => $detail['precioVenta'],
                    'subtotal' => $detail['subTotal'],
                ];

                Inventory_detail::create($dataInventoryDetails);
            }

            $response['code'] = 200;
        }

        return response()->json($response);
    }

    public function show($id)
    {
        $inventory = Inventory::find($id);

        return view('inventory.show', compact('inventory'));
    }

    public function edit($id)
    {
        $inventory = Inventory::find($id);

        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        //request()->validate(Inventory::$rules);

        $inventory->update($request->all());

        return redirect()->route('inventories.index')
            ->with('success', 'Inventory updated successfully');
    }

    public function destroy($id)
    {
        $inventory = Inventory::find($id)->delete();

        return redirect()->route('inventories.index')
            ->with('success', 'Inventory deleted successfully');
    }

    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $data = Product::select('id', 'name')->where('name', 'LIKE', "%{$search}%")->get();
            return response()->json($data);
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new InventoryExport($request->all()), 'archivo_excel.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Stock;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class MenuController
 * @package App\Http\Controllers
 */
class MenuController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            #$data = Order::select('*');
            $products = Product::leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
                ->leftJoin('product_types', 'products.product_type_id', '=', 'product_types.id')
                ->select('products.*', 'sub_categories.name as subcategory_name', 'product_types.name as product_type')
                ->whereIn('products.status_id', [1])
                ->whereIn('products.product_type_id', [1, 2, 3, 4])
                ->get();


            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('state', function ($row) {
                    $tag = ($row->product_enable == 1) ? '<span class="text-success fs-5"><i class="las la-clock"></i></span>' : '<span class="text-danger fs-5"><i class="las la-history"></i></i></span>';
                    return $tag;
                })
                ->addColumn('type', function ($row) {
                    $badge = 'badge text-bg-dark';
                    if ($row->product_type_id == 2) {
                        $badge = 'badge text-bg-info text-white';
                    } else if ($row->product_type_id == 3) {
                        $badge = 'badge text-bg-warning text-white';
                    } else if ($row->product_type_id == 4) {
                        $badge = 'badge text-bg-danger text-white';
                    }
                    $tag = "<span class='" . $badge . "'>" . $row->product_type . "</span>";
                    return $tag;
                })
                ->addColumn('image_preview', function ($row) {
                    $imagePath = Storage::url($row->cover_image);
                    $preview = '<a href="' . $imagePath . '" target="_blank" class="preview btn btn-light rounded-pill btn-sm"><i class="las la-image pe-none"></i></a>';
                    return $preview;
                })
                ->addColumn('action', function ($row) {
                    $disabled = ($row->product_enable == 1) ? 'disabled' : '';
                    $actionBtn = '<a href="javascript:void(0)" data-product="' . $row->id . '" class="enable btn btn-success btn-sm text-white rounded-pill ' . $disabled . '"><i class="las la-power-off pe-none"></i></a> 
                                  <a href="javascript:void(0)" data-product="' . $row->id . '" class="view  btn btn-light rounded-pill btn-sm"><i class="las la-pen pe-none"></i></a> 
                                  <a href="javascript:void(0)" data-product="' . $row->id . '" class="destroy btn  btn btn-light rounded-pill btn-sm"><i class="lar la-trash-alt pe-none"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'image_preview', 'type', 'state'])
                ->make(true);
        }


        $subcategories = SubCategory::where('status_id', 1)
            ->where('name', 'LIKE', '%MENU%')
            ->get();
        $productTypes = ProductType::where('status_id', 1)
            ->whereIn('id', [1,2,3])
            ->get();

        $data = [
            'subcategories' => $subcategories,
            'productTypes' => $productTypes
        ];

        return view('menu.index', $data);
    }

    public function store(Request $request)
    {
        //request()->validate(Product::$rules);

        $response = ['code' => 400, 'status' => 'error'];

        # Store in Storage Folder
        $imagen = $request->file('cover');

        $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
        $ruta = Storage::putFileAs('public/images/products', $imagen, $coverName);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => str_replace('public', '', $ruta),
            'product_type_id' => $request->type,
            'product_enable' => 0,
        ];

        if ($request->type == '4') {
            $data['product_enable'] = 1;
            $data['control_stock'] = 0;
        }

        $product = Product::create($data);

        if ($product) {

            $productCode = 'MLC' . str_pad($product->id, 4, '0', STR_PAD_LEFT);

            $model = Product::find($product->id);
            $model->code = $productCode;
            $model->save();

            // Creamos el stock del producto
            Stock::create(['product_id' => $product->id, 'quantity' => 0]);

            $response['code'] = 200;
            $response['status'] = 'success';
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $product = Product::find($request->id);

        if ($product) {
            $response = [
                'code' => 200,
                'status' => 'success',
                'data' => $product
            ];
        }

        return response()->json($response);
    }

    public function update(Request $request)
    {
        //request()->validate(Product::$rules);

        $response = ['code' => 400, 'status' => 'error'];

        $product = Product::find($request->id);

        # Store in Storage Folder
        $imagen = $request->file('cover');
        if ($imagen) {
            $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
            $ruta = Storage::putFileAs('public/images/categories', $imagen, $coverName);

            $data['cover_image'] = $ruta;
        }

        $product->sub_category_id = $request->category;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->buy_price = $request->buy_price;
        $product->sales_price = $request->sales_price;

        $result = $product->save();

        if ($result) {
            $response['code'] = 200;
            $response['status'] = 'success';
        } else {
            $errors = $product->getErrors();
            $response['errors'] = $errors->all();
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        $product->status_id = 0;
        $product->save();

        $response = ['code' => 200, 'status' => 'success'];
        return response()->json($response);
    }

    public function listCartProducts(Request $request)
    {
        # Request data
        $datos = json_decode($request->input('datos'));

        # ID products
        $productList = [];

        foreach ($datos as $dato) {
            $productList[] = $dato->productId;
        }

        $products = Product::whereIn('id', $productList)->get();

        $resume = [
            'igv' => 0,
            'subTotal' => 0,
            'total' => 0,
        ];
        foreach ($products as $product) {
            $resume['subTotal'] += doubleval($product->sales_price);
        }
        $resume['igv'] = $resume['subTotal'] * 0.18;
        $resume['subTotal'] = $resume['subTotal'] - $resume['igv'];
        $resume['total'] = $resume['subTotal'] + $resume['igv'];

        # Response data
        $data = [
            'status' => 'success',
            'code' => 200,
            'products' => $products,
            'resume' => $resume
        ];

        return response()->json($data);
    }

    public function enable(Request $request)
    {
        $product = Product::find($request->id);

        //Validamos si existe stock
        $stock = Stock::where('product_id', $request->id);
        if ($stock->exists()) {
            $stock = $stock->first();
            $stock->quantity = $request->quantity;
            $stock->save();
        } else {
            Stock::create(['product_id' => $request->id, 'quantity' => $request->quantity]);
        }

        $product->product_enable = 1;
        $product->save();

        $response = ['code' => 200, 'status' => 'success'];
        return response()->json($response);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProductsExport($request->all()), 'archivo_excel.xlsx');
    }
}

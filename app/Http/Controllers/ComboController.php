<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use App\Models\ComboProduct;
use App\Models\Product;
use App\Models\SubCategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ComboController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::select('products.*')
                ->where('products.status_id', '=', '1')
                ->where('products.product_type_id', '=', '4')
                ->get();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-light rounded-pill btn-sm btn-show"><i class="las la-pen pe-none"></i></a> 
                                          <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-light rounded-pill btn-sm btn-deleted"><i class="lar la-trash-alt pe-none"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
            #$data = Order::select('*');
            /*$combos = Combo::select('*')
                ->where('combos.status_id', '=', '1')
                ->get();

            return DataTables::of($combos)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-light rounded-pill btn-sm btn-show"><i class="las la-pen pe-none"></i></a> 
                                          <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-light rounded-pill btn-sm btn-deleted"><i class="lar la-trash-alt pe-none"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);*/
        }

        return view('admin.combo.index');
    }

    public function store(Request $request)
    {
        $response = ['code' => 400, 'status' => 'error'];

        $products = json_decode($request->products);

        # Store in Storage Folder
        $imagen = $request->file('cover_image');

        $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
        $ruta = Storage::putFileAs('public/images/combos', $imagen, $coverName);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'sales_price' => $request->sales_price,
            'cover_image' => str_replace('public', '', $ruta),
            'product_type_id' => 4 // product type 4 for combos
        ];

        $combo = Product::create($data);

        if ($combo) {

            # Actualizamos los products asociados a este combo
            if (count($products) > 0) {
                #ComboProduct::where('combo_id', $combo->id)->delete();
                foreach ($products as $product) {
                    ComboProduct::create(['combo_id' => $combo->id, 'product_id' => $product]);
                }
            }

            $response['code'] = 200;
            $response['status'] = 'success';
        }

        return response()->json($response);
    }

    public function update(Request $request)
    {
        //request()->validate(Category::$rules);

        $response = ['code' => 400, 'status' => 'error'];

        $combo = Combo::find($request->id);
        $products = json_decode($request->products);



        $coverName = "";
        if ($request->hasFile('cover_image')) {
            $imagen = $request->file('cover_image');
            $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
            $ruta = Storage::putFileAs('public/images/combos', $imagen, $coverName);
            //$data['cover_image'] = $ruta;
            $combo->cover_image = str_replace("public/", "", $ruta);
        }

        # Store in Storage Folder
        $combo->name = $request->name;
        $combo->description = $request->description;

        $result = $combo->save();

        if ($result) {

            # Actualizamos los products asociados a este combo
            if (count($products) > 0) {
                ComboProduct::where('combo_id', $request->id)->delete();
                foreach ($products as $product) {
                    ComboProduct::create(['combo_id' => $request->id, 'product_id' => $product]);
                }
            } else {
                ComboProduct::where('combo_id', $request->id)->delete();
            }

            $response['code'] = 200;
            $response['status'] = 'success';
        } else {
            $errors = $combo->getErrors();
            $response['errors'] = $errors->all();
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        $category = Combo::find($id);

        $category->status_id = 0;
        $category->save();

        $response = ['code' => 200, 'status' => 'success'];
        return response()->json($response);
    }

    public function show(Request $request)
    {
        $combo = Product::find($request->id);

        if ($combo) {

            $products = ComboProduct::select('combos_products.combo_id as id', 'products.name as text', 'products.description', 'products.sales_price', 'products.cover_image')
                ->leftJoin('products', 'products.id', '=', 'combos_products.product_id')
                ->where('combo_id', '=', $request->id)
                ->get();

            $combo['products'] = $products;

            $response = [
                'code' => 200,
                'status' => 'success',
                'data' => $combo
            ];
        }

        return response()->json($response);
    }

    public function searchProducts(Request $request)
    {

        $searchValue = trim($request->search);
        $products = [];

        if (strlen($searchValue) > 0) {
            $products = SubCategoryProduct::select('products.id', 'products.name', 'products.sales_price', 'products.description', 'products.cover_image')
                ->leftJoin('sub_categories_products', 'sub_categories_products.product_id', '=', 'products.id')
                ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
                ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
                ->whereIn('products.product_type_id', [0, 1])
                ->where('products.status_id', '=', 1)
                ->where('products.product_enable', '=', 1)
                ->whereNotIn('sub_categories.category_id', [16, 17])
                ->where('products.name', 'LIKE', '%' . $searchValue . '%')
                ->get();
        }

        $data = [
            'products' => $products
        ];

        return response()->json($data);
    }
}

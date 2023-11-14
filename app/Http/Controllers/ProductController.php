<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SubCategory;
use App\Models\SubCategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            #$data = Order::select('*');
            $products = Product::select('products.*')
                //->leftJoin('sub_categories_products', 'sub_categories_products.product_id', '=', 'products.id')
                //->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories_products.sub_category_id')
                ->where('products.status_id', '=', '1')
                ->where('products.product_type_id', '=', '0')
                ->get();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image_preview', function ($row) {
                    $imagePath = Storage::url($row->cover_image);
                    $preview = '<a href="' . $imagePath . '" target="_blank" class="preview  btn btn-light rounded-pill btn-sm"><i class="las la-image pe-none"></i></a>';
                    return $preview;
                })
                ->addColumn('categories', function ($row) {
                    $categories = SubCategoryProduct::select('sub_categories.name')
                        ->leftJoin('sub_categories', 'sub_categories.id', '=', 'sub_categories_products.sub_category_id')
                        ->where('sub_categories_products.product_id', '=', $row->id)
                        ->get();
                    $categoriesNames = '';
                    foreach ($categories as $category) {
                        $categoriesNames .= '<span class="badge bg-info">' . $category->name . '</span>';
                    }

                    return $categoriesNames;
                })
                ->addColumn('action', function ($row) {
                    $stock = Stock::where('product_id', $row->id)->first();
                    $iconControlStock = '<i class="las la-bell-slash pe-none"></i>';
                    $controlStockText = 'Activar control Stock';
                    $controlStock = "enable";
                    if ($stock->control_stock == '1') {
                        $iconControlStock = '<i class="las la-bell pe-none"></i>';
                        $controlStockText = 'Desactivar control Stock';
                        $controlStock = "disabled";
                    }
                    $actionBtn = '<a href="javascript:void(0)" data-action="' . $controlStock . '" data-product="' . $row->id . '" title="' . $controlStockText . '" class="control btn btn-info btn-sm text-white rounded-pill">' . $iconControlStock . '</a> 
                                  <a href="javascript:void(0)" data-product="' . $row->id . '" title="Editar" class="view btn btn-light rounded-pill btn-sm"><i class="las la-pen pe-none"></i></a> 
                                  <a href="javascript:void(0)" data-product="' . $row->id . '" title="Eliminar" class="destroy  btn btn-light rounded-pill btn-sm"><i class="lar la-trash-alt pe-none"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'image_preview', 'categories'])
                ->make(true);
        }

        $conditions = [
            ['status_id', '=', '1'],
        ];
        $categories = SubCategory::where($conditions)->get();

        $data = [
            'categories' => $categories
        ];

        return view('product.index', $data);
    }

    public function create()
    {
        $product = new Product();
        $subcategories = SubCategory::where('status_id', 1)->get();

        $data = [
            'product' => $product,
            'subcategories' => $subcategories
        ];

        return view('product.create', $data);
    }

    public function store(Request $request)
    {
        //request()->validate(Product::$rules);

        $response = ['code' => 400, 'status' => 'error'];

        # Store in Storage Folder
        $imagen = $request->file('cover');

        $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
        $ruta = Storage::putFileAs('public/images/products', $imagen, $coverName);

        $categories = json_decode($request->categories);

        $data = [
            //'sub_category_id' => $request->category,
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => str_replace('public', '', $ruta),
            'buy_price' => $request->buy_price,
            'sales_price' => $request->sales_price
        ];

        $product = Product::create($data);

        if ($product) {
            $productCode = 'PLC' . str_pad($product->id, 4, '0', STR_PAD_LEFT);

            $model = Product::find($product->id);
            $model->code = $productCode;
            $model->save();
            
            # Agregamos las categorias a la que esta asociada este producto
            if (count($categories) > 0) {
                foreach ($categories as $category) {
                    SubCategoryProduct::create(['sub_category_id' => $category, 'product_id' => $product->id]);
                }
            }

            $response['code'] = 200;
            $response['status'] = 'success';
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $product = Product::find($request->id);

        $categories = SubCategoryProduct::select('sub_categories_products.sub_category_id')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'sub_categories_products.sub_category_id')
            ->where('sub_categories_products.product_id', '=', $request->id)
            ->get()
            ->pluck('sub_category_id')
            ->toArray();

        if ($product) {

            $product['categories'] = $categories;

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
        
        $categories = json_decode($request->categories);

        $coverName = "";
        if ($request->hasFile('cover')) {
            $imagen = $request->file('cover');
            $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
            $ruta = Storage::putFileAs('public/images/products', $imagen, $coverName);
            //$data['cover_image'] = $ruta;
            $product->cover_image = str_replace("public/", "", $ruta);
        }

        //$product->sub_category_id = $request->category;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->buy_price = $request->buy_price;
        $product->sales_price = $request->sales_price;

        $result = $product->save();

        if ($result) {

            # Actualizamos las categorias a la que esta asociada este producto            
            SubCategoryProduct::where('product_id', $product->id)->delete();
            if (count($categories) > 0) {                
                foreach ($categories as $category) {
                    SubCategoryProduct::create(['sub_category_id' => $category, 'product_id' => $product->id]);
                }
            }


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

    public function getStock(Request $request)
    {
        $stock = Stock::where('product_id', $request->id);
        if ($stock->exists()) {
            return $stock->first()->quantity;
        } else {
            return 0;
        }
    }

    public function setControlStock(Request $request)
    {
        $response = ['code' => 400, 'status' => 'error'];
        $product = Stock::where('product_id', '=', $request->id)->first();
        $accion = $request->action;

        if (!$product->exists()) {
            return response()->json($response);
        }
        
        $product->control_stock = ($accion == 'enable') ? '1' : '0';
        
        $result = $product->save();

        if ($result) {
            $response['code'] = 200;
            $response['status'] = 'success';
        }

        return response()->json($response);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProductsExport($request->all()), 'archivo_excel.xlsx');
    }
}

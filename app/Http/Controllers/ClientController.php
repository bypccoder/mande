<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\VoucherEmail;
use App\Models\Category;
use App\Models\DocumentType;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\VoucherType;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Helpers\RulesHelper;
use App\Models\ProductType;
use App\Models\SubCategoryProduct;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Validator\Constraints\Length;
use Yajra\DataTables\DataTables;

class ClientController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::where('status_id', 1)->get();

        $data = ['documentTypes' => $documentTypes];
        return view('agente/index', $data);
    }
    public function home(Request $request)
    {

        if (!Session::has('person')) {
            return redirect()->route('client.index');
        }

        $categories = Category::where('status_id', 1)->get();
        $paymentMethods = PaymentMethod::select('id', 'payment_method as name')->get();
        $voucherType = VoucherType::select('id', 'name')->where('status_id', 1)->whereIn('id',[1,2,3])->get();

        //$ruleCategories = RulesHelper::filterCategories($categories, Session::get('person'));
        $rulePaymentMethods = RulesHelper::filterPaymentMethods($paymentMethods, Session::get('person'));

        $data = [
            //'categories' => $categories,
            'paymentMethods' => $rulePaymentMethods,
            'voucherTypes' => $voucherType
        ];

        return view('agente/home', $data);
    }

    public function categories()
    {
        $categories = Category::where('status_id', 1)->get();

        #$arrCategories = (isset($_GET['productIds'])) ? explode(',', $_GET['productIds']) : [];
        $currentCategory = (isset($_GET['currentCategory'])) ?  explode(',', $_GET['currentCategory']) : [];

        $ruleCategories = RulesHelper::filterCategories($categories, Session::get('person'), $currentCategory);

        $data = [
            'categories' => $ruleCategories
        ];

        return view('agente/partials/categorias', $data);
    }

    public function subcategories($category_id, Request $request)
    {
        $subCategories = SubCategory::where('category_id', $category_id)->where('status_id', '!=', 0)->get();

        $currentCategory = (isset($_GET['currentCategory'])) ?  explode(',', $_GET['currentCategory']) : [];

        $ruleCategories = RulesHelper::filterSubCategories($subCategories, Session::get('person'), $currentCategory);

        $data = ['subcategories' => $ruleCategories];

        return view('agente/partials/subcategorias', $data);
    }
    public function products($sub_category_id, Request $request)
    {

        $generalPrice = null;
        $discount = [];
        $shopping_cart = session()->get('shopping_cart');

        /**
         * Validar Bebidas e infusiones
         */
        if (session('person')->employee_id) {
            if (session('person')->type_employee == 2) { //VIP
                $discount = [16, 17]; //bebidas,infusiones
            } else if (session('person')->type_employee == 1) { //REGULAR
                $discount = [16, 17];
                $discount = RulesHelper::validateOneOrderInCurrentDay(session('person')->id, $discount);
            }
        }

        /**
         * Validar precio menu
         */
        $products = null;
        $modelSubCategory = SubCategory::find($sub_category_id);
        $nameCategory = $modelSubCategory->name;

        if (str_contains(strtolower($nameCategory), 'menu')) {
            #$generalPrice = RulesHelper::validatePricingMenu($nameCategory, session('person'));

            // Obtenemos los precios por cada tipo de producto del menu
            $productTypePrice = ProductType::whereIn('id', [1, 2])->get()->toArray();
            $generalPrice = [];
            foreach ($productTypePrice as $item) {
                $generalPrice[$item['id']] = doubleval($item['price']);
            }

            $products = Product::select('products.*', 'sub_categories.category_id', 'product_types.name as product_type', 'sub_categories.id as sub_category_id', 'stocks.quantity as stock', 'stocks.control_stock as stock_enable')
                ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
                ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
                ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
                ->whereIn('products.product_type_id', [1, 2, 3])
                ->where('products.status_id', '=', 1)
                ->where('products.product_enable', '=', 1)
                ->groupBy('products.id')
                ->get();

        } else {
            $products = SubCategoryProduct::select('products.*', 'sub_categories.category_id', 'product_types.name as product_type', 'sub_categories.id as sub_category_id', 'sub_categories.is_iced', 'stocks.quantity as stock', 'stocks.control_stock as stock_enable')
                ->leftJoin('products', 'products.id', '=', 'sub_categories_products.product_id')
                ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
                ->leftJoin('sub_categories', 'sub_categories.id', '=', 'sub_categories_products.sub_category_id')
                ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
                ->where('sub_categories_products.sub_category_id', $sub_category_id)
                ->whereIn('products.product_type_id', [0, 1])
                ->where('products.status_id', '=', 1)
                ->where('products.product_enable', '=', 1)
                ->get();
        }

        $cartProducts = ($request->productIds) ? explode(',', $request->productIds) : [];

        if (!is_null($shopping_cart)) {
            $products = RulesHelper::validateProductInCart($products, $shopping_cart);
        }

        $data = [
            'products' => $products,
            'cartProducts' => $cartProducts,
            'discount' => $discount,
            'generalPrice' => $generalPrice,
            'sub_category_id' => $sub_category_id,
            'sub_category' => $modelSubCategory,
            'shopping_cart' => $shopping_cart,
        ];


        return view('agente/partials/listaProductos', $data);
    }

    public function searchProducts($search, Request $request)
    {

        $searchValue = trim($search);
        $products = [];
        $generalPrice = null;
        $discount = [];
        $shopping_cart = session()->get('shopping_cart');



        if (strlen($searchValue) > 0) {
            $products = SubCategoryProduct::select('products.*', 'sub_categories.category_id', 'product_types.name as product_type', 'sub_categories.id as sub_category_id', 'sub_categories.is_iced', 'stocks.quantity as stock', 'stocks.control_stock as stock_enable')
                ->leftJoin('products', 'products.id', '=', 'sub_categories_products.product_id')
                ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
                ->leftJoin('sub_categories', 'sub_categories.id', '=', 'sub_categories_products.sub_category_id')
                ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
                ->whereIn('products.product_type_id', [0, 1])
                ->where('products.status_id', '=', 1)
                ->where('products.product_enable', '=', 1)
                ->where('products.name', 'LIKE', DB::raw("'%$search%'"))
                ->groupBy('products.id') // Agrega la cláusula GROUP BY          
                ->get();

            /*
            $products = Product::select(
                'products.*',
                'sub_categories.category_id',
                'product_types.name as product_type',
                'sub_categories.id as sub_category_id',
                'sub_categories.is_iced',
                'stocks.quantity as stock',
                'sub_categories.iced_price',
                'stocks.control_stock as stock_enable'
            )
                #->leftJoin('products', 'products.id', '=', 'sub_categories_products.product_id')
                ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
                ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')                
                ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
                ->whereIn('products.product_type_id', [0, 1])
                ->where('products.status_id', '=', 1)
                ->where('products.product_enable', '=', 1)
                #->whereNotIn('sub_categories.category_id', [16, 17])
                ->where('products.name', 'LIKE', '%' . $search . '%')                
                ->get();*/
        }

        //$cartProducts = ($request->productIds) ? explode(',', $request->productIds) : [];

        if (!is_null($shopping_cart)) {
            $products = RulesHelper::validateProductInCart($products, $shopping_cart);
        }

        //$cartProducts = ($request->productIds) ? explode(',', $request->productIds) : [];
        $cartProducts = [];

        $data = [
            'products' => $products,
            'shopping_cart' => $shopping_cart,
            'discount' => $discount,
            'generalPrice' => $generalPrice,
            'cartProducts' => $cartProducts,
        ];

        return view('agente/partials/listaProductosBuscador', $data);
    }

    public function comboProducts(Request $request)
    {

        $products = [];
        $generalPrice = null;
        $discount = [];
        $shopping_cart = session()->get('shopping_cart');

        $products = Product::select(
            'products.*',
            'sub_categories.category_id',
            'product_types.name as product_type',
            'sub_categories.id as sub_category_id',
            'sub_categories.is_iced',
            'stocks.quantity as stock',
            'sub_categories.iced_price'
        )
            ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
            ->leftJoin('sub_categories', 'products.sub_category_id', '=', 'sub_categories.id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->whereIn('products.product_type_id', [4])
            ->where('products.status_id', '=', 1)
            ->get();

        //$cartProducts = ($request->productIds) ? explode(',', $request->productIds) : [];

        if (!is_null($shopping_cart)) {
            $products = RulesHelper::validateProductInCart($products, $shopping_cart);
        }

        //$cartProducts = ($request->productIds) ? explode(',', $request->productIds) : [];
        $cartProducts = [];

        $data = [
            'products' => $products,
            'shopping_cart' => $shopping_cart,
            'discount' => $discount,
            'generalPrice' => $generalPrice,
            'cartProducts' => $cartProducts,
        ];

        return view('agente/partials/listaProductosBuscador', $data);
    }

    public function resume()
    {

        return view('agente/partials/resumen');
    }

    public function listProductsComplementaryMenu()
    {
        $products = Product::select('products.*', 'product_types.name as product_type')
            ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
            ->whereIn('products.product_type_id', [2, 3])
            ->where('products.status_id', '=', 1)
            ->get();

        $data = ['products' => $products];

        return response()->json($data);
    }

    public function cancelCart(Request $request)
    {
        $request->session()->forget('shopping_cart');
        $request->session()->forget('person');
        return redirect()->route('client.kiosk');
    }

    public function updateCart(Request $request)
    {
        $strCart = $request->categories;
        $cartInfo = ($request->cart !== 'null') ? $request->cart : null;

        $shoppingCart = [];

        if (!is_null($cartInfo)) {
            foreach (json_decode($cartInfo) as $jsonString) {
                $phpArray = json_decode($jsonString, true);
                if ($phpArray !== null) {
                    $shoppingCart[] = $phpArray;
                }
            }
        }

        /*session()->forget('subCategoriesInCart');
        die();*/
        session()->put('shopping_cart', $shoppingCart);

        session()->put('SubCategoriesInCart', $strCart);
        $response = ['message' => 'Se actualizo la informacion en el carrito del servidor.'];
        return response()->json($response);
    }

    public function historyOrders(Request $request)
    {
        if ($request->expectsJson()) {
            #$data = Order::select('*');
            $document = $request->document;
            $data = Order::select('orders.id', 'orders.internal_code', 'orders.amount', 'orders.date_order', 'orders.id', 'orders.id', 'vouchers.path_pdf', 'vouchers.data_path_note_credit', 'charge_code_note_credit', 'payment_methods.payment_method as payment_method', 'voucher_types.name as voucher_type', 'order_types.name as order_type', DB::raw('CONCAT(people.name," ",people.lastname_1," ",people.lastname_2) as person'))
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
                ->leftJoin('voucher_types', 'voucher_types.id', '=', 'orders.form_method_id')
                ->leftJoin('order_types', 'order_types.id', '=', 'orders.order_type_id')
                ->leftJoin('people', 'people.id', '=', 'orders.person_id')
                ->leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
                ->where('orders.status_id', '=', 1)
                ->where('people.document', '=', $document)
                ->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('monto', function ($row) {
                    $dato = '<span class="text-success">' . number_format($row->amount, 2) . ' S/.</span>';
                    return $dato;
                })
                ->addColumn('details', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="viewDetail mx-2 btn btn-light rounded-pill btn-sm" data-order="' . $row->id . '" data-coreui-toggle="modal" data-coreui-target="#modal-details"><i class="las la-eye"></i></a>';
                    return '<div class="d-flex flex-row">' . $actionBtn . '</div>';
                })
                ->rawColumns(['details', 'monto'])
                ->make(true);
        }
        return view('client/index');
    }
}

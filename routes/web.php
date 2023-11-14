<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/admin', function () {
    return view('admin/dashboard');
});*/
/*
Route::get('/', function () {
    return view('agente/index');
});*/

#----------------------------------------------------------------
#   Rutas para el POS
#----------------------------------------------------------------

Route::get('/', [App\Http\Controllers\ClientController::class, 'index'])->name('client.kiosk');
Route::get('/client', [App\Http\Controllers\ClientController::class, 'index'])->name('client.index');
Route::get('/client/cancel', [App\Http\Controllers\ClientController::class, 'cancelCart'])->name('kiosk.cancel');
Route::get('/client/home', [App\Http\Controllers\ClientController::class, 'home'])->name('home');
Route::get('/client/home/categories', [App\Http\Controllers\ClientController::class, 'categories'])->name('categories');
Route::get('/client/home/subcategories/{id}', [App\Http\Controllers\ClientController::class, 'subcategories'])->name('subcategories');
Route::get('/client/home/products/{id}', [App\Http\Controllers\ClientController::class, 'products'])->name('products');
Route::post('/client/home/products/complementary', [App\Http\Controllers\ClientController::class, 'listProductsComplementaryMenu'])->name('complementarymenu');
Route::post('/client/home/listcart', [App\Http\Controllers\ProductController::class, 'listCartProducts'])->name('listCartProducts');
Route::post('/client/validate', [App\Http\Controllers\ValidateClientController::class, 'validateDocument'])->name('client.validate');
Route::get('/client/validateCart', [App\Http\Controllers\ValidateClientController::class, 'validateCart'])->name('client.validateCart');
Route::post('/client/updateCart', [App\Http\Controllers\ClientController::class, 'updateCart'])->name('client.updateCart');
Route::get('/client/history', [App\Http\Controllers\ClientController::class, 'historyOrders'])->name('client.historyOrders');
Route::post('/client/history', [App\Http\Controllers\ClientController::class, 'historyOrders'])->name('client.historyOrders');

Route::get('/client/cancelcancel', [App\Http\Controllers\ValidateClientController::class, 'remove'])->name('client.remove');

Route::post('/client/person/store', [App\Http\Controllers\PersonController::class, 'store'])->name('client.person.store');
Route::post('/client/order/store', [App\Http\Controllers\OrderController::class, 'store'])->name('client.order.store');

Route::get('/sunat', [App\Http\Controllers\OrderController::class, 'index']);
Route::get('/anulacion', [App\Http\Controllers\OrderController::class, 'anulacion_api_sunat_boleta']);
Route::get('/print', [App\Http\Controllers\OrderController::class, 'print']);

Route::get('/email', [App\Http\Controllers\OrderController::class, 'sendEmail']);

Route::post('/client/imprimirBoletaOFactura', [App\Http\Controllers\OrderController::class, 'print_boleta_o_factura']);
Route::post('/client/imprimirNotaVenta', [App\Http\Controllers\OrderController::class, 'print_nota_venta']);

Route::get('/client/imprimirBoletaOFactura', [App\Http\Controllers\OrderController::class, 'print_boleta_o_factura']);
Route::get('/client/imprimirNotaVenta', [App\Http\Controllers\OrderController::class, 'print_nota_venta']);

Route::get('/client/home/productSearch/{search}', [App\Http\Controllers\ClientController::class, 'searchProducts'])->name('productSearch');
Route::get('/client/home/combos', [App\Http\Controllers\ClientController::class, 'comboProducts'])->name('pdv.home.combos');

#----------------------------------------------------------------
#   Rutas para el ADMINISTRADOR
#----------------------------------------------------------------

Auth::routes();

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

/*Route::get('/admin', function () {
    return view('admin/home/dashboard');
})->name('admin.index')->middleware('auth');*/

#Route::resource('/admin/categories', CategoryController::class)->middleware('auth');
#Route::resource('/admin/sub-categories', SubCategoryController::class)->middleware('auth');
//Route::resource('/admin/products', ProductController::class)->middleware('auth');
#Route::resource('/admin/inventories', InventoryController::class)->middleware('auth');
Route::middleware('auth')->group(function () {


    Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.index');

    Route::get('/admin/close-sales', [App\Http\Controllers\CloseSalesController::class, 'index'])->name('close_sales.index');
    Route::post('/admin/close-sales', [App\Http\Controllers\CloseSalesController::class, 'index'])->name('close_sales.index.ajax');

    Route::get('/admin/inventories', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventories.index');
    Route::post('/admin/inventories/store', [App\Http\Controllers\InventoryController::class, 'store']);
    Route::post('/admin/inventories/products', [App\Http\Controllers\InventoryController::class, 'getProducts']);
    Route::get('/admin/inventories/report', [App\Http\Controllers\InventoryController::class, 'export'])->name('inventories.export');

    Route::get('/admin/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::post('/admin/products/show', [App\Http\Controllers\ProductController::class, 'show']);
    Route::post('/admin/products/store', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('/admin/products/update', [App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('/admin/products/destroy/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/admin/products/stock', [App\Http\Controllers\ProductController::class, 'getStock'])->name('products.stock');
    Route::post('/admin/products/control-stock', [App\Http\Controllers\ProductController::class, 'setControlStock'])->name('products.controlstock');
    Route::get('/admin/products/report', [App\Http\Controllers\ProductController::class, 'export'])->name('products.export');


    Route::get('/admin/menus', [App\Http\Controllers\MenuController::class, 'index'])->name('menus.index');
    Route::post('/admin/menus/show', [App\Http\Controllers\MenuController::class, 'show']);
    Route::post('/admin/menus/store', [App\Http\Controllers\MenuController::class, 'store']);
    Route::post('/admin/menus/update', [App\Http\Controllers\MenuController::class, 'update']);
    Route::delete('/admin/menus/destroy/{id}', [App\Http\Controllers\MenuController::class, 'destroy'])->name('menus.destroy');
    Route::post('/admin/menus/enable', [App\Http\Controllers\MenuController::class, 'enable']);
    Route::get('/admin/menus/report', [App\Http\Controllers\MenuController::class, 'export'])->name('menus.export');

    Route::get('/admin/subcategories', [App\Http\Controllers\SubCategoryController::class, 'index'])->name('subcategories.index');
    Route::post('/admin/subcategories/show', [App\Http\Controllers\SubCategoryController::class, 'show']);
    Route::post('/admin/subcategories/store', [App\Http\Controllers\SubCategoryController::class, 'store']);
    Route::post('/admin/subcategories/update', [App\Http\Controllers\SubCategoryController::class, 'update']);
    Route::delete('/admin/subcategories/destroy/{id}', [App\Http\Controllers\SubCategoryController::class, 'destroy'])->name('subcategories.destroy');
    Route::get('/admin/subcategories/report', [App\Http\Controllers\SubCategoryController::class, 'export'])->name('subcategories.export');
    Route::post('/admin/subcategories/iced', [App\Http\Controllers\SubCategoryController::class, 'enableIced'])->name('subcategories.enableIced');

    Route::get('/admin/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::post('/admin/categories/show', [App\Http\Controllers\CategoryController::class, 'show']);
    Route::post('/admin/categories/store', [App\Http\Controllers\CategoryController::class, 'store']);
    Route::post('/admin/categories/update', [App\Http\Controllers\CategoryController::class, 'update']);
    Route::delete('/admin/categories/destroy/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/admin/categories/report', [App\Http\Controllers\CategoryController::class, 'export'])->name('categories.export');

    Route::get('/admin/reports', [App\Http\Controllers\CategoryController::class, 'index'])->name('reports.index');

    Route::get('/admin/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::post('/admin/orders/detail', [App\Http\Controllers\OrderController::class, 'getOrderDetails'])->name('orders.getOrderDetails');
    Route::delete('/admin/orders/destroy/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/admin/orders/report', [App\Http\Controllers\ExcelController::class, 'export'])->name('excel.export');
    Route::post('/admin/orders/chargecode', [App\Http\Controllers\OrderController::class, 'getChargeCode'])->name('orders.chargecode');


    Route::get('/admin/orders_income', [App\Http\Controllers\ElectronicBillingController::class, 'index'])->name('orders_income.index');
    //Route::post('/admin/orders/detail', [App\Http\Controllers\OrderController::class, 'getOrderDetails'])->name('orders.getOrderDetails');
    //Route::delete('/admin/orders/destroy/{id}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
    //Route::get('/admin/orders/report', [App\Http\Controllers\ExcelController::class, 'export'])->name('excel.export');
    //Route::post('/admin/orders/chargecode', [App\Http\Controllers\OrderController::class, 'getChargeCode'])->name('orders.chargecode');

    Route::post('/admin/sunat/anular', [App\Http\Controllers\OrderController::class, 'anulacion_orden'])->name('sunat.anular');
    Route::post('/admin/sunat/nota-credito', [App\Http\Controllers\OrderController::class, 'nota_credito'])->name('sunat.nota');
    Route::get('/admin/sunat/comprobante/{id}', [App\Http\Controllers\OrderController::class, 'comprobante'])->name('sunat.comprobante');


    Route::post('/admin/cobranzas/nota-credito', [App\Http\Controllers\ElectronicBillingController::class, 'proccess_notacredito'])->name('cobranzas.nota');

    Route::get('/admin/import-employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('import_employees.index');
    Route::post('/admin/upload-employees', [App\Http\Controllers\EmployeeController::class, 'import'])->name('upload_employees.index');

    Route::get('/admin/descargar-xml-cdr', [App\Http\Controllers\ArchivoXmlCdrController::class, 'index'])->name('descargar_xml_cdr.index');
    Route::post('/admin/descargar-xml-cdr', [App\Http\Controllers\ArchivoXmlCdrController::class, 'descargarXmlCdr'])->name('descargar_xml_cdr.post');

    Route::get('/admin/generate-boleta', [App\Http\Controllers\ElectronicBillingController::class, 'index_boleta'])->name('generate_boleta.index');
    Route::post('/admin/generate-boleta', [App\Http\Controllers\ElectronicBillingController::class, 'proccess_boleta'])->name('generate_boleta.post');

    Route::get('/admin/generate-factura', [App\Http\Controllers\ElectronicBillingController::class, 'index_factura'])->name('generate_factura.index');
    Route::post('/admin/generate-factura', [App\Http\Controllers\ElectronicBillingController::class, 'proccess_factura'])->name('generate_factura.post');
    // Combos
    Route::get('/admin/combos', [App\Http\Controllers\ComboController::class, 'index'])->name('combos.index');
    Route::get('/admin/combos/searchProducts', [App\Http\Controllers\ComboController::class, 'searchProducts'])->name('combos.searchProducts');
    Route::post('/admin/combos/store', [App\Http\Controllers\ComboController::class, 'store'])->name('combos.store');
    Route::post('/admin/combos/update', [App\Http\Controllers\ComboController::class, 'update'])->name('combos.update');
    Route::post('/admin/combos/show', [App\Http\Controllers\ComboController::class, 'show'])->name('combos.show');
    Route::delete('/admin/combos/destroy/{id}', [App\Http\Controllers\ComboController::class, 'destroy'])->name('combos.destroy');
    // Transactions
    Route::get('/admin/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/admin/transactions/report', [App\Http\Controllers\TransactionController::class, 'export'])->name('transaction.export');
    // Cobranza
    Route::get('/admin/cobranza/index', [App\Http\Controllers\CobranzaController::class, 'index'])->name('cobranza.index');
    Route::get('/admin/cobranza/report', [App\Http\Controllers\CobranzaController::class, 'generateReport'])->name('cobranza.reports');    

    Route::get('/admin/generate-notacredito', [App\Http\Controllers\ElectronicBillingController::class, 'index_notacredito'])->name('generate_notacredito.index');
    Route::post('/admin/generate-notacredito', [App\Http\Controllers\ElectronicBillingController::class, 'proccess_notacredito'])->name('generate_notacredito.post');


});


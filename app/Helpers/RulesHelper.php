<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Stock;
use Carbon\Carbon;

class RulesHelper
{
    /**
     * Validación de fecha de inicio de contrato si colaboradores es mayor a 
     * 3 meses se le habilita la opción de corp 3eriza y el método de pago planilla.
     */
    public static function filterCategories($categories, $client, $currentCategories)
    {
        $clientArray = get_object_vars($client);
        $newCategories = [];
        $shoppingCart = session()->get('shopping_cart');


        $companyMap = [
            'MILLA MARTINEZ IGNACIO PABLO' => 17,
            'TERCERIZA PERU S.R.L.' => 16,
            'BPO PERÚ S.A.C.' => 16,
        ];

        // los IDs a validar para CORP MILLA
        $productIdsInCart = collect($shoppingCart)->pluck('productId')->toArray();
        $invalidCategoryIds = [6, 7];
        $validCategoryIds = [5];

        $isNoEmployee = false;

        if (array_key_exists('company_id', $clientArray) && $clientArray['company_id'] !== null) {
            $companyId = $clientArray['company_id'];
        } elseif (array_key_exists('employee_id', $clientArray) && $clientArray['employee_id'] !== null) {
            $companyId = $companyMap[$clientArray['company']] ?? null;
        } elseif (array_key_exists('clientsvip_id', $clientArray) && $clientArray['clientsvip_id'] !== null) {
            $companyId = $companyMap[$clientArray['company']] ?? null;
        } elseif (array_key_exists('no_employee_id', $clientArray) && $clientArray['no_employee_id'] !== null) {
            $companyId = null;
            $isNoEmployee = true;
        } else {
            $companyId = null;
        }

        $onlyCurrentCategories = in_array(16, $currentCategories) || in_array(17, $currentCategories);

        foreach ($categories as $category) {

            if ($isNoEmployee && !in_array($category->id, [16, 17])) {
                $newCategories[] = $category;
            }

            if (!$isNoEmployee) {
                if ($companyId === null || ($category->id != $companyId && (!$onlyCurrentCategories || ($onlyCurrentCategories && in_array($category->id, [16, 17]))) || (empty($currentCategories) && $category->id != 16 && $category->id != 17))) {
                    $newCategories[] = $category;
                }
            }
        }

        return $newCategories;


        /*$clientArray = get_object_vars($client);
        $newCategories = [];

        $onlyCurrentCategories = boolval(array_intersect([16, 17], $currentCategories)); // ID de las categorias CORPORATIVAS

        if (array_key_exists('company_id', $clientArray) && $clientArray['company_id'] !== null) {
            foreach ($categories as $category) {
                if ($category->id != 16 && $category->id != 17) {
                    $newCategories[] = $category;
                }
            }
        } else if (array_key_exists('employee_id', $clientArray) && $clientArray['employee_id'] !== null) {
            $idCompany = 0;
            if ($clientArray['company'] == 'MILLA MARTINEZ IGNACIO PABLO') {
                $idCompany = 17;
            } else if ($clientArray['company'] == 'TERCERIZA PERU S.R.L.' ||  $clientArray['company'] == 'BPO PERÚ S.A.C.') {
                $idCompany = 16;
            }

            foreach ($categories as $category) {
                if ($category->id == $idCompany) {
                    continue;
                }

                if ($onlyCurrentCategories) {
                    if ($category->id == 16 || $category->id == 17) {
                        $newCategories[] = $category;
                    }
                } else if (count($currentCategories) > 0) {
                    if ($category->id != 16 && $category->id != 17) {
                        $newCategories[] = $category;
                    }
                } else {
                    $newCategories[] = $category;
                }
            }
        } else if (array_key_exists('clientsvip_id', $clientArray) && $clientArray['clientsvip_id'] !== null) {
            $idCompany = 0;
            if ($clientArray['company'] == 'MILLA MARTINEZ IGNACIO PABLO') {
                $idCompany = 17;
            } else if ($clientArray['company'] == 'TERCERIZA PERU S.R.L.' ||  $clientArray['company'] == 'BPO PERÚ S.A.C.') {
                $idCompany = 16;
            }

            foreach ($categories as $category) {
                if ($category->id == $idCompany) {
                    continue;
                }

                if ($onlyCurrentCategories) {
                    if ($category->id == 16 || $category->id == 17) {
                        $newCategories[] = $category;
                    }
                } else if (count($currentCategories) > 0) {
                    if ($category->id != 16 && $category->id != 17) {
                        $newCategories[] = $category;
                    }
                } else {
                    $newCategories[] = $category;
                }
            }
        } else if (array_key_exists('no_employee_id', $clientArray) && $clientArray['no_employee_id'] !== null) {
            foreach ($categories as $category) {
                if ($category->id == 16 || $category->id == 17) { //CORP MILLA - CORP 3ERIZA
                    continue;
                }
                $newCategories[] = $category;
            }
        }


        return $newCategories;*/
    }

    public static function filterSubCategories($categories, $client, $currentCategories)
    {
        $clientArray = get_object_vars($client);
        $newSubCategories = [];
        $shoppingCart = (session()->get('shopping_cart')) ? session()->get('shopping_cart') : [];
        $productIdsInCart = collect($shoppingCart)->pluck('subCategory')->toArray();

        $companyMap = [
            'MILLA MARTINEZ IGNACIO PABLO' => [16],
            'TERCERIZA PERU S.R.L.' => [17],
            'BPO PERÚ S.A.C.' => [17],
        ];

        if (array_key_exists('company_id', $clientArray) && $clientArray['company_id'] !== null) {
            $companyId = $clientArray['company_id'];
        } else {
            $companyId = $companyMap[$clientArray['company']] ?? null;
        }

        $validSubcategory = [6, 7, 15];
        $invalidSubcategory = [5, 8];

        $isSubcategory5InCart = in_array(5, $productIdsInCart); //MILLA
        $isSubcategory8InCart = in_array(8, $productIdsInCart); //3ERIZA
        $isSubcategoryVIP = (isset($client->clientsvip_id)) ? true : false;

        /**
         * En caso de que el carrito tenga los menu FONDO Y ENTRADA entonces 
         * se aplicaran los filtros de validacion por coroporacion, caso contrario
         * los precios se mantendran sin beneficio
         */

        $hasMenu = false;
        if ($shoppingCart) {
            $hasFondo = false;
            $hasEntrada = false;

            foreach ($shoppingCart as $product) {
                $productType = $product['product_type'];
                if ($productType == 1) {
                    $hasFondo = true;
                } else if ($productType == 2) {
                    $hasEntrada = true;
                }
            }

            $hasMenu = ($hasFondo && $hasEntrada);
        }


        foreach ($categories as $category) {

            if ($isSubcategoryVIP) {
                if ($companyId === null || !in_array($category->id, $companyId)) {
                    $newSubCategories[] = $category;
                }
            } else {

                // Si no hay un menu completo (fondo+entrada)
                if (!$hasMenu) {
                    if ($companyId === null || !in_array($category->id, $companyId)) {
                        $newSubCategories[] = $category;
                    }
                } else if (!$isSubcategory5InCart && !$isSubcategory8InCart) {
                    if ($companyId === null || !in_array($category->id, $companyId)) {
                        if (count($productIdsInCart) == 0) {
                            $newSubCategories[] = $category;
                        } else if (!in_array($category->id, $invalidSubcategory)) {
                            $currentSubcategoryInter = array_intersect($productIdsInCart, $validSubcategory);
                            $currentSubctegory = array_values($currentSubcategoryInter);

                            if (!in_array($category->id, $currentSubctegory)) {
                                $newSubCategories[] = $category;
                            }
                        }
                    }
                } else {
                    if (!in_array($category->id, $validSubcategory)) {

                        $currentSubcategoryInter = array_intersect($productIdsInCart, $invalidSubcategory);
                        $currentSubctegory = array_values($currentSubcategoryInter);

                        if (!in_array($category->id, $currentSubctegory)) {
                            if ($companyId === null || !in_array($category->id, $companyId)) {
                                $newSubCategories[] = $category;
                            }
                        }
                    }
                }
            }
        }

        /*foreach ($categories as $category) {                        
            if ($companyId === null || !in_array($category->id, $companyId)) {                                
                $newSubCategories[] = $category;
            }
        }*/

        return $newSubCategories;
        /*$clientArray = get_object_vars($client);
        $newSubCategories = [];

        if (array_key_exists('company_id', $clientArray) && $clientArray['company_id'] !== null) {
            foreach ($categories as $category) {
                $newSubCategories[] = $category;
            }
        } else if (array_key_exists('employee_id', $clientArray) && $clientArray['employee_id'] !== null) {

            # Validamos la categoria principal actual
            $subCategoriesInCart = (session('SubCategoriesInCart')) ? explode(',', session('SubCategoriesInCart')) : [];

            # Recorremos las categorias totales
            foreach ($categories as $category) {

                $addToListSubcategories = true; // Evaluar si una subcategoria debe ser visible o no (validacion de corps)                
                if (count($subCategoriesInCart) > 0) {

                    foreach ($subCategoriesInCart as $inCart) {
                        $cateSubcate = explode('-', $inCart);

                        if (in_array($cateSubcate[0], [17, 16])) {
                            if ($category->id == $cateSubcate[1]) {
                                $addToListSubcategories = false; // no agregar la subcategoria                                
                                break;
                            }
                        }
                    }
                }

                if ($addToListSubcategories) {
                    $newSubCategories[] = $category;
                }
            }
        } else if (array_key_exists('clientsvip_id', $clientArray) && $clientArray['clientsvip_id'] !== null) {
            foreach ($categories as $category) {
                $newSubCategories[] = $category;
            }
        } else if (array_key_exists('no_employee_id', $clientArray) && $clientArray['no_employee_id'] !== null) {
            foreach ($categories as $category) {
                $newSubCategories[] = $category;
            }
        }
        return $newSubCategories;*/
    }

    public static function filterPaymentMethods($paymentMethods, $client)
    {
        $newPaymentMethods = [];


        $fecha = $client->contract_start_date;
        $fechaActual = Carbon::now();

        $diferenciaMeses = $fechaActual->diffInMonths(Carbon::parse($fecha));

        foreach ($paymentMethods as $method) {
            if ($method->id == 1 && $diferenciaMeses >= 3) { //CORP MILLA - CORP 3ERIZA
                continue;
            }
            $newPaymentMethods[] = $method;
        }

        return $newPaymentMethods;
    }

    public static function validatePricingMenu($nameCategory, $client)
    {
        $clientArray = get_object_vars($client);

        $mapPricing = [
            'menu' => 14,
            'fondo' => 10,
            'entrada' => 5
        ];

        // Por defecto el costo del menu es de 14 s/ para no colaboradores
        $generalPrice = 14;

        if (array_key_exists('company_id', $clientArray) && $clientArray['company_id'] !== null) {
            $generalPrice = 14;
        } else if (array_key_exists('employee_id', $clientArray) && $clientArray['employee_id'] !== null) { // para colaboradores
            if ($clientArray['company'] == 'MILLA MARTINEZ IGNACIO PABLO' || $clientArray['company'] == 'IMPORTACION EXPORTACION APC SAC') {
                $generalPrice = 14;
            } else {
                $generalPrice = 8;
            }
        }
        return $generalPrice;
    }

    public static function validatePricingDrinks($client)
    {
    }

    public static function validateOneOrderInCurrentDay($personId, $pCategories)
    {
        $categories = $pCategories;
        $order = Order::leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
            ->leftJoin('products', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
            ->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id')
            ->where('orders.person_id', $personId)
            ->where('orders.date_order', date('Y-m-d'))
            ->select('orders.id', 'orders.date_order', 'categories.id as category_id')
            ->get();

        foreach ($order as $item) {
            $categoryId = $item->category_id;

            // Verificar si el categoryId existe en $categories
            if (($key = array_search($categoryId, $categories)) !== false) {
                unset($categories[$key]);
            }
        }

        return $categories;
    }

    public static function validateStock($productId, $quantity)
    {
        $stock = Stock::where('product_id', $productId);

        if ($stock->exists()) {
            $stock = $stock->first();
            if ($stock->quantity >= $quantity) {
                return $stock->quantity;
            } else {
                return false;
            }
        }
    }

    public static function getQuantityMenusByProfile($client)
    {
        $quantityMenu = 1;
        if ($client->clientsvip_id) {
            $quantityMenu = 100;
        }
        return $quantityMenu;
    }

    public static function validateProductInCart($products, $shoppingCart)
    {
        // Crear un índice de productos en el carrito por productId
        $cartIndex = [];
        foreach ($shoppingCart as $item) {
            $cartIndex[$item["productId"]] = $item;
        }

        // Iterar solo una vez a través de los productos
        foreach ($products as $product) {
            if (isset($cartIndex[$product->id])) {
                $cartItem = $cartIndex[$product->id];
                $product->in_cart = true;
                $product->in_iced = isset($cartItem['iced_price']);
            }
        }

        return $products;
    }
}

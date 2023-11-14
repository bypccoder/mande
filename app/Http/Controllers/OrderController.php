<?php

namespace App\Http\Controllers;

use App\Helpers\RulesHelper;
use App\Mail\VoucherEmail;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\FormaPagos\FormaPagoCredito;
use Greenter\Model\Sale\Cuota;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use DateTime;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SubCategory;
use App\Models\Voucher;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FileImage;
use Mike42\Escpos\EscposImage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Greenter\Model\Response\Error;
use Greenter\Model\Sale\Document;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client as GClient;
use Illuminate\Support\Facades\Http;
use Greenter\Model\Sale\Note;
use Illuminate\Routing\Route;

class OrderController extends Controller
{
    /**
     * STATUS SUNAT
     * 0 => No se envia a SUNAT es nota de venta
     * 1 => Enviado
     * 2 => Anulado
     * 3 => Rechazado por SUNAT
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            #$data = Order::select('*');
            $data = Order::select(
                'orders.*',
                'vouchers.path_pdf',
                'vouchers.data_path_note_credit',
                'vouchers.is_note_credit',
                'charge_code_note_credit',
                'payment_methods.payment_method as payment_method',
                'vouchers.is_note_credit',
                DB::raw('CASE WHEN vouchers.is_note_credit = 1 THEN "NOTA DE CRÉDITO" ELSE voucher_types.name END as voucher_type'),

                DB::raw('CONCAT(people.name," ",people.lastname_1," ",people.lastname_2) as person')
            )

                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
                ->leftJoin('voucher_types', 'voucher_types.id', '=', 'orders.form_method_id')
                ->leftJoin('people', 'people.id', '=', 'orders.person_id')
                ->leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
                //->where('orders.status_id', '=', 1)
                ->where('orders.type_order', '=', 0)
                ->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('monto', function ($row) {
                    $dato = '<span class="text-success">' . number_format($row->amount, 2) . ' S/.</span>';
                    if ($row->status_sunat == 0 && $row->form_method_id != 3 || ($row->status_id == 0 && $row->form_method_id == 3)) {
                        $dato = '<span class="text-danger">' . number_format(($row->amount * -1), 2) . ' S/.</span>';
                    } else if ($row->is_note_credit == 1) {
                        $dato = '<span class="text-danger">' . number_format(($row->amount * -1), 2) . ' S/.</span>';
                    }
                    return $dato;
                })
                ->addColumn('estado', function ($row) {
                    $html = '<span class="badge text-bg-success text-white">ENVIADO A SUNAT</span>';
                    if ($row->status_sunat == 0 && $row->form_method_id != 3) {
                        $html = '<span class="badge text-bg-light">ANULADO</span>';
                    } else if ($row->status_id == 0 && $row->form_method_id == 3) {
                        $html = '<span class="badge text-bg-light">ANULADO</span>';
                    } else if ($row->status_sunat == 3 && $row->status_id == 1) {
                        $html = '<span class="badge text-bg-danger text-white">RECHAZADO POR SUNAT</span>';
                    }
                    return $html;
                })
                ->addColumn('details', function ($row) {

                    if ($row->form_method_id == 3) {
                        $html = '<div class="dropdown">
                    <button class="btn btn-light rounded-pill btn-sm" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                    <i class="las la-ellipsis-v"></i>
                    </button>
                  </div>';
                    } else {
                        if ($row->is_note_credit == 1) {
                            $html = '<div class="dropdown">
                            <button class="btn btn-light rounded-pill btn-sm" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                            <i class="las la-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                            <li><a class="dropdown-item viewVoucher" target="_blank" href="#" data-pdf="' . $row->path_pdf . '" >Ver Comprobante</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item viewVoucher" target="_blank" href="#" data-pdf="' . str_replace(".pdf", ".xml", $row->path_pdf) . '" >Descargar XML</a></li>
                            <li><a class="dropdown-item viewVoucher" target="_blank" href="#" data-pdf="' . str_replace(".pdf", ".zip", $row->path_pdf) . '" >Descargar CDR</a></li>
                            </ul>
                            </div>';
                        } else {
                            $html = '<div class="dropdown">
                            <button class="btn btn-light rounded-pill btn-sm" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                            <i class="las la-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item modalNotaCredito" data-order="' . $row->id . '" href="#">Nota de Crédito</a></li>
                              <li><hr class="dropdown-divider"></li>
                              <li><a class="dropdown-item viewVoucher" target="_blank" href="#" data-pdf="' . $row->path_pdf . '" >Ver Comprobante</a></li>
                              <li><hr class="dropdown-divider"></li>
                              <li><a class="dropdown-item viewVoucher" target="_blank" href="#" data-pdf="' . str_replace(".pdf", ".xml", $row->path_pdf) . '" >Descargar XML</a></li>
                              <li><a class="dropdown-item viewVoucher" target="_blank" href="#" data-pdf="' . str_replace(".pdf", ".zip", $row->path_pdf) . '" >Descargar CDR</a></li>
                            </ul>
                          </div>';
                        }
                    }


                    $actionBtn = '<a href="javascript:void(0)" class="viewDetail mx-2 btn btn-light rounded-pill btn-sm" data-order="' . $row->id . '" data-coreui-toggle="modal" data-coreui-target="#modal-details"><i class="las la-eye"></i></a>';
                    return '<div class="d-flex flex-row">' . $actionBtn . $html . '</div>';
                })
                ->rawColumns(['action', 'details', 'sunat', 'estado', 'monto'])
                ->make(true);
        }

        return view('order.index');
    }

    public function getOrderDetails(Request $request)
    {

        if (!$request->expectsJson()) {
            return "Error";
        }

        $order_id = $request->order;
        /*$orderRaw = Order::select('orders.*', 'people.name as person_name')
            ->leftJoin('people', 'orders.person_id', '=', 'people.id')
            ->where('orders.id', '=', $order_id)
            ->first();*/

        $orderRw = Order::select('orders.amount as total')
            ->find($order_id);

        $detail =  OrderDetail::select('order_details.*', 'products.name as product')
            ->leftJoin('products', 'order_details.product_id', '=', 'products.id')
            ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.id', '=', $order_id)
            ->get();



        $orderRaw = Order::leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
            ->select('vouchers.charge_code', 'vouchers.document', 'vouchers.client', 'vouchers.phone', 'vouchers.email', 'vouchers.subtotal', 'vouchers.total', 'vouchers.vat')
            ->find($order_id);

        $order = [
            'cod.comprobante'  => $orderRaw->charge_code,
            'documento' => $orderRaw->document,
            'cliente' => $orderRaw->client,
            'telefono' => $orderRaw->phone,
            'email' => $orderRaw->email
        ];

        if ($orderRaw) {
            $orderAmount = [
                'subtotal' => round($orderRw->total / 1.18, 2),
                'igv' => 18,
                'total' => $orderRw->total,
            ];
        } else {
            $orderAmount = [
                'subtotal' => $orderRaw->subtotal,
                'igv' => $orderRaw->vat,
                'total' => $orderRaw->total,
            ];
        }

        $response = [
            'code' => 200,
            'data' => [
                'order' => $order,
                'details' => $detail,
                'orderAmount' => $orderAmount
            ]
        ];

        return response()->json($response);
    }


    public function send_api_sunat_factura($headers, $detail_headers, $order)
    {
        $sunat = new SunatController();
        $see = $sunat->connect();
        // Cliente
        $client = (new Client())
            ->setTipoDoc($headers['document_type'])
            ->setNumDoc($headers['document'])
            ->setRznSocial($headers['company']);

        // dd($client);
        // Emisor
        $address = (new Address())
            ->setUbigueo('150108')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('CHORRILLOS')
            ->setUrbanizacion('SANTA LEONOR')
            ->setDireccion('JR. LIZANDRO DE LA PUENTE NRO. 561')
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $company = (new Company())
            ->setRuc('10098282462')
            ->setRazonSocial('MILLA MARTINEZ IGNACIO PABLO')
            ->setNombreComercial('LA CAFETERÍ@')
            ->setAddress($address);


        // Venta

        $total = $headers['amount'];
        $subtotal = number_format(($total / 1.18), 2, '.', '.');
        $igv = $total - $subtotal;

        $fechaInicio = new DateTime($headers['date_start_credit']);
        $fechaFin = new DateTime($headers['date_end_credit']);
        $plazoDias = $fechaInicio->diff($fechaFin)->days;
        $fechaVencimiento = $fechaFin;

        $tipoFactura = $headers['invoice_type_id'];
        if ($tipoFactura == 2) {
            $formaPago = new FormaPagoContado();
        } else if ($tipoFactura == 1) {
            $formaPago = new FormaPagoCredito($total);
        } else {
            $formaPago = new FormaPagoContado();
        }

        /*============================================
        */

        $lastVoucher = Voucher::where('charge_code', 'like', 'F%')->first();

        $serie = 'F002'; // Serie por defecto para boletos.
        $correlative = 1; // Correlativo por defecto.

        if ($lastVoucher) {
            $lastChargeCode = $lastVoucher->charge_code;
            $correlative = intval(explode('-',$lastChargeCode)[1]+1);
        }

        // Construir el código de carga con la serie y el correlativo.
        $chargeCode = $serie . '-' . $correlative;
        /*
    ============================================*/

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta - Catalog. 51
            ->setTipoDoc('01') // Factura - Catalog. 01
            ->setSerie($serie)
            ->setCorrelativo($correlative)
            ->setFechaEmision(new DateTime()) // Zona horaria: Lima
            ->setFormaPago($formaPago) // FormaPago: Contado
            ->setTipoMoneda('PEN') // Sol - Catalog. 02
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($subtotal) //subtotal
            ->setMtoIGV($igv) //igv
            ->setTotalImpuestos($igv) //igv
            ->setValorVenta($subtotal) //subtotal
            ->setSubTotal($total) //total
            ->setMtoImpVenta($total); //total

        if ($tipoFactura == 1) {
            $invoice->setCuotas([
                (new Cuota())
                    ->setMonto($total)
                    ->setFechaPago(new DateTime('+' . $plazoDias . 'days'))
            ])
                ->setFecVencimiento($fechaVencimiento);
        }

        $items = [];
        $i = 1;
        foreach ($detail_headers as $itemJson) {

            $item = json_decode($itemJson);

            $product = Product::find($item->productId);
            $price = $product->sales_price;
            $cod_product = $product->code;

            $detail_amount = number_format($price * $item->quantity, 2, '.', '.');
            $detail_subtotal = number_format(($detail_amount / 1.18), 2, '.', '.');
            $detail_igv = $detail_amount - $detail_subtotal;
            $amount_unity_value = number_format(($price / 1.18), 2, '.', '.');

            if ($item->price > 0) {
                $items[] = (new SaleDetail())
                    ->setCodProducto($cod_product)
                    ->setDescripcion($item->productName)
                    ->setUnidad('NIU') // Unidad - Catalog. 03
                    ->setCantidad($item->quantity)
                    ->setMtoValorUnitario($amount_unity_value)
                    ->setMtoBaseIgv($detail_subtotal)
                    ->setPorcentajeIgv(18.00) // 18%
                    ->setIgv($detail_igv)
                    ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos($detail_igv) // Suma de impuestos en el detalle
                    ->setMtoValorVenta($detail_subtotal)
                    ->setMtoPrecioUnitario($price);
            }


            $i++;
        }

        $formatter = new NumeroALetras();

        $valorTotalLetras = $formatter->toWords($headers['amount']) . ' CON 00/100 SOLES';

        $legend = (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue($valorTotalLetras);

        $invoice->setDetails($items)
            ->setLegends([$legend]);

        // dd($invoice);

        //Envio a SUNAT
        $result = $see->send($invoice);

        $fileDir = storage_path('files');

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        // Guardar XML firmado digitalmente.
        //En templates cambiar valor de cbc:URI por la ruta en el servidor donde esta el certificado digital .pfx
        /*file_put_contents(
            $invoice->getName() . '.xml',
            $see->getFactory()->getLastXml()
        );*/

        file_put_contents($fileDir . '/' . $invoice->getName() . '.xml', $see->getFactory()->getLastXml());

        $responseLocalData = array();
        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {

            $order_st = Order::find($order->id);
            $order_st->status_sunat = 3;
            $order_st->save();

            return $responseLocalData;
        } else {
            $data = [
                'voucher_type_id' => 2,
                'order_id' => $order->id,
                'charge_code' => $invoice->getSerie() . '-' . $invoice->getCorrelativo(),
                'document' => $headers['document'],
                'client' => $headers['client'],
                'address' => '',
                'phone' => '',
                'email' => '',
                'payment_condition' => '',
                'vat' => $igv,
                'subtotal' => $subtotal,
                'total' => $total,
                'cash' => 0,
                'change' => 0,
                'status_id' => 1,
                'path_xml' =>  'storage/files/' . $invoice->getName() . '.xml',
                'path_pdf' => 'storage/files/' . $invoice->getName() . '.pdf'

            ];

            Voucher::create($data);

            //Imprimir Factura
            $dataInvoice = array();
            $dataInvoice = array(
                'serie' => $invoice->getSerie(),
                'correlativo' => $invoice->getCorrelativo(),
                'fechaEmision' => $invoice->getFechaEmision()->format('Y-m-d H:i:s'),
                'numDocCliente' => $client->getNumDoc(),
                'nombreCliente' => $client->getRznSocial(),
                'total' => $invoice->getMtoImpVenta(),
                'subtotal' => $invoice->getMtoOperGravadas()

            );

            $responseLocalData = [
                'print_data_url' => 'http://localhost/impresora/imprimirBoletaOFactura.php',
                'invoice' => $dataInvoice,
                'method_payment' => $headers['payment_method_id'],
                'orders' => $order,
                'detail_headers' => $detail_headers
            ];

            // Guardamos el CDR
            file_put_contents($fileDir . '/' . $invoice->getName() . '.zip', $result->getCdrZip());

            //reports
            $util = SunatController::getInstance();

            try {
                $pdf = $util->getPdf($invoice);
                $util->showPdf($pdf, $invoice->getName() . '.pdf');
            } catch (\Exception $e) {
                var_dump($e);
            }
        }

        return $responseLocalData;
    }

    public function send_api_sunat_boleta($headers, $detail_headers, $order)
    {

        $sunat = new SunatController();
        $see = $sunat->connect();

        // Cliente
        $client = new Client();
        $client->setTipoDoc($headers['document_type'])
            ->setNumDoc($headers['document'])
            ->setRznSocial($headers['client']);
        //Dirección
        $address = new Address();
        $address->setUbigueo('150108')
            ->setDistrito('CHORRILLOS')
            ->setProvincia('LIMA')
            ->setDepartamento('LIMA')
            ->setUrbanizacion('SANTA LEONOR')
            ->setCodLocal('0000')
            ->setDireccion('JR. LIZANDRO DE LA PUENTE NRO. 561');

        //Empresa
        $company = new Company();
        $company->setRuc('10098282462')
            ->setNombreComercial('LA CAFETERÍ@')
            ->setRazonSocial('MILLA MARTINEZ IGNACIO PABLO')
            ->setAddress($address)
            ->setEmail('lacafeteriacec@gmail.com')
            ->setTelephone('993745873');

        // Venta

        /*$subtotal = number_format($headers['amount'] / 1.18, 2, '.', '.');
        $subtotalConIgv =  $subtotal * 0.18;*/

        $total = $headers['amount'];
        $subtotal = number_format(($total / 1.18), 2, '.', '.');
        $igv = $total - $subtotal;

        // 'invoice_type_id' => $request->order['invoiceType'],
        // 'date_start_credit' => $request->order['startDate'],
        // 'date_end_credit' => $request->order['endDate']

          /*
    ============================================
    */

    $lastVoucher =  $lastVoucher = Voucher::where('charge_code', 'like', 'B%')->first();

    $serie = 'B002'; // Serie por defecto para boletos.
    $correlative = 1; // Correlativo por defecto.

    if ($lastVoucher) {
        $lastChargeCode = $lastVoucher->charge_code;
        $correlative = intval(explode('-',$lastChargeCode)[1]+1);
    }

    // Construir el código de carga con la serie y el correlativo.
    $chargeCode = $serie . '-' . $correlative;
    /*
============================================
  */

        $invoice = new Invoice();
        $invoice
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc('03')
            ->setSerie($serie)
            ->setCorrelativo($correlative)
            ->setFechaEmision(new DateTime())
            ->setTipoMoneda('PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($subtotal) //subtotal
            ->setMtoIGV($igv) //$igv
            ->setTotalImpuestos($igv) //$igv
            ->setValorVenta($subtotal) //subtotal
            ->setSubTotal($total)
            ->setMtoImpVenta($total);

        $items = [];
        $i = 1;
        foreach ($detail_headers as $itemJson) {

            $item = json_decode($itemJson);

            $product = Product::find($item->productId);
            $price = $item->price;

            $cod_product = $product->code;

            $detail_amount = number_format($price * $item->quantity, 2, '.', '.');
            $detail_subtotal = number_format(($detail_amount / 1.18), 2, '.', '.');
            $detail_igv = $detail_amount - $detail_subtotal;
            $amount_unity_value = number_format(($price / 1.18), 2, '.', '.');

            if ($item->price > 0) {
                $items[] = (new SaleDetail())
                    ->setCodProducto($cod_product)
                    ->setUnidad('NIU')
                    ->setCantidad($item->quantity)
                    ->setDescripcion($item->productName)
                    ->setMtoValorUnitario($amount_unity_value)
                    ->setMtoBaseIgv($detail_subtotal)
                    ->setPorcentajeIgv(18.00)
                    ->setIgv($detail_igv)
                    ->setTipAfeIgv('10')
                    ->setTotalImpuestos($detail_igv)
                    ->setMtoValorVenta($detail_subtotal)
                    ->setMtoPrecioUnitario($price);
            }
            $i++;
        }



        $formatter = new NumeroALetras();

        $valorTotalLetras = $formatter->toWords($headers['amount']) . ' CON 00/100 SOLES';

        $legend = (new Legend())
            ->setCode('1000') // Monto en letras - Catalog. 52
            ->setValue($valorTotalLetras);

        $invoice->setDetails($items)
            ->setLegends([$legend]);

        //Envio a SUNAT
        $result = $see->send($invoice);
        $fileDir = storage_path('files');

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        file_put_contents($fileDir . '/' . $invoice->getName() . '.xml', $see->getFactory()->getLastXml());

        $responseLocalData = array();
        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            $order_st = Order::find($order->id);
            $order_st->status_sunat = 3;
            $order_st->save();

            // dump('Ocurrio un problema:'.$result->getError()->getMessage());

            return $responseLocalData;
        } else {
            $data = [
                'voucher_type_id' => 1,
                'order_id' => $order->id,
                'charge_code' => $invoice->getSerie() . '-' . $invoice->getCorrelativo(),
                'document' => $headers['document'],
                'client' => $headers['client'],
                'address' => '',
                'phone' => '',
                'email' => '',
                'payment_condition' => '',
                'vat' => $igv,
                'subtotal' => $subtotal,
                'total' => $total,
                'cash' => 0,
                'change' => 0,
                'status_id' => 1,
                'path_xml' =>  'storage/files/' . $invoice->getName() . '.xml',
                'path_pdf' => 'storage/files/' . $invoice->getName() . '.pdf'

            ];

            Voucher::create($data);

            $dataInvoice = array();
            $dataInvoice = array(
                'serie' => $invoice->getSerie(),
                'correlativo' => $invoice->getCorrelativo(),
                'fechaEmision' => $invoice->getFechaEmision()->format('Y-m-d H:i:s'),
                'numDocCliente' => $client->getNumDoc(),
                'nombreCliente' => $client->getRznSocial(),
                'total' => $invoice->getMtoImpVenta(),
                'subtotal' => $invoice->getMtoOperGravadas()

            );


            $responseLocalData[] = [
                'print_data_url' => 'http://localhost/impresora/imprimirBoletaOFactura.php',
                'invoice' =>  $dataInvoice,
                'method_payment' => $headers['payment_method_id'],
                'orders' => $order,
                'detail_headers' => $detail_headers
            ];

            // Verificar si algún elemento tiene un precio de 0
            $precioCero = false;
            foreach ($detail_headers as $itemJson2) {
                $item2 = json_decode($itemJson2);
                if ($item2->price == 0) {
                    $precioCero = true;
                    break;
                }
            }

            $response['printData'] =  $responseLocalData;

            // Guardamos el CDR
            try {
                file_put_contents($fileDir . '/' . $invoice->getName() . '.zip', $result->getCdrZip());

                //reports
                $util = SunatController::getInstance();
                $pdf = $util->getPdf($invoice);
                $util->showPdf($pdf, $invoice->getName() . '.pdf');
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

        return $responseLocalData;
    }

    public function store(Request $request)
    {
        //request()->validate(Order::$rules);

        $response = ['code' => 400, 'status' => 'error', 'message' => ''];

        /**
         * Validamos stock de cada producto
         */
        $notStock = [];
        $numCorp = 0;
        foreach ($request->orderDetail as $itemJson) {
            $item = json_decode($itemJson);
            if ($item->subCategory == 5 || $item->subCategory == 6 || $item->subCategory == 7) {
                $numCorp++;
            }
            $stock = RulesHelper::validateStock($item->productId, $item->quantity);
            if (!$stock) {
                $notStock[$item->productId] = $stock;
            }
        }

        if (count($notStock) > 0) {
            $message = '';
            foreach ($notStock as $key => $value) {
                $product = Product::find($key);
                $message .= $product->name . ', ';
            }
            $response['message'] = 'El producto(s) ' . $message . ' no se encuentran disponibles. ';
            return response()->json($response);
        }

        $person_id = session('person')->id;

        $date_order = date('Y-m-d H:i:s');

        $data = [
            'person_id' => $person_id,
            'client' => session('person')->name . ' ' . session('person')->last_name,
            'company' => session('person')->company_name,
            'document' => session('person')->document,
            'document_type' => session('person')->document_type_id == 1 ? 1
                : (session('person')->document_type_id == 3 ? 6 : 4),
            'date_order' => $date_order,
            'amount' => $request->order['amount'],
            'form_method_id' => $request->order['form_method_id'],
            'payment_method_id' => $request->order['payment_method_id'],
            'invoice_type_id' => !isset($request->order['invoiceType']) ? 0 : $request->order['invoiceType'],
            'date_start_credit' => !isset($request->order['start_date']) ? null : $request->order['start_date'],
            'date_end_credit' => !isset($request->order['end_date']) ? null : $request->order['end_date']
        ];

        $order = Order::create($data);

        //Actualizar tabla order
        $order_ic = Order::find($order->id);
        $order_ic->internal_code = $order->id;
        $order_ic->save();

        if ($order) {
            $response['code'] = 200;
            $response['status'] = 'success';
            $response['message'] = 'Se agrego correctamente';

            $headers = $data;
            $detail_headers = $request->orderDetail;

            //Resumen para email
            $orderDetail = [];

            foreach ($request->orderDetail as $itemJson) {
                $item = json_decode($itemJson);
                $product = Product::find($item->productId);

                /**
                 * Validar precio menu
                 */

                $nameCategory = SubCategory::find($item->subCategory)->name;
                $generalprice = $product->sales_price;
                if (str_contains(strtolower($nameCategory), 'menu')) {
                    $generalprice = RulesHelper::validatePricingMenu($nameCategory, session('person'));
                }
                $precioProducto = $generalprice;

                /*$subTotal = $precioProducto * $item->quantity;
                $igv = $request->order['amount'] == 0 ? 0 : $subTotal - ($subTotal / 1.18);
                $total = $request->order['amount'] == 0 ? : $subTotal + $igv;*/

                $precioConIGV = ($precioProducto * $item->quantity);
                $precioSinIGV = $precioConIGV / 1.18;

                $dataDetail = [
                    'order_id' => $order->id,
                    'product_id' => $item->productId,
                    'quantity' => $item->quantity,
                    'subtotal' => $precioSinIGV,
                    'total' => $precioConIGV
                ];

                $orderDetailRsult = OrderDetail::create($dataDetail);

                //Resumen para email
                if ($orderDetailRsult) {
                    $dataDetail['product'] = $product->name;
                    $orderDetail[] = $dataDetail;
                }
            }

            //$this->sendEmail($order_ic, $orderDetail);

            if (($request->order['amount']) > 0   && $numCorp == 0) {

                $order_st = Order::find($order->id);
                $order_st->status_sunat = 1;
                $order_st->save();

                $responsePayment = [];

                if ($request->order['form_method_id'] == 1) { // BOLETA

                    $response['printData'] = $this->send_api_sunat_boleta($headers, $detail_headers, $order);

                    if ($response['printData'] == null) {
                        $response['code'] = 500;
                        $response['status'] = 'error';
                        $response['message'] = 'Ocurrio un problema con SUNAT si el error persiste contacte con el administrador.';
                    }
                } else if ($request->order['form_method_id'] == 2) { // FACTURA
                    $response['printData'] =  $this->send_api_sunat_factura($headers, $detail_headers, $order);
                    if ($response['printData'] == null) {
                        $response['code'] = 500;
                        $response['status'] = 'error';
                        $response['message'] = 'Ocurrio un problema con SUNAT si el error persiste contacte con el administrador.';
                    }
                }
            } else {
                $order_st = Order::find($order->id);
                $order_st->status_sunat = 0;
                $order_st->form_method_id = 3;
                $order_st->save();

                // dd($headers);
                //Imprimir
                $responseLocalData[] = [
                    'print_data_url' => 'http://localhost/impresora/imprimirNotaVenta.php',
                    'invoice' =>  $headers,
                    'method_payment' => $headers['payment_method_id'],
                    'orders' => $order,
                    'detail_headers' => $detail_headers
                ];
                // dd($detail_headers);

                $response['printData'] =  $responseLocalData;
            }

            $request->session()->forget('person');
        }

        return response()->json($response);
    }



    public function anulacion_orden(Request $request)
    {
        $orderId = $request->order;

        $order = Order::leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
            ->select('orders.id', 'orders.type_order', 'vouchers.voucher_type_id', 'vouchers.charge_code', 'orders.date_order', 'orders.internal_code', 'vouchers.id as voucher_id')
            ->where('orders.id', '=', $orderId)
            ->first();

        #$response = ['code' => 400];
        $response = ['code' => 200];
        if ($order->voucher_type_id == 1) { // IS BOLETA
            $response = $this->anulacion_api_sunat_boleta($order);
        } else if ($order->voucher_type_id == 2) { // IS FACTURA
            $response = $this->anulacion_api_sunat_factura($order);
        } else { // IS NOTA DE VENTA

            //se anula orden
            $order = Order::find($orderId);
            $order->status_id = 0;
            $order->save();

            //se anula voucher
            // $voucher = Voucher::find($order->voucher_id);
            // $voucher->status_id = 0;
            // $voucher->save();

            $response = ['code' => 200, 'status' => 'ok', 'message' => 'Se anulo correctamente'];
        }

        if ($response['code'] == 200 && $order->voucher_type_id  != 3) {
            $orderDetail = OrderDetail::where('order_id', '=', $orderId)->get();
            foreach ($orderDetail as $detail) {
                if ($order->type_order == 0) {
                    $stock = Stock::where('product_id', $detail->product_id)->first();
                    $stock->increment('quantity', $detail->quantity);
                }
            }
        }

        return response()->json($response);
    }

    public function nota_credito(Request $request)
    {
        $response = ['code' => 400, 'status' => 'error', 'message' => 'Invalid'];



        $orderId = $request->orderId;

        $order = Order::leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
            ->leftJoin('people', 'people.id', '=', 'orders.person_id')
            ->select('orders.id', 'vouchers.voucher_type_id', 'vouchers.charge_code', 'orders.date_order', 'orders.internal_code', 'vouchers.id as voucher_id', 'vouchers.document', 'vouchers.client', 'people.document_type_id', 'vouchers.total')
            ->where('orders.id', '=', $orderId)
            ->orderBy('vouchers.created_at', 'desc')
            ->orderBy('orders.created_at', 'desc')
            ->first();

        $sunat = new SunatController();
        $see = $sunat->connect();
        // Cliente
        $client = new Client();
        $client->setTipoDoc($order->document_type_id == 1 ? 1
            : ($order->document_type_id == 3 ? 6 : 4))
            ->setNumDoc($order->document)
            ->setRznSocial($order->client);
        //Dirección
        $address = new Address();
        $address->setUbigueo('150108')
            ->setDistrito('CHORRILLOS')
            ->setProvincia('LIMA')
            ->setDepartamento('LIMA')
            ->setUrbanizacion('SANTA LEONOR')
            ->setCodLocal('0000')
            ->setDireccion('JR. LIZANDRO DE LA PUENTE NRO. 561');

        //Empresa
        $company = new Company();
        $company->setRuc('10098282462')
            ->setNombreComercial('LA CAFETERÍ@')
            ->setRazonSocial('MILLA MARTINEZ IGNACIO PABLO')
            ->setAddress($address)
            ->setEmail('lacafeteriacec@gmail.com')
            ->setTelephone('993745873');

        $total = $order->total;
        $subtotal = number_format(($total / 1.18), 2, '.', '.');
        $igv = $total - $subtotal;

        $serieCorrelativoArr = explode('-', $order->charge_code);
        $motivoNotaCreditoArr = explode('-', $request->cboMotivoNotaCredito);


        $tipoDocAfectado = substr($serieCorrelativoArr[0], 0, 1) == 'B' ? '03' : '01';

        if ($tipoDocAfectado == '01') {
            $lastVoucher = Voucher::where('charge_code', 'like', 'F%')->where('is_note_credit', 1)->first();
        } elseif ($tipoDocAfectado == '03') {
            $lastVoucher = Voucher::where('charge_code', 'like', 'B%')->where('is_note_credit', 1)->first();
        }
        /* ================================= */



        $serie = 'B002'; // Serie por defecto para boletos.
        $correlative = 1; // Correlativo por defecto.

        if ($lastVoucher) {
            $lastChargeCode = $lastVoucher->charge_code;
            $correlative = intval(explode('-',$lastChargeCode)[1]+1);
        }

        // Construir el código de carga con la serie y el correlativo.
        $chargeCode = $serie . '-' . $correlative;
        /* ================================= */



        $note = new Note();
        $note
            ->setUblVersion('2.1')
            ->setTipoDoc('07')
            ->setSerie($serie)
            ->setCorrelativo($correlative)
            ->setFechaEmision(new DateTime())
            ->setTipDocAfectado($tipoDocAfectado)
            ->setNumDocfectado($order->charge_code) // Factura: Serie-Correlativo
            ->setCodMotivo($motivoNotaCreditoArr[0]) // Catalogo. 09
            ->setDesMotivo($motivoNotaCreditoArr[1])
            ->setTipoMoneda('PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($subtotal)
            ->setMtoIGV($igv)
            ->setTotalImpuestos($igv)
            ->setMtoImpVenta($total);


        $items = [];
        $i = 1;

        $orderDetail = OrderDetail::where('order_id', $order->id)->get();

        foreach ($orderDetail as $item) {


            $product = Product::find($item->product_id);

            if($product->product_type_id == "1" || $product->product_type_id == "2"){
                $productType = Product::leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
                ->select('products.*', 'product_types.price as price_type')
                ->whereIn('products.product_type_id', [1,2])
                ->first();
                $price = $item->total/$item->quantity; //$productType->price_type;

            }else{
                $price = $product->sales_price;
            }

            $cod_product = $product->code;

            $detail_amount = number_format($price * $item->quantity, 2, '.', '.');
            $detail_subtotal = number_format(($detail_amount / 1.18), 2, '.', '.');
            $detail_igv = $detail_amount - $detail_subtotal;
            $amount_unity_value = number_format(($price / 1.18), 2, '.', '.');

            $items[] = (new SaleDetail())
                ->setCodProducto($cod_product)
                ->setUnidad('NIU') // Unidad - Catalog. 03
                ->setCantidad($item->quantity)
                ->setDescripcion($item->name)
                ->setMtoBaseIgv($detail_subtotal)
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($detail_igv)
                ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                ->setTotalImpuestos($detail_igv) // Suma de impuestos en el detalle
                ->setMtoValorVenta($detail_subtotal)
                ->setMtoValorUnitario($amount_unity_value)
                ->setMtoPrecioUnitario($price);


            $i++;
        }


        $formatter = new NumeroALetras();

        $valorTotalLetras = $formatter->toWords($order->total) . ' CON 00/100 SOLES';

        $legend = new Legend();
        $legend->setCode('1000')
            ->setValue($valorTotalLetras);

        $note->setDetails($items)
            ->setLegends([$legend]);

        //Envio a SUNAT
        $result = $see->send($note);

        $fileDir = storage_path('files');

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        file_put_contents($fileDir . '/' . $note->getName() . '.xml', $see->getFactory()->getLastXml());


        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            //dd($result->getError()->getMessage());
            // Mostrar error al conectarse a SUNAT.
            $response = ['code' => 400, 'status' => 'error', 'message' =>  $result->getError()->getCode() . ' ' . $result->getError()->getMessage()];
            return response()->json($response);
        } else {

            // Guardamos el CDR
            try {
                file_put_contents($fileDir . '/' . $note->getName() . '.zip', $result->getCdrZip());

                //reports
                $util = SunatController::getInstance();

                $pdf = $util->getPdf($note);
                $util->showPdf($pdf, $note->getName() . '.pdf');
            } catch (\Exception $e) {
            }

            /* Replicar la orden pero como nota de crédito */

            $data = [
                'voucher_type_id' => 2,
                'order_id' => $order->id,
                'charge_code' => $serieCorrelativoArr[0] . '-' . $serieCorrelativoArr[1],
                'document' => $order->document,
                'client' => $order->client,
                'address' => '',
                'phone' => '',
                'email' => '',
                'payment_condition' => '',
                'vat' => $igv,
                'subtotal' => $subtotal,
                'total' => $total,
                'cash' => 0,
                'change' => 0,
                'status_id' => 1,
                'path_xml' =>  'storage/files/' . $note->getName() . '.pdf',
                'path_pdf' => 'storage/files/' . $note->getName() . '.pdf',
                'is_note_credit' => 1

            ];

            Voucher::create($data);

            $response['code'] = 200;
            $response['status'] = 'success';
            $response['message'] = 'Se agrego correctamente';
        }



        return response()->json($response);
    }

    public function anulacion_api_sunat_factura($order)
    {

        $response = ['code' => 400, 'status' => 'error', 'message' => 'Invalid'];


        $util = SunatController::getInstance();

        $comprobanteArr = explode('-', $order->charge_code);


        $detail1 = new VoidedDetail();
        $detail1->setTipoDoc('01')
            ->setSerie($comprobanteArr[0])
            ->setCorrelativo($comprobanteArr[1])
            ->setDesMotivoBaja('ANULACIÓN COMPROBANTE ELECTRÓNICO');

        $address = (new Address())
            ->setUbigueo('150108')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('CHORRILLOS')
            ->setUrbanizacion('SANTA LEONOR')
            ->setDireccion('JR. LIZANDRO DE LA PUENTE NRO. 561')
            ->setCodLocal('0000'); // Código de establecimiento asignado por SUNAT, 0000 por defecto.

        $company = (new Company())
            ->setRuc('10098282462')
            ->setRazonSocial('MILLA MARTINEZ IGNACIO PABLO')
            ->setNombreComercial('LA CAFETERÍ@')
            ->setAddress($address);

        $voided = new Voided();
        $voided->setCorrelativo(sprintf('%05u', $order->internal_code))
            // Fecha Generacion menor que Fecha comunicacion
            ->setFecGeneracion(new DateTime($order->date_order))
            ->setFecComunicacion(new DateTime())
            ->setCompany($company)
            ->setDetails([$detail1]);

        $sunat = new SunatController();
        $see = $sunat->connect();

        $res = $see->send($voided);
        //$util->writeXml($voided, $see->getFactory()->getLastXml());

        $fileDir = storage_path('files');

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }



        file_put_contents($fileDir . '/' . $voided->getName() . '.xml', $see->getFactory()->getLastXml());

        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$res->isSuccess()) {
            $message = $res->getError()->getMessage();
            $response = ['code' => 400, 'status' => 'error', 'message' => $message];
        }



        $ticket = $res->getTicket();


        $res = $see->getStatus($ticket);
        if (!$res->isSuccess()) {

            $message = $res->getError()->getMessage();
            $response = ['code' => 400, 'status' => 'error', 'message' => $message];
        }

        file_put_contents($fileDir . '/' . $voided->getName() . '.zip', $res->getCdrZip());


        try {

            //Actualizar tabla voucher
            if ($res->isSuccess()) {
                $voucher = Voucher::find($order->voucher_id);
                $voucher->correlative_cancel = $order->internal_code;
                $voucher->number_ticket = $ticket;
                $voucher->save();

                $response['code'] = 200;
                $response['status'] = 'success';
                $response['message'] = 'Se anulo correctamente';
            }
        } catch (\Exception $e) {
            $response = ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }

        return $response;
    }

    public function anulacion_api_sunat_boleta($order)
    {
        $response = ['code' => 400, 'status' => 'error', 'message' => 'Invalid'];

        $voucher = Voucher::find($order->voucher_id);

        $util = SunatController::getInstance();

        $comprobanteArr = explode('-', $order->charge_code);

        $address = (new Address())
            ->setUbigueo('150108')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('CHORRILLOS')
            ->setUrbanizacion('SANTA LEONOR')
            ->setDireccion('JR. LIZANDRO DE LA PUENTE NRO. 561')
            ->setCodLocal('0000'); // Código de establecimiento asignado por SUNAT, 0000 por defecto.

        $company = (new Company())
            ->setRuc('10098282462')
            ->setRazonSocial('MILLA MARTINEZ IGNACIO PABLO')
            ->setNombreComercial('LA CAFETERÍ@')
            ->setAddress($address);

        // Crear una instancia de la boleta a anular
        $voided = new Voided();
        $voided->setCorrelativo(sprintf('%05u', $order->internal_code)) // Número correlativo de la anulación
            ->setFecGeneracion(new DateTime()) // Fecha de generación de la anulación
            ->setFecComunicacion(new DateTime()) // Fecha de comunicación de la anulación
            ->setCompany($company) // Datos de la empresa
            ->setDetails([
                (new VoidedDetail())
                    ->setTipoDoc('03') // Tipo de documento (03 para boleta)
                    ->setSerie($comprobanteArr[0]) // Serie de la boleta a anular
                    ->setCorrelativo($comprobanteArr[1]) // Número correlativo de la boleta a anular
                    ->setDesMotivoBaja('ANULACIÓN COMPROBANTE ELECTRÓNICO')
            ]);


        // Conectar con el servicio de SUNAT
        $sunat = new SunatController();
        $see = $sunat->connect();

        // Enviar la anulación de boleta a SUNAT
        $res = $see->send($voided);

        $fileDir = storage_path('files');

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }



        file_put_contents($fileDir . '/' . $voided->getName() . '.xml', $see->getFactory()->getLastXml());

        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$res->isSuccess()) {
            $message = $res->getError()->getMessage();
            $response = ['code' => 400, 'status' => 'error', 'message' => $message];
        }

        $ticket = $res->getTicket();

        $res = $see->getStatus($ticket);
        if (!$res->isSuccess()) {
            $message = $res->getError()->getMessage();
            $response = ['code' => 400, 'status' => 'error', 'message' => $message];
        }

        //$cdr = $res->getCdrResponse();
        //$util->writeCdr($voided, $res->getCdrZip());

        file_put_contents($fileDir . '/' . $voided->getName() . '.zip', $res->getCdrZip());

        //Actualizar tabla voucher
        try {
            if ($res->isSuccess()) {

                $order = Order::find($order->id);
                $order->status_sunat = 2;
                $order->save();

                $voucher = Voucher::find($order->voucher_id);
                $voucher->correlative_cancel = $order->internal_code;
                $voucher->number_ticket = $ticket;
                $voucher->save();

                $response['code'] = 200;
                $response['status'] = 'success';
                $response['message'] = 'Se anulo correctamente';
            }
        } catch (\Exception $e) {
            $response = ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
        }

        return $response;
    }

    function comprobante($path)
    {

        $file = storage_path('files/' . $path);

        return response()->download($file);
    }

    public function payOrder($amount, $orderId)
    {

        $url = env('IZIPAY_API_URL') . '/sales';

        $payload = [
            'amount' => $amount,
            'description' => 'Pedido Cafeteria'
        ];

        $response = Http::withHeaders([
            'X-Merchant-Id' => env('IZIPAY_MERCHANT_ID'),
            'X-Api-Key' => env('IZIPAY_API_KEY'),
        ])->post($url, $payload);

        // Manejar la respuesta del API de Izipay
        if ($response->successful()) {
            //guardar datos de Izipay
            $responseData = $response->json();
            $operationCode = $responseData['codigoOperacion'];
            $operationDate = $responseData['fecha'];
            $newOperationDate = date("Y-m-d H:i:s", strtotime($operationDate));

            //actualizar db vouchers
            $orderRaw = Order::leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
                ->select('vouchers.id AS voucher_id', 'vouchers.charge_code', 'vouchers.document', 'vouchers.client', 'vouchers.phone', 'vouchers.email', 'vouchers.subtotal', 'vouchers.total', 'vouchers.vat')
                ->find($orderId);

            $order_ic = Voucher::find($orderRaw->voucher_id);
            $order_ic->operation_code = $operationDate;
            $order_ic->operation_date = $newOperationDate;
            $order_ic->save();


            /*
            Respuesta IZIPAY
            {
                "status": "success",
                "codigoOperacion": "CODIGO_DE_OPERACION",
                "monto": 100.0,
                "descripcion": "Descripción de la venta",
                "fecha": "2023-07-13T12:34:56",
                "otraInformacion": "Información adicional"
            }*/


            $response['code'] = 200;
            $response['status'] = 'success';
            $response['message'] = 'Se realizo el pago correctamente';
        } else {
            $errorResponse = $response->json();

            $errorCode = $errorResponse['errorCode'];
            $errorMessage = $errorResponse['errorMessage'];
            $response = ['code' => 400, 'status' => 'error', 'message' =>  $errorCode . ' - ' . $errorMessage];
        }
    }

    public function sendEmail($order, $orderDetails)
    {
        $voucherInfo = Voucher::select('subtotal', 'total')->where('order_id', '333')->first();

        $order['total'] = $voucherInfo->total;
        $order['subtotal'] = $voucherInfo->subtotal;

        $data = array(
            'email' => 'luquemichael.92@gmail.com',
            'subject' => 'Tu resomen de compra',
            'emailTo' => 'luquemichael.92@gmail.com',
            'order' => $order,
            'orderDetails' => $orderDetails
        );

        //Mail::to($userEmail)->send(new VoucherEmail($params));
        Mail::send('emails.voucher', $data, function ($message) use ($data) {
            $message->from($data['email']);
            $message->to($data['emailTo']);
            $message->subject($data['subject']);
        });
    }

    public function getChargeCode(Request $request)
    {
        $vouchers = Voucher::select('charge_code', 'charge_code')->where('charge_code', 'LIKE', '%' . $request->search . '%')->get();

        return response()->json($vouchers);
    }
}

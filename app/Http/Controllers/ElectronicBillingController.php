<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Voucher;
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
use DateTime;
use Illuminate\Support\Facades\Http;
use Greenter\Model\Sale\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ElectronicBillingController extends Controller
{

    function index(Request $request)
    {
        if ($request->ajax()) {
            #$data = Order::select('*');
            $data = Order::select(
                'orders.*',
                'vouchers.path_pdf',
                'vouchers.data_path_note_credit',
                'charge_code_note_credit',
                'payment_methods.payment_method as payment_method',
                'voucher_types.name as voucher_type',
                'vouchers.is_note_credit',
                DB::raw('CASE WHEN vouchers.is_note_credit = 1 THEN "NOTA DE CRÉDITO" ELSE voucher_types.name END as voucher_type'),
                'vouchers.client as person'

            )
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'orders.payment_method_id')
                ->leftJoin('voucher_types', 'voucher_types.id', '=', 'orders.form_method_id')
                ->leftJoin('people', 'people.id', '=', 'orders.person_id')
                ->leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
                ->where('orders.status_id', '=', 1)
                ->where('orders.type_order', '=', 1)
                ->get();



            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('monto', function ($row) {
                    $dato = '<span class="text-success">' . number_format($row->amount, 2) . ' S/.</span>';
                    if ($row->status_sunat == 0) {
                        $dato = '<span class="text-danger">' . number_format(($row->amount * -1), 2) . ' S/.</span>';
                    } else if ($row->is_note_credit == 1) {
                        $dato = '<span class="text-danger">' . number_format(($row->amount * -1), 2) . ' S/.</span>';
                    }
                    return $dato;
                })
                ->addColumn('estado', function ($row) {
                    $html = '<span class="badge text-bg-success text-white">ENVIADO</span>';
                    if ($row->status_sunat == 0) {
                        $html = '<span class="badge text-bg-light">ANULADO</span>';
                    } else if ($row->status_id == 0 && $row->form_method_id == 3) {
                        $html = '<span class="badge text-bg-light">ANULADO</span>';
                    }else if ($row->status_sunat == 3 && $row->status_id == 1) {
                        $html = '<span class="badge text-bg-danger text-white">RECHAZADO POR SUNAT</span>';
                    }
                    return $html;
                })
                ->addColumn('details', function ($row) {

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



                    $actionBtn = '<a href="javascript:void(0)" class="viewDetail mx-2 btn btn-light rounded-pill btn-sm" data-order="' . $row->id . '" data-coreui-toggle="modal" data-coreui-target="#modal-details"><i class="las la-eye"></i></a>';
                    return '<div class="d-flex flex-row">' . $actionBtn . $html . '</div>';
                })
                ->rawColumns(['action', 'details', 'sunat', 'estado', 'monto'])
                ->make(true);
        }

        return view('admin.income.index_income');
    }

    function index_boleta()
    {
        return view('admin.income.index_boleta');
    }

    function proccess_boleta(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        $documento = $request->dni;
        $fecha = $request->fecha;

        $total = $request->total;
        $subtotal = number_format(($total / 1.18), 2, '.', '.');
        $igv = $total - $subtotal;

        $response = ['code' => 400, 'status' => 'error', 'message' => ''];

        if (!Auth::check()) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Su sesión ha expirado, por favor inicie sesión nuevamente']);
        }

        $user_id = Auth::id();

        $data = [
            'person_id' => 0,
            'user_id' => $user_id,
            'client' => $nombre,
            'company' => '',
            'document' => $documento,
            'document_type' => 1,
            'date_order' => $fecha,
            'amount' => $total,
            'form_method_id' => 1,
            'payment_method_id' => 3,
            'type_order' => 1
        ];

        $order = Order::create($data);

        $sunat = new SunatController();
        $see = $sunat->connect();

        $client = new Client();
        $client->setTipoDoc(1)
            ->setNumDoc($documento)
            ->setRznSocial($nombre);

        $address = new Address();
        $address->setUbigueo('150108')
            ->setDistrito('CHORRILLOS')
            ->setProvincia('LIMA')
            ->setDepartamento('LIMA')
            ->setUrbanizacion('SANTA LEONOR')
            ->setCodLocal('0000')
            ->setDireccion('JR. LIZANDRO DE LA PUENTE NRO. 561');

        $company = new Company();
        $company->setRuc('10098282462')
            ->setNombreComercial('LA CAFETERÍ@')
            ->setRazonSocial('MILLA MARTINEZ IGNACIO PABLO')
            ->setAddress($address)
            ->setEmail('lacafeteriacec@gmail.com')
            ->setTelephone('993745873');

        $invoice = new Invoice();
        $invoice
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc('03')
            ->setSerie('B008')
            ->setCorrelativo($order->id)
            ->setFechaEmision(new DateTime($fecha))
            ->setTipoMoneda('PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($subtotal) // Monto gravado
            ->setMtoIGV($igv) // Monto del IGV
            ->setTotalImpuestos($igv) // Total de impuestos (IGV)
            ->setValorVenta($subtotal) // Valor de venta (monto gravado)
            ->setSubTotal($total) // Subtotal (total gravado + IGV)
            ->setMtoImpVenta($total); // Monto total de la venta (total de la boleta)

        $items = [];
        $productosData = json_decode($request->input('productos'));

        foreach ($productosData as $data) {
            $nombreProducto = $data->nombre_producto;
            $cantidad = $data->cantidad;
            $precioUnitario = $data->precio_unitario;
            //$subtotal = $data->subtotal;

            $detail_amount = number_format($precioUnitario * $cantidad, 2, '.', '.');
            $detail_subtotal = number_format(($detail_amount / 1.18), 2, '.', '.');
            $detail_igv = $detail_amount - $detail_subtotal;
            $amount_unity_value = number_format(($precioUnitario / 1.18), 2, '.', '.');

            $items[] = (new SaleDetail())
                ->setCodProducto('')
                ->setDescripcion($nombreProducto)
                ->setUnidad('NIU') // Unidad de medida - Catalog. 03
                ->setCantidad($cantidad)
                ->setMtoValorUnitario($amount_unity_value) // Precio unitario por unidad (sin IGV)
                ->setMtoBaseIgv($detail_subtotal) // Monto base para IGV (sin IGV)
                ->setPorcentajeIgv(18.00) // Porcentaje de IGV (18%)
                ->setIgv($detail_igv) // Monto del IGV (IGV calculado)
                ->setTipAfeIgv('10') // Gravado - Catalog. 07
                ->setTotalImpuestos($detail_igv) // Total de impuestos (IGV)
                ->setMtoValorVenta($detail_subtotal) // Monto gravado (sin IGV)
                ->setMtoPrecioUnitario($precioUnitario); // Precio unitario con IGV (con IGV)

            $dataDetail = [
                'order_id' => $order->id,
                'product_id' => 0,
                'description_income' => $nombreProducto,
                'quantity' => $cantidad,
                'subtotal' => $detail_subtotal,
                'total' => $detail_amount
            ];

            if (OrderDetail::create($dataDetail)) {
            }
        }

        $formatter = new NumeroALetras();

        $valorTotalLetras = $formatter->toWords($total) . ' CON 00/100 SOLES';

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue($valorTotalLetras);

        $invoice->setDetails($items)
            ->setLegends([$legend]);

        $result = $see->send($invoice);
        $fileDir = storage_path('files');

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        file_put_contents($fileDir . '/' . $invoice->getName() . '.xml', $see->getFactory()->getLastXml());

        if (!$result->isSuccess()) {
            $response = ['code' => 400, 'status' => 'error', 'message' => $result->getError()->getCode() . '-' . $result->getError()->getMessage()];
        } else {

            try {
                file_put_contents($fileDir . '/' . $invoice->getName() . '.zip', $result->getCdrZip());

                $util = SunatController::getInstance();
                $pdf = $util->getPdf($invoice);
                $util->showPdf($pdf, $invoice->getName() . '.pdf');

                $order->status_id = 1;
                $order->status_sunat = 1;
                $order->save();

                $response['code'] = 200;
                $response['status'] = 'success';
                $response['message'] = 'Se agrego correctamente';

                $order_ic = Order::find($order->id);
                $order_ic->internal_code = $order->id;
                $order_ic->save();

                $data = [
                    'voucher_type_id' => 1,
                    'order_id' => $order->id,
                    'charge_code' => $invoice->getSerie() . '-' . $invoice->getCorrelativo(),
                    'document' => $documento,
                    'client' => $nombre,
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
            } catch (\Exception $e) {
                $response = ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return response()->json($response);
    }

    function index_factura()
    {
        return view('admin.income.index_factura');
    }

    function proccess_factura(Request $request)
    {
        $nombre = strtoupper($request->nombre);
        $documento = $request->ruc;
        $fecha = $request->fecha;
        $tipoFactura = $request->tipoFactura;
        $fechaInicio = new DateTime($request->fechaInicio);
        $fechaFin = new DateTime($request->fechaFin);
        $plazoDias = $fechaInicio->diff($fechaFin)->days;
        $fechaVencimiento = $fechaFin;

        $total = $request->total;
        $subtotal = number_format(($total / 1.18), 2, '.', '.');
        $igv = $total - $subtotal;

        if ($tipoFactura == 1) {
            $formaPago = new FormaPagoContado();
        } else {
            $formaPago = new FormaPagoCredito($total);
        }

        $response = ['code' => 400, 'status' => 'error', 'message' => ''];

        if (!Auth::check()) {
            return response()->json(['code' => 400, 'status' => 'error', 'message' => 'Su sesión ha expirado, por favor inicie sesión nuevamente']);
        }

        $user_id = Auth::id();

        $data = [
            'person_id' => 0,
            'user_id' => $user_id,
            'client' => $nombre,
            'company' => '',
            'document' => $documento,
            'document_type' => 1,
            'date_order' => $fecha,
            'amount' => $total,
            'form_method_id' => 2,
            'payment_method_id' => 3,
            'type_order' => 1
        ];

        $order = Order::create($data);

        $sunat = new SunatController();
        $see = $sunat->connect();

        $client = new Client();
        $client->setTipoDoc(6)
            ->setNumDoc($documento)
            ->setRznSocial($nombre);

        $address = new Address();
        $address->setUbigueo('150108')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('CHORRILLOS')
            ->setUrbanizacion('SANTA LEONOR')
            ->setDireccion('JR. LIZANDRO DE LA PUENTE NRO. 561')
            ->setCodLocal('0000');

        $company = new Company();
        $company->setRuc('10098282462')
            ->setNombreComercial('LA CAFETERÍ@')
            ->setRazonSocial('MILLA MARTINEZ IGNACIO PABLO')
            ->setAddress($address)
            ->setEmail('lacafeteriacec@gmail.com')
            ->setTelephone('993745873');

        // Crear una instancia de Invoice

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta - Catalog. 51
            ->setTipoDoc('01') // Factura - Catalog. 01
            ->setSerie('F009')
            ->setCorrelativo($order->id)
            ->setFechaEmision(new DateTime($fecha))
            ->setFormaPago($formaPago) // FormaPago: Contado
            ->setTipoMoneda('PEN') // Sol - Catalog. 02
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($subtotal) // Monto gravado
            ->setMtoIGV($igv) // Monto del IGV
            ->setTotalImpuestos($igv) // Total de impuestos (IGV)
            ->setValorVenta($subtotal) // Valor de venta (monto gravado)
            ->setSubTotal($total) // Subtotal (total gravado + IGV)
            ->setMtoImpVenta($total); // Monto total de la venta (total de la factura)

        if ($tipoFactura == 2) {
            $invoice->setCuotas([
                (new Cuota())
                    ->setMonto($total)
                    ->setFechaPago(new DateTime('+' . $plazoDias . 'days'))
            ])
                ->setFecVencimiento($fechaVencimiento);
        }


        $items = [];
        $productosData = json_decode($request->input('productos'));

        foreach ($productosData as $data) {
            $nombreProducto = $data->nombre_producto;
            $cantidad = $data->cantidad;
            $precioUnitario = $data->precio_unitario;
            //$subtotal = $data->subtotal;

            $detail_amount = number_format($precioUnitario * $cantidad, 2, '.', '.');
            $detail_subtotal = number_format(($detail_amount / 1.18), 2, '.', '.');
            $detail_igv = $detail_amount - $detail_subtotal;
            $amount_unity_value = number_format(($precioUnitario / 1.18), 2, '.', '.');

            $items[] = (new SaleDetail())
                ->setCodProducto('')
                ->setDescripcion($nombreProducto)
                ->setUnidad('NIU') // Unidad de medida - Catalog. 03
                ->setCantidad($cantidad)
                ->setMtoValorUnitario($amount_unity_value) // Precio unitario por unidad (sin IGV)
                ->setMtoBaseIgv($detail_subtotal) // Monto base para IGV (sin IGV)
                ->setPorcentajeIgv(18.00) // Porcentaje de IGV (18%)
                ->setIgv($detail_igv) // Monto del IGV (IGV calculado)
                ->setTipAfeIgv('10') // Gravado - Catalog. 07
                ->setTotalImpuestos($detail_igv) // Total de impuestos (IGV)
                ->setMtoValorVenta($detail_subtotal) // Monto gravado (sin IGV)
                ->setMtoPrecioUnitario($precioUnitario); // Precio unitario con IGV (con IGV)

            $dataDetail = [
                'order_id' => $order->id,
                'product_id' => 0,
                'description_income' => $nombreProducto,
                'quantity' => $cantidad,
                'subtotal' => $detail_subtotal,
                'total' => $detail_amount
            ];

            if (OrderDetail::create($dataDetail)) {
            }
        }
        $formatter = new NumeroALetras();

        $valorTotalLetras = $formatter->toWords(100) . ' CON 00/100 SOLES';

        $legend = (new Legend())
            ->setCode('1000')
            ->setValue($valorTotalLetras);

        $invoice->setDetails($items)
            ->setLegends([$legend]);

        $result = $see->send($invoice);
        $fileDir = storage_path('files');

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        file_put_contents($fileDir . '/' . $invoice->getName() . '.xml', $see->getFactory()->getLastXml());

        if (!$result->isSuccess()) {
            $response = ['code' => 400, 'status' => 'error', 'message' => $result->getError()->getCode() . '-' . $result->getError()->getMessage()];
        } else {

            try {
                file_put_contents($fileDir . '/' . $invoice->getName() . '.zip', $result->getCdrZip());

                $util = SunatController::getInstance();
                $pdf = $util->getPdf($invoice);
                $util->showPdf($pdf, $invoice->getName() . '.pdf');

                $order->status_id = 1;
                $order->status_sunat = 1;
                $order->save();

                $response['code'] = 200;
                $response['status'] = 'success';
                $response['message'] = 'Se agrego correctamente';

                $order_ic = Order::find($order->id);
                $order_ic->internal_code = $order->id;
                $order_ic->save();

                $data = [
                    'voucher_type_id' => 2,
                    'order_id' => $order->id,
                    'charge_code' => $invoice->getSerie() . '-' . $invoice->getCorrelativo(),
                    'document' => $documento,
                    'client' => $nombre,
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
            } catch (\Exception $e) {
                $response = ['code' => 400, 'status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return response()->json($response);
    }

    function index_notacredito()
    {
        return view('admin.income.index_notacredito');
    }

    function proccess_notacredito(Request $request)
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
        $note = new Note();
        $note
            ->setUblVersion('2.1')
            ->setTipoDoc('07')
            ->setSerie($serieCorrelativoArr[0])
            ->setCorrelativo($serieCorrelativoArr[1])
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

            $price = number_format($item->total/$item->quantity, 2, '.', '.');
            $cod_product = '';


            $detail_amount = number_format($price * $item->quantity, 2, '.', '.');
            $detail_subtotal = number_format(($detail_amount / 1.18), 2, '.', '.');
            $detail_igv = $detail_amount - $detail_subtotal;
            $amount_unity_value = number_format(($price / 1.18), 2, '.', '.');

            $items[] = (new SaleDetail())
                ->setCodProducto($cod_product)
                ->setUnidad('NIU') // Unidad - Catalog. 03
                ->setCantidad($item->quantity)
                ->setDescripcion($item->description_income)
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
                'is_note_credit'=>1

            ];

            Voucher::create($data);

            $response['code'] = 200;
            $response['status'] = 'success';
            $response['message'] = 'Se agrego correctamente';
        }



        return response()->json($response);
    }
}

<html>

<head>
    <META http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
    <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" style="max-width:100%;background:#e9e9e9;padding:50px 0px">
        <tr>
            <td>
                <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;background:#ffffff;padding:0px 25px">
                    <tbody>
                        <tr>
                            <td style="margin:0;padding:0">
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" style="background:#ffffff;color:#1a1a1a;line-height:150%;text-align:center;border-bottom:1px solid #e9e9e9;font-family:300 14px &#39;Helvetica Neue&#39;,Helvetica,Arial,sans-serif">
                                    <tbody>
                                        <tr>
                                            <td valign="top" align="center" width="100" style="background-color:#ffffff">
                                                <img alt="Swiggy" style="width:134px" src="https://i.ibb.co/zPdryGV/logo.png">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <br>

                                <table border="0" cellpadding="" cellspacing="0" width="100%" style="background:#ffffff;color:#000000;line-height:150%;text-align:center;font:300 16px &#39;Helvetica Neue&#39;,Helvetica,Arial,sans-serif">
                                    <tbody>
                                        <tr>
                                            <td valign="top" width="100">
                                                <h3 style="text-align:center;text-transform:uppercase">LaCafeteria Mapapa</h3>
                                                <p>Tipo de Pago: <span style="font-size:18px;font-weight:bold"></span></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <table border="0" cellpadding="20" cellspacing="0" width="100%" style="color:#000000;line-height:150%;text-align:left;font:300 16px &#39;Helvetica Neue&#39;,Helvetica,Arial,sans-serif">
                                    <tbody>
                                        <tr>
                                            <td valign="top" style="font-size:24px;">
                                                <span style="text-decoration:underline;">Pedido No: #</span> {{ $order->internal_code }}
                                                <h2 style="display:inline-block;font-family:Arial;font-size:24px;font-weight:bold;margin-top:5px;margin-right:0;margin-bottom:5px;margin-left:0;text-align:left;line-height:100%">
                                                @php
                                                    echo date("Y/m/d", strtotime($order->created_at))
                                                @endphp
                                            </h2>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table align="center" cellspacing="0" cellpadding="6" width="95%" style="border:0;color:#000000;line-height:150%;text-align:left;font:300 14px/30px &#39;Helvetica Neue&#39;,Helvetica,Arial,sans-serif;" border=".5px">
                                    <thead>
                                        <tr style="background:#efefef">
                                            <th scope="col" width="30%" style="text-align:left;border:1px solid #eee">Producto</th>
                                            <th scope="col" width="15%" style="text-align:right;border:1px solid #eee">Cantidad</th>
                                            <th scope="col" width="20%" style="text-align:right;border:1px solid #eee">Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                        @foreach ($orderDetails as $detail)
                                        <tr width="100%">
                                            <td width="30%" style="text-align:left;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0;word-wrap:break-word">
                                                {{ $detail['product'] }}
                                            </td>
                                            <td width="15%" style="text-align:right;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                                                {{ $detail['quantity'] }}
                                            </td>
                                            <td width="20%" style="text-align:right;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0">
                                                <span>{{ $detail['total'] }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th scope="row" colspan="2" style="text-align:right;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">Subtotal </th>
                                            <th style="text-align:right;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0"><span>{{ $order->subtotal }}</span></th>
                                        </tr>
                                        <tr>
                                            <th scope="row" colspan="2" style="text-align:right;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">
                                                IGV</th>
                                            <td style="text-align:right;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0"><span>18 %</span></td>
                                        </tr>

                                        <tr>
                                            <th scope="row" colspan="2" style="text-align:right;background:#efefef;text-align:right;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:0;border-top:0">Total</th>
                                            <td style="background:#efefef;text-align:right;vertical-align:middle;border-left:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;border-top:0;color:#7db701;font-weight:bold"><span>{{ $order->total }}</span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                                <br>

                                <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;padding:0px;font-size:12px;color:#9b9b9b;">
                                    <tbody>
                                        <tr>
                                            <td align="center" width="33.3333%">
                                                Calle Los Halcones 102 - Limatambo - Surquillo - Lima
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
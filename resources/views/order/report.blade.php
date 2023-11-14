<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.9/semantic.min.css">
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
            }

            /* ... the rest of the rules ... */
        }

        .bigfont {
            font-size: 3rem !important;
        }

        .invoice {
            width: 970px !important;
            margin: 50px auto;
        }

        .logo {
            float: left;
            padding-right: 10px;
            margin: 10px auto;
        }

        dt {
            float: left;
        }

        dd {
            float: left;
            clear: right;
        }

        .customercard {
            min-width: 65%;
        }

        .itemscard {
            min-width: 98.5%;
            margin-left: 0.5%;
        }

        .logo {
            max-width: 5rem;
            margin-top: -0.25rem;
        }

        .invDetails {
            margin-top: 0rem;
        }

        .pageTitle {
            margin-bottom: -1rem;
        }

        .content-100 {
            width: 100% !important;
        }

        .c-border {
            border: 2px solid #622542 !important;
            border-radius: 7px !important;
            box-shadow: none !important;
        }

        .no-border {
            border: none !important;
            box-shadow: none !important;
        }

        .ui.table thead th {
            color: #fff !important;
            font-weight: bolder !important;
            background-color: #622542 !important;
        }

        .ui.table tfoot th {
            background: #fff !important;
        }

        .item.flex {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
        }

        .item.flex span {
            order: 1;
        }
        .title{
            color: #622542;
            font-weight: bolder;
            
        }
        .factu{
            background: #622542;
            color: #fff;
            padding: 14px 0 !important;
            font-weight: bolder;
            letter-spacing: 1.5px;
        }
        .ui.card>.content p, .ui.cards>.card>.content p {
            padding: 10px 0;
            margin: 0;
        }
        @media print {
            .factu {                
                background: #622542;
            }
        }
    </style>
</head>

<body>
    <div class="container invoice">
        
        <div class="ui cards">
            <div class="ui card customercard no-border" style="margin-left: 0;">
                <div class="ui grid">
                    <div class="six wide column">
                        <img width="130px" src="logo_cafe.png" alt="">
                    </div>
                    <div class="ten wide column">
                        <div class="content" style="text-align: center;font-size: 12px;line-height: 14px;">
                            <h1 class="title">LA CAFETERÍ@</h1>
                            DE: MILLA MARTINEZ IGNACIO PABLO<br/>
                            JR.LIZANDRO DE LA PUENTE NRO.561 URB.SANTA LEONOR<br/>
                            CHORRILLOS-LIMA-LIMA-PERU<br/>
                            Cel.993 745 873<br/>
                            Email: lacafeteriac@gmail.com<br/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui card c-border" style="margin-right: 0; text-align: center;">
                <div class="content" style="padding: 0;">                    
                    <p> RUC: 10001202 </p>
                    <p class="factu"> FACTURA ELECTRONICA </p>
                    <p> F001-110 </p>
                </div>
            </div>


            <div class="ui card customercard c-border" style="margin-left: 0;">
                <div class="content">
                    <div class="ui list">
                        <div class="item"> <strong> RAZON SOCIAL: RCJA </strong> </div>
                        <div class="item"><strong> RUC: </strong> 1 Unknown Street VIC</div>
                        <div class="item"><strong> DIRECCION: </strong> (+61)404123123</div>
                    </div>
                </div>
            </div>
            <div class="ui card c-border" style="margin-right: 0;">
                <div class="content">
                    <div class="ui list">
                        <div class="item"> <strong> FEC.EMISION: RCJA </strong> </div>
                        <div class="item"><strong> MONEDA: </strong> 1 Unknown Street VIC</div>
                        <div class="item"><strong> COND.PAGO: </strong> (+61)404123123</div>
                        <div class="item"><strong> ORD.COMRPA: </strong> admin@rcja.com</div>
                    </div>
                </div>
            </div>
            <div class="ui segment itemscard c-border">
                <div class="content">
                    <div class="ui equal width grid">
                        <div class="column"><strong> CUOTA: </strong> RCJA</div>
                        <div class="column"><strong> FECHA VENCIMIENTO: </strong> 1 Unknown Street VIC</div>
                        <div class="column"><strong> TOT.CUOTA: </strong> (+61)404123123</div>
                    </div>
                </div>
            </div>

            <div class="ui segment itemscard c-border p-0" style="padding: 0;">
                <div class="content">
                    <table class="ui celled table no-border">
                        <thead>
                            <tr>
                                <th>DESCRIPCION</th>
                                <th class="text-center colfix">U.M.</th>
                                <th class="text-center colfix">CANTIDAD</th>
                                <th class="text-center colfix">PRECIO</th>
                                <th class="text-center colfix">IMPORTE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Lorem Ipsum Dolor
                                </td>
                                <td class="text-right">
                                    <span class="mono">$1,000.00</span>
                                </td>
                                <td class="text-right">
                                    <span class="mono">$18,000.00</span>
                                </td>
                                <td class="text-right">
                                    <span class="mono">- $1,800.00</span>
                                </td>
                                <td class="text-right">
                                    <span class="mono">+ $3,240.00</span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Sit Amet Dolo
                                </td>
                                <td class="text-right">
                                    <span class="mono">$120.00</span>
                                </td>
                                <td class="text-right">
                                    <span class="mono">$240.00</span>
                                </td>
                                <td class="text-right">
                                    <span class="mono">- $0.00</span>
                                </td>
                                <td class="text-right">
                                    <span class="mono">+ $72.00</span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="full-width">
                            <tr>
                                <th colspan="3"></th>
                                <th colspan="2">
                                    <div class="ui list">
                                        <div class="item flex"><strong>SUB TOTAL</strong> <span>$500</span> </div>
                                        <div class="item flex"><strong>DSCTO</strong> <span>$800</span> </div>
                                        <div class="item flex"><strong>OP GRAVADA</strong> <span>$800</span> </div>
                                        <div class="item flex"><strong>OP EXONERADA</strong> <span>$800</span> </div>
                                        <div class="item flex"><strong>OP INAFECTA</strong> <span>$800</span> </div>
                                        <div class="item flex"><strong>EXPORTACION</strong> <span>$800</span> </div>
                                        <div class="item flex"><strong>OP GRATUITA</strong> <span>$800</span> </div>
                                        <div class="item flex"><strong>IGV</strong> <span>$800</span> </div>
                                        <div class="item flex"><strong>TOTAL</strong> <span>$800</span> </div>
                                    </div>
                                </th>

                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>

            <div class="ui segment itemscard c-border">
                <div class="content">
                    <strong> IMPORTE EN LETRAS: </strong> RCJA
                </div>
            </div>

            <div class="ui card c-border">
                <div class="content center aligned text segment">
                    <p class="bigfont"> QR CODE </p>
                </div>
            </div>
            <div class="ui card c-border">
                <div class="content">
                    <p> <strong> Account Name: </strong> "RJCA" </p>
                    <p> <strong> BSB: </strong> 111-111 </p>
                    <p> <strong>Account Number: </strong> 1234101 </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
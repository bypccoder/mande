<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* invoice2.html.twig */
class __TwigTemplate_d32f42f96dc3cb8837bc76c39697742ce3f892b6423f3425f4b9a81cd20949f7 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"es\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
    <title>Invoice</title>
    <style type=\"text/css\">
        .clearfix:after {
            content: \"\";
            display: table;
            clear: both;
        }
        body {
            position: relative;
            width: 100%;
            height: 29.7cm;
            margin: 0;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }
        #logo {
            float: left;
            margin-top: 8px;
        }
        #logo img {
            height: 120px;
        }
        #company {
            float: right;
            text-align: right;
        }
        #details {
            margin-bottom: 50px;
        }
        #client {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
            float: left;
        }
        #qrcode {
            margin-bottom: 20px;
            float: right;
        }
        #client .to {
            color: #777777;
        }
        h2.ruc {
            font-size: 1.2em;
            font-weight: normal;
            margin: 0;
        }
        #invoice {
            float: right;
            text-align: right;
        }
        #invoice h1 {
            color: #0087C3;
            font-size: 2em;
            line-height: 1em;
            font-weight: normal;
            margin: 0  0 10px 0;
        }
        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }
        table th,
        table td {
            padding: 20px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }
        table th {
            white-space: nowrap;
            font-weight: normal;
        }
        table td {
            text-align: right;
        }
        table td h3{
            color: #6D2142;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }
        table .no {
            color: #FFFFFF;
            font-size: 1.2em;
            text-align: center;
            background: #6D2142;
        }
        table .desc {
            text-align: left;
            width: 50%;
        }
        table .unit {
            background: #DDDDDD;
            text-align: center;
        }
        table .qty {
        }
        table .total {
            background: #6D2142;
            color: #FFFFFF;
        }
        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
            text-align: center;
        }
        table tbody tr:last-child td {
            border: none;
        }
        table tfoot td {
            padding: 10px 20px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }
        table tfoot tr:first-child td {
            border-top: none;
        }
        table tfoot tr td:last-child {
            text-align: center;
        }
        table tfoot tr:last-child td {
            color: #6D2142;
            font-size: 1.4em;
            border-top: 1px solid #6D2142;
        }
        table tfoot tr td:first-child {
            border: none;
        }
        #notices{
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }
        #notices .notice {
            font-size: 1.2em;
        }
        .name {
            font-size: 1.4em;
            margin: 0.2em 0;
        }
    </style>
</head>
<body>
<header class=\"clearfix\">
    <div id=\"logo\">
        <img src=\"";
        // line 166
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\ImageFilter')->toBase64(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["params"]) || array_key_exists("params", $context) ? $context["params"] : (function () { throw new RuntimeError('Variable "params" does not exist.', 166, $this->source); })()), "system", [], "any", false, false, false, 166), "logo", [], "any", false, false, false, 166)), "html", null, true);
        echo "\">
    </div>
    <div id=\"company\">
        ";
        // line 169
        $context["cp"] = twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 169, $this->source); })()), "company", [], "any", false, false, false, 169);
        // line 170
        echo "        <h2 style=\"margin-bottom:0\">RUC ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["cp"]) || array_key_exists("cp", $context) ? $context["cp"] : (function () { throw new RuntimeError('Variable "cp" does not exist.', 170, $this->source); })()), "ruc", [], "any", false, false, false, 170), "html", null, true);
        echo "</h2>
        <h3 style=\"margin:0.2em 0;font-size:1.3em\">";
        // line 171
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["cp"]) || array_key_exists("cp", $context) ? $context["cp"] : (function () { throw new RuntimeError('Variable "cp" does not exist.', 171, $this->source); })()), "razonSocial", [], "any", false, false, false, 171), "html", null, true);
        echo "</h3>
        <div>";
        // line 172
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["cp"]) || array_key_exists("cp", $context) ? $context["cp"] : (function () { throw new RuntimeError('Variable "cp" does not exist.', 172, $this->source); })()), "address", [], "any", false, false, false, 172), "direccion", [], "any", false, false, false, 172), "html", null, true);
        echo "</div>
        <div>Cel: 993 745 873</div>
    </div>
</header>
<main>
    <div id=\"details\" class=\"clearfix\">
        <div id=\"client\" style=\"max-width: 60%\">
            <div class=\"to\">EMITIDO A:</div>
            ";
        // line 180
        $context["cl"] = twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 180, $this->source); })()), "client", [], "any", false, false, false, 180);
        // line 181
        echo "            <h2 class=\"ruc\"><b>";
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog(twig_get_attribute($this->env, $this->source, (isset($context["cl"]) || array_key_exists("cl", $context) ? $context["cl"] : (function () { throw new RuntimeError('Variable "cl" does not exist.', 181, $this->source); })()), "tipoDoc", [], "any", false, false, false, 181), "06"), "html", null, true);
        echo "</b> ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["cl"]) || array_key_exists("cl", $context) ? $context["cl"] : (function () { throw new RuntimeError('Variable "cl" does not exist.', 181, $this->source); })()), "numDoc", [], "any", false, false, false, 181), "html", null, true);
        echo "</h2>
            <span class=\"name\">";
        // line 182
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["cl"]) || array_key_exists("cl", $context) ? $context["cl"] : (function () { throw new RuntimeError('Variable "cl" does not exist.', 182, $this->source); })()), "rznSocial", [], "any", false, false, false, 182), "html", null, true);
        echo "</span>
            ";
        // line 183
        if (twig_get_attribute($this->env, $this->source, (isset($context["cl"]) || array_key_exists("cl", $context) ? $context["cl"] : (function () { throw new RuntimeError('Variable "cl" does not exist.', 183, $this->source); })()), "address", [], "any", false, false, false, 183)) {
            echo "<div class=\"address\"><i>";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["cl"]) || array_key_exists("cl", $context) ? $context["cl"] : (function () { throw new RuntimeError('Variable "cl" does not exist.', 183, $this->source); })()), "address", [], "any", false, false, false, 183), "direccion", [], "any", false, false, false, 183), "html", null, true);
            echo "</i></div>";
        }
        // line 184
        echo "        </div>
        <div id=\"invoice\">
            ";
        // line 186
        $context["serieNro"] = ((twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 186, $this->source); })()), "serie", [], "any", false, false, false, 186) . "-") . twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 186, $this->source); })()), "correlativo", [], "any", false, false, false, 186));
        // line 187
        echo "            <h1>";
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 187, $this->source); })()), "tipoDoc", [], "any", false, false, false, 187), "01"), "html", null, true);
        echo " ELECTRÓNICA</h1>
            <h1>";
        // line 188
        echo twig_escape_filter($this->env, (isset($context["serieNro"]) || array_key_exists("serieNro", $context) ? $context["serieNro"] : (function () { throw new RuntimeError('Variable "serieNro" does not exist.', 188, $this->source); })()), "html", null, true);
        echo "</h1>
            <div class=\"date\">EMITIDO: ";
        // line 189
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 189, $this->source); })()), "fechaEmision", [], "any", false, false, false, 189), "d/m/Y"), "html", null, true);
        echo "</div>
        </div>
    </div>
    <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
        <thead>
        <tr>
            <th class=\"no\">CANT.</th>
            <th class=\"desc\">DESCRIPCION</th>
            <th class=\"unit\">UNIDAD</th>
            <th class=\"qty\">PRECIO</th>
            <th class=\"total\">IMPORTE</th>
        </tr>
        </thead>
        <tbody>
        ";
        // line 203
        $context["moneda"] = $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 203, $this->source); })()), "tipoMoneda", [], "any", false, false, false, 203), "02");
        // line 204
        echo "        ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 204, $this->source); })()), "details", [], "any", false, false, false, 204));
        foreach ($context['_seq'] as $context["_key"] => $context["det"]) {
            // line 205
            echo "            <tr>
                <td class=\"no\">";
            // line 206
            echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, $context["det"], "cantidad", [], "any", false, false, false, 206)), "html", null, true);
            echo "</td>
                <td class=\"desc\">
                    <h3>";
            // line 208
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["det"], "codProducto", [], "any", false, false, false, 208), "html", null, true);
            echo "</h3>
                    ";
            // line 209
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["det"], "descripcion", [], "any", false, false, false, 209), "html", null, true);
            echo "
                </td>
                <td class=\"unit\">";
            // line 211
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["det"], "unidad", [], "any", false, false, false, 211), "html", null, true);
            echo "</td>
                <td class=\"qty\">";
            // line 212
            echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 212, $this->source); })()), "html", null, true);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["det"], "mtoValorUnitario", [], "any", false, false, false, 212), "html", null, true);
            echo "</td>
                <td class=\"total\">";
            // line 213
            echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 213, $this->source); })()), "html", null, true);
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["det"], "mtoValorVenta", [], "any", false, false, false, 213), "html", null, true);
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['det'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 216
        echo "        </tbody>
        <tfoot>
        <tr>
            <td colspan=\"2\"></td>
            <td colspan=\"2\">Descuento</td>
            <td>";
        // line 221
        echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 221, $this->source); })()), "html", null, true);
        echo " 0</td>
        </tr>
        <tr>
            <td colspan=\"2\"></td>
            <td colspan=\"2\">Op. Gravadas</td>
            <td>";
        // line 226
        echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 226, $this->source); })()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 226, $this->source); })()), "mtoOperGravadas", [], "any", false, false, false, 226)), "html", null, true);
        echo "</td>
        </tr>
        ";
        // line 228
        if (twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 228, $this->source); })()), "mtoOperInafectas", [], "any", false, false, false, 228)) {
            // line 229
            echo "            <tr>
                <td colspan=\"2\"></td>
                <td colspan=\"2\">Op. Inafectas</td>
                <td>";
            // line 232
            echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 232, $this->source); })()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 232, $this->source); })()), "mtoOperInafectas", [], "any", false, false, false, 232)), "html", null, true);
            echo "</td>
            </tr>
        ";
        }
        // line 235
        echo "        <tr>
            <td colspan=\"2\"></td>
            <td colspan=\"2\">Exportación</td>
            <td>";
        // line 238
        echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 238, $this->source); })()), "html", null, true);
        echo " 0</td>
        </tr>
        ";
        // line 240
        if (twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 240, $this->source); })()), "mtoOperExoneradas", [], "any", false, false, false, 240)) {
            // line 241
            echo "            <tr>
                <td colspan=\"2\"></td>
                <td colspan=\"2\">Op. Exoneradas</td>
                <td>";
            // line 244
            echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 244, $this->source); })()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 244, $this->source); })()), "mtoOperExoneradas", [], "any", false, false, false, 244)), "html", null, true);
            echo "</td>
            </tr>
        ";
        }
        // line 247
        echo "        ";
        if (twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 247, $this->source); })()), "mtoOperGratuitas", [], "any", false, false, false, 247)) {
            // line 248
            echo "            <tr>
                <td colspan=\"2\"></td>
                <td colspan=\"2\">Op. Gratuitas</td>
                <td>";
            // line 251
            echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 251, $this->source); })()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 251, $this->source); })()), "mtoOperGratuitas", [], "any", false, false, false, 251)), "html", null, true);
            echo "</td>
            </tr>
        ";
        }
        // line 254
        echo "        <tr>
            <td colspan=\"2\"></td>
            <td colspan=\"2\">IGV (18%)</td>
            <td>";
        // line 257
        echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 257, $this->source); })()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 257, $this->source); })()), "mtoIGV", [], "any", false, false, false, 257)), "html", null, true);
        echo "</td>
        </tr>
        ";
        // line 259
        if (twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 259, $this->source); })()), "mtoISC", [], "any", false, false, false, 259)) {
            // line 260
            echo "            <tr>
                <td colspan=\"2\"></td>
                <td colspan=\"2\">ISC</td>
                <td>";
            // line 263
            echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 263, $this->source); })()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 263, $this->source); })()), "mtoISC", [], "any", false, false, false, 263)), "html", null, true);
            echo "</td>
            </tr>
        ";
        }
        // line 266
        echo "        ";
        if (twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 266, $this->source); })()), "mtoOtrosTributos", [], "any", false, false, false, 266)) {
            // line 267
            echo "            <tr>
                <td colspan=\"2\"></td>
                <td colspan=\"2\">Otros Tributos</td>
                <td>";
            // line 270
            echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 270, $this->source); })()), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 270, $this->source); })()), "mtoOtrosTributos", [], "any", false, false, false, 270)), "html", null, true);
            echo "</td>
            </tr>
        ";
        }
        // line 273
        echo "        <tr>
            <td colspan=\"2\"></td>
            <td colspan=\"2\">TOTAL</td>
            <td>";
        // line 276
        echo twig_escape_filter($this->env, (isset($context["moneda"]) || array_key_exists("moneda", $context) ? $context["moneda"] : (function () { throw new RuntimeError('Variable "moneda" does not exist.', 276, $this->source); })()), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\FormatFilter')->number(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 276, $this->source); })()), "mtoImpVenta", [], "any", false, false, false, 276)), "html", null, true);
        echo "</td>
        </tr>
        </tfoot>
    </table>
    <hr>
    <div style=\"margin: 20px 0;width: 100%;text-align: center\">
        <span>
            <b>IMPORTE EN LETRAS</b> ";
        // line 283
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\ResolveFilter')->getValueLegend(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 283, $this->source); })()), "legends", [], "any", false, false, false, 283), "1000"), "html", null, true);
        echo "
        </span>
    </div>
    <div class=\"clearfix\">
        <div style=\"float: left\">
            <div id=\"notices\">
                <div class=\"notice\">
                    ";
        // line 290
        if ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["params"] ?? null), "system", [], "any", false, true, false, 290), "hash", [], "any", true, true, false, 290) && twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["params"]) || array_key_exists("params", $context) ? $context["params"] : (function () { throw new RuntimeError('Variable "params" does not exist.', 290, $this->source); })()), "system", [], "any", false, false, false, 290), "hash", [], "any", false, false, false, 290))) {
            // line 291
            echo "                        <strong>Resumen:</strong>   ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["params"]) || array_key_exists("params", $context) ? $context["params"] : (function () { throw new RuntimeError('Variable "params" does not exist.', 291, $this->source); })()), "system", [], "any", false, false, false, 291), "hash", [], "any", false, false, false, 291), "html", null, true);
            echo "<br>
                    ";
        }
        // line 293
        echo "                    Representación Impresa de la ";
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\DocumentFilter')->getValueCatalog(twig_get_attribute($this->env, $this->source, (isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 293, $this->source); })()), "tipoDoc", [], "any", false, false, false, 293), "01"), "html", null, true);
        echo " ELECTRÓNICA.
                </div>
            </div>
        </div>
        <div id=\"qrcode\">
            <img src=\"";
        // line 298
        echo twig_escape_filter($this->env, $this->env->getRuntime('Greenter\Report\Filter\ImageFilter')->toBase64($this->env->getRuntime('Greenter\Report\Render\QrRender')->getImage((isset($context["doc"]) || array_key_exists("doc", $context) ? $context["doc"] : (function () { throw new RuntimeError('Variable "doc" does not exist.', 298, $this->source); })())), "svg+xml"), "html", null, true);
        echo "\">
        </div>
    </div>
</main>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "invoice2.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  491 => 298,  482 => 293,  476 => 291,  474 => 290,  464 => 283,  452 => 276,  447 => 273,  439 => 270,  434 => 267,  431 => 266,  423 => 263,  418 => 260,  416 => 259,  409 => 257,  404 => 254,  396 => 251,  391 => 248,  388 => 247,  380 => 244,  375 => 241,  373 => 240,  368 => 238,  363 => 235,  355 => 232,  350 => 229,  348 => 228,  341 => 226,  333 => 221,  326 => 216,  316 => 213,  311 => 212,  307 => 211,  302 => 209,  298 => 208,  293 => 206,  290 => 205,  285 => 204,  283 => 203,  266 => 189,  262 => 188,  257 => 187,  255 => 186,  251 => 184,  245 => 183,  241 => 182,  234 => 181,  232 => 180,  221 => 172,  217 => 171,  212 => 170,  210 => 169,  204 => 166,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "invoice2.html.twig", "C:\\Git\\cf\\cafeteria\\vendor\\greenter\\greenter\\packages\\report\\src\\Report\\Templates\\invoice2.html.twig");
    }
}

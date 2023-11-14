<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;

class ArchivoXmlCdrController extends Controller
{
    public function index()
    {
        return view('admin.income.index');
    }

    public function descargarXmlCdr(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Obtén la lista de archivos XML que cumplen con el rango de fechas
        $archivos = Order::leftJoin('vouchers', 'vouchers.order_id', '=', 'orders.id')
            ->select('vouchers.charge_code', 'vouchers.path_xml')
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$fechaInicio, $fechaFin])
            ->get();

        $idZip = str_replace("-", "", $fechaInicio) . '_' . str_replace("-", "", $fechaFin) . '_' . date('His');

        // Nombre del archivo ZIP temporal
        $zipFileName = storage_path('app/public/tmp_xml_cdr/archivos_' . $idZip . '.zip');

        // Crea un archivo ZIP temporal para almacenar los archivos XML y CDR
        $zip = new ZipArchive;
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($archivos as $archivo) {
                if (empty($archivo->path_xml)) continue;
                $file_xml = explode('storage/', $archivo->path_xml);
                $archivo_xml = $file_xml[1];
                $rutaArchivoXML = storage_path($archivo_xml);

                // Especifica la ruta relativa "xml" dentro del archivo ZIP
                $rutaRelativaXML = 'xml/' . basename($archivo_xml);
                $zip->addFile($rutaArchivoXML, $rutaRelativaXML);

                // Cambia la extensión a ZIP para el archivo CDR
                $rutaArchivoCDR = str_replace('.xml', '.zip', $rutaArchivoXML);

                // Especifica la ruta relativa "cdr" dentro del archivo ZIP
                $rutaRelativaCDR = 'cdr/' . basename($rutaArchivoCDR);
                $zip->addFile($rutaArchivoCDR, $rutaRelativaCDR);
            }

            $zip->close();

            // Descarga el archivo ZIP resultante
            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            // Manejar el caso en el que no se puede crear el archivo ZIP
            return back()->with('error', 'No se pudo crear el archivo ZIP.');
        }
    }
}

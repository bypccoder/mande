<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;
use Greenter\Model\DocumentInterface;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\Report\Resolver\DefaultTemplateResolver;
use Greenter\Report\XmlUtils;

class SunatController extends Controller
{

    private static $current;

    public function connect()
    {
        $see = new See();
        $see->setCertificate(file_get_contents(resource_path('/10098282462.pem')));
        $see->setService(SunatEndpoints::FE_BETA); //FE_PRODUCCION- FE_BETA
        $see->setClaveSOL('10098282462', 'DEMOCAFE', 'Marmot4s$1');

        return $see;
    }

    public static function getInstance(): SunatController
    {
        if (!self::$current instanceof self) {
            self::$current = new self();
        }

        return self::$current;
    }

    private function getHash(DocumentInterface $document): ?string
    {
        $see = $this->getSee('');
        $xml = $see->getXmlSigned($document);

        return (new XmlUtils())->getHashSign($xml);
    }

    public function getSee(?string $endpoint)
    {
        $see = new See();
        $see->setService($endpoint);
        //        $see->setCodeProvider(new XmlErrorCodeProvider());
        $certificate = file_get_contents(resource_path('/10098282462.pem'));
        if ($certificate === false) {
            throw new Exception('No se pudo cargar el certificado');
        }
        $see->setCertificate($certificate);
        /**
         * Clave SOL
         * Ruc     = 20000000001
         * Usuario = MODDATOS
         * Clave   = moddatos
         */
        $see->setClaveSOL('10098282462', 'USUSDEMO', 'Marmot4s$1');
        $see->setCachePath(resource_path('cache'));


        return $see;
    }

    public function getPdf(DocumentInterface $document): ?string
    {
        $html = new HtmlReport('', [
            'cache' => storage_path('app/cache'),
            //'cache' => resource_path('cache'),
            'strict_variables' => true,
        ]);
        $resolver = new DefaultTemplateResolver();
        $template = $resolver->getTemplate($document);


        //$html->setTemplate($template);
        $html->setTemplate('invoice2.html.twig');



        $render = new PdfReport($html);
        $render->setOptions([
            'no-outline',
            'print-media-type',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
            'footer-html' => resource_path('/footer.html'),
        ]);
        $binPath = self::getPathBin();
        if (file_exists($binPath)) {
            $render->setBinPath($binPath);
        }
        $hash = $this->getHash($document);
        $params = self::getParametersPdf();
        $params['system']['hash'] = $hash;
        $params['user']['footer'] = '<div>consulte en <a href="https://github.com/giansalex/sufel">sufel.com</a></div>';

        $pdf = $render->render($document, $params);

        if ($pdf === null) {
            $error = $render->getExporter()->getError();

            echo 'Error: ' . $error;
            exit();
        } else {
        }

        // Write html
        $this->writeFile($document->getName() . '.html', $render->getHtml());

        return $pdf;
    }

    public function writeFile(?string $filename, ?string $content): void
    {
        if (getenv('GREENTER_NO_FILES')) {
            return;
        }

        $fileDir = storage_path('/files');

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0777, true);
        }

        file_put_contents($fileDir . DIRECTORY_SEPARATOR . $filename, $content);
    }


    public function showPdf(?string $content, ?string $filename): void
    {
        $this->writeFile($filename, $content);
        /*header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($content));

        echo $content;*/
    }

    public static function getPathBin(): string
    {
        $path =  storage_path('app//public//wkhtmltopdf');
        if (self::isWindows()) {
            $path .= '.exe';
        } else {
            $path = '/usr/local/bin/wkhtmltopdf';
        }

        return $path;
    }

    public function getErrorResponse(\Greenter\Model\Response\Error $error): string
    {
        $result = <<<HTML
        <h2 class="text-danger">Error:</h2><br>
        <b>Código:</b>{$error->getCode()}<br>
        <b>Descripción:</b>{$error->getMessage()}<br>
HTML;

        return $result;
    }


    public static function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private static function getParametersPdf(): array
    {
        $logo = file_get_contents(resource_path('/logo.png'));

        return [
            'system' => [
                'logo' => $logo,
                'hash' => ''
            ],
            'user' => [
                'resolucion' => '212321',
                'header' => 'Telf: <b>(056) 123375</b>',
                'extras' => [
                    ['name' => 'FORMA DE PAGO', 'value' => 'Contado'],
                    ['name' => 'VENDEDOR', 'value' => 'GITHUB SELLER'],
                ],
            ]
        ];
    }
}

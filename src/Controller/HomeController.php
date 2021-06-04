<?php

namespace App\Controller;

use App\Service\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Twig\Parser;
//use Symfony\Component\Yaml\Parser;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Client $Client): Response
    {

/*
        include_once(__DIR__.'/../pdf/fpdf/fpdf.php'); // подключаем библиотеку
        include_once(__DIR__.'/../pdf/fpdi/fpdi.php'); // подключаем библиотеку
        ob_clean();
        $file = __DIR__.'/public/Blank/blanc-osago.pdf';
        if (!file_exists($file)) {
            return false;
        }
        $pdf        = new FPDI();
        $pagecount    = $pdf->setSourceFile($file);
        for ($i = 1; $i <= $pagecount ; $i++) { //проходимо по всем страницам файла
            $tpl            = $pdf->importPage($i);
            $size            = $pdf->getTemplateSize($tpl);
            $orientation    = $size['h'] > $size['w'] ? 'P':'L';
            // используем ориантацию и размер исходного файла
            $pdf->AddPage($orientation);
            $pdf->useTemplate($tpl, null, null, $size['w'], $size['h'], true);
            $pdf->SetXY(21, 90);
            $pdf->SetTextColor(16, 13, 102);
            $pdf->SetFont('Arial', '' , 23);
            $pdf->Cell(0, 0, 'Some test text', 0, 1);
        }
*/

        ///////////////////////

        //var_dump($this->getUser()->getRoles());
        if($this->getUser() && $this->getUser()->getRoles()[0]=='ROLE_AGENT'){

            $contracts=$Client->getContractAgent($this->getUser());

            return $this->render('home/index.html.twig', [
                'contracts'=> $contracts,
                'controller_name' => 'HomeController',
            ]);
        }


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}

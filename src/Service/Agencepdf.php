<?php
// src/Service/AgencePdf.php

namespace App\Service;

use Dompdf\Dompdf;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

class Agencepdf
{
    private $twig;
    private $kernel;

    public function __construct(Environment $twig, KernelInterface $kernel)
    {
        $this->twig = $twig;
        $this->kernel = $kernel;
    }

    public function generatePdfList($agences)
    {
        $dompdf = new Dompdf();
        $html = $this->twig->render('agence/pdf_list.html.twig', ['agences' => $agences]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }
}

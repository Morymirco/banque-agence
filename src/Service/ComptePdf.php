<?php
// src/Service/AgencePdf.php

namespace App\Service;

use Dompdf\Dompdf;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

class ComptePdf
{
    private $twig;
    private $kernel;

    public function __construct(Environment $twig, KernelInterface $kernel)
    {
        $this->twig = $twig;
        $this->kernel = $kernel;
    }

    public function generatePdfList($comptes)
    {
        $dompdf = new Dompdf();
        $html = $this->twig->render('compte/compte_liste.html.twig', ['comptes' => $comptes]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }
}

<?php

namespace App\Controller;

use App\Repository\CompteRepository;
use App\Service\ComptePdf;
use App\Service\PdfService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte')]
#[IsGranted('ROLE_ADMIN')]
class CompteController extends AbstractController
{
    #[Route('/', name: 'app_compte_index', methods: ['GET'])]
    public function index(CompteRepository $compteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $compteRepository->findAllComptes();

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('compte/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

     #[Route('/pdf/list', name: 'app_compte_pdf_list', methods: ['GET'])]
    public function pdfList(CompteRepository $compteRepository, ComptePdf $comptePdf): Response
    {
        $clients = $compteRepository->findAll();
        $pdfContent = $comptePdf->generatePdfList($clients);

        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="comptes.pdf"'
            ]
        );
    }
    
}

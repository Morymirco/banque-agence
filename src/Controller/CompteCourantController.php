<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\CompteCourant;
use App\Form\CompteCourantType;
use App\Form\DepotType;
use App\Form\RetraitType;
use App\Repository\CompteCourantRepository;
use App\Service\RecuPdf;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte/courant')]
  #[IsGranted('ROLE_ADMIN')]
class CompteCourantController extends AbstractController
{
    #[Route('/', name: 'app_compte_courant_index', methods: ['GET'])]
    public function index(CompteCourantRepository $compteCourantRepository): Response
    {
        
      
        return $this->render('compte_courant/index.html.twig', [
            'compte_courant' => $compteCourantRepository->findAll(),
         
        ]);
    }

    #[Route('/new', name: 'app_compte_courant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $compteCourant = new CompteCourant();
        $form = $this->createForm(CompteCourantType::class, $compteCourant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($compteCourant);
            $entityManager->flush();

            return $this->redirectToRoute('app_compte_courant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_courant/new.html.twig', [
            'compte_courant' => $compteCourant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_courant_show', methods: ['GET'])]
    public function show(CompteCourant $compteCourant): Response
    {
        return $this->render('compte_courant/show.html.twig', [
            'compte_courant' => $compteCourant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_compte_courant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CompteCourant $compteCourant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompteCourantType::class, $compteCourant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_compte_courant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_courant/edit.html.twig', [
            'compte_courant' => $compteCourant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_courant_delete', methods: ['POST'])]
    public function delete(Request $request, CompteCourant $compteCourant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compteCourant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($compteCourant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_compte_courant_index', [], Response::HTTP_SEE_OTHER);
    }


   
    #[Route('/{id}/depot', name: 'compte_depot', methods: ['GET', 'POST'])]
    public function depot(Request $request, CompteCourant $compteCourant, EntityManagerInterface $em,RecuPdf $pdfService): Response
    {
        $form = $this->createForm(DepotType::class);
      
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $montant = $data['montant'];

            $compteCourant->depot($montant);
            $em->persist($compteCourant);
            $em->flush();
    
// Génération du PDF
            $html = $this->renderView('Transaction/Depot/recu/depot.html.twig', [
                'compte' => $compteCourant,
                'montant' => $montant,
                'date' => new \DateTime()
            ]);
            return $pdfService->generatePdfFromHtml($html, 'recu_depot.pdf');
        
            // return $this->redirectToRoute('app_compte_courant_show', ['id' => $compteCourant->getId()]);
            return $this->redirectToRoute('app_compte_courant_show', ['id' => $compteCourant->getId()]);
        }

        return $this->render('compte_courant/depot.html.twig', [
            'form' => $form->createView(),
            'compte_courant' => $compteCourant,
        ]);
    }
    #[Route('/{id}/retrait', name: 'compte_retrait', methods: ['GET', 'POST'])]
    public function retrait(Request $request, CompteCourant $compte, EntityManagerInterface $em,RecuPdf $pdfService): Response
    {
        $form = $this->createForm(RetraitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $montant = $data['montant'];

            $compte->retrait($montant);
            $em->persist($compte);
            $em->flush();
          // Génération du PDF
            $html = $this->renderView('Transaction/retrait/recu/retrait.html.twig', [
                'compte' => $compte,
                'montant' => $montant,
                'date' => new \DateTime()
            ]);
            return $pdfService->generatePdfFromHtml($html, 'recu_retrait.pdf');

            return $this->redirectToRoute('app_compte_courant_show', ['id' => $compte->getId()]);
        }

        return $this->render('compte_courant/retrait.html.twig', [
            'form' => $form->createView(),
            'compte' => $compte,
        ]);
    }
}

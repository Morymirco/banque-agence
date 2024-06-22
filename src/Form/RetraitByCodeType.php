<?php

// src/Controller/TransactionController.php
namespace App\Controller;

use App\Entity\Compte;
use App\Form\DepotByCodeType;
use App\Form\RetraitByCodeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    #[Route('/transaction/depot', name: 'transaction_depot', methods: ['GET', 'POST'])]
    public function depot(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepotByCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $codeCompte = $data['codeCompte'];
            $montant = $data['montant'];

            $compte = $entityManager->getRepository(Compte::class)->findOneBy(['code' => $codeCompte]);

            if ($compte === null) {
                $this->addFlash('error', 'Le compte avec ce code n\'existe pas.');
                return $this->redirectToRoute('transaction_depot');
            }

            $compte->depot($montant);
            $entityManager->persist($compte);
            $entityManager->flush();

            $this->addFlash('success', 'Dépôt effectué avec succès.');
            return $this->redirectToRoute('transaction_depot');
        }

        return $this->render('transaction/depot.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/transaction/retrait', name: 'transaction_retrait', methods: ['GET', 'POST'])]
    public function retrait(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RetraitByCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $codeCompte = $data['codeCompte'];
            $montant = $data['montant'];

            $compte = $entityManager->getRepository(Compte::class)->findOneBy(['code' => $codeCompte]);

            if ($compte === null) {
                $this->addFlash('error', 'Le compte avec ce code n\'existe pas.');
                return $this->redirectToRoute('transaction_retrait');
            }

            try {
                $compte->retrait($montant);
                $entityManager->persist($compte);
                $entityManager->flush();

                $this->addFlash('success', 'Retrait effectué avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur: ' . $e->getMessage());
            }

            return $this->redirectToRoute('transaction_retrait');
        }

        return $this->render('transaction/retrait.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

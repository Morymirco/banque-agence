<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\DepotByCodeType;
use App\Form\RetraitByCodeType;
use App\Form\TransferType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/transaction')]
  #[IsGranted('ROLE_ADMIN')]
class TransferController extends AbstractController
{
    #[Route('/transfert', name: 'app_transaction_transfert', methods: ['GET', 'POST'])]
    public function transfer(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransferType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $source = $data['source'];
            $destination = $data['destination'];
            $amount = $data['amount'];

            // Vérifier que le compte source et le compte destination ne sont pas les mêmes
            if ($source === $destination) {
                $this->addFlash('error', 'Le compte source et le compte destination ne peuvent pas être identiques.');
                return $this->redirectToRoute('app_transaction_transfert');
            }

            // Vérifier si le solde du compte source est suffisant
            $totalAmount = $amount + 1000; // Montant total du transfert avec frais
            if ($source->getSolde() < $totalAmount) {
                $this->addFlash('error', 'Solde insuffisant sur le compte source pour effectuer ce transfert.');
                return $this->redirectToRoute('app_transaction_transfert');
            }

            // Début de la transaction
            $entityManager->beginTransaction();

            try {
                // Débiter le montant total (montant + frais de 1000 GNF) du compte source
                $source->debit($totalAmount);
                $destination->credit($amount); // Créditer le montant sur le compte destination

                // Persist des entités
                $entityManager->persist($source);
                $entityManager->persist($destination);
                $entityManager->flush();

                // Commit de la transaction
                $entityManager->commit();

                $this->addFlash('success', 'Transfert effectué avec succès.');

                return $this->redirectToRoute('app_transaction_transfert');
            } catch (\Exception $e) {
                // En cas d'erreur, rollback de la transaction
                $entityManager->rollback();
                $this->addFlash('error', 'Erreur lors du transfert : ' . $e->getMessage());
            }
        }

        return $this->render('Transaction/transfert/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     #[Route('/depot', name: 'app_transaction_depot', methods: ['GET', 'POST'])]
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
                return $this->redirectToRoute('app_transaction_depot');
            }

            $compte->depot($montant);
            $entityManager->persist($compte);
            $entityManager->flush();

            $this->addFlash('success', 'Dépôt effectué avec succès.');
            return $this->redirectToRoute('app_transaction_depot');
        }

        return $this->render('transaction/Depot/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/retrait', name: 'app_transaction_retrait', methods: ['GET', 'POST'])]
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
                return $this->redirectToRoute('app_transaction_retrait');
            }

            try {
                $compte->retrait($montant);
                $entityManager->persist($compte);
                $entityManager->flush();

                $this->addFlash('success', 'Retrait effectué avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur: ' . $e->getMessage());
            }

            return $this->redirectToRoute('app_transaction_retrait');
        }

        return $this->render('transaction/retrait/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

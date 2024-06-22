<?php

namespace App\Controller;

use App\Entity\CompteEpargne;
use App\Form\CompteEpargneType;
use App\Form\DepotType;
use App\Form\RetraitType;
use App\Repository\CompteEpargneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte/epargne')]
  #[IsGranted('ROLE_ADMIN')]
class CompteEpargneController extends AbstractController
{
    #[Route('/', name: 'app_compte_epargne_index', methods: ['GET'])]
    public function index(CompteEpargneRepository $compteEpargneRepository): Response
    {
        return $this->render('compte_epargne/index.html.twig', [
            'compte_epargnes' => $compteEpargneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_compte_epargne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $compteEpargne = new CompteEpargne();
        $form = $this->createForm(CompteEpargneType::class, $compteEpargne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($compteEpargne);
            $entityManager->flush();

            return $this->redirectToRoute('app_compte_epargne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_epargne/new.html.twig', [
            'compte_epargne' => $compteEpargne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_epargne_show', methods: ['GET'])]
    public function show(CompteEpargne $compteEpargne): Response
    {
        return $this->render('compte_epargne/show.html.twig', [
            'compte_epargne' => $compteEpargne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_compte_epargne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CompteEpargne $compteEpargne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompteEpargneType::class, $compteEpargne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_compte_epargne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compte_epargne/edit.html.twig', [
            'compte_epargne' => $compteEpargne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compte_epargne_delete', methods: ['POST'])]
    public function delete(Request $request, CompteEpargne $compteEpargne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compteEpargne->getId(), $request->request->get('_token'))) {
            $entityManager->remove($compteEpargne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_compte_epargne_index', [], Response::HTTP_SEE_OTHER);
    }

  #[Route('/{id}/depot', name: 'compte_epargne_depot', methods: ['GET', 'POST'])]
    public function depot(Request $request, CompteEpargne $compteEpargne, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DepotType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $montant = $data['montant'];

            $compteEpargne->depot($montant);
            $em->persist($compteEpargne);
            $em->flush();

            $this->addFlash('success', 'Dépôt effectué avec succès.');

            return $this->redirectToRoute('app_compte_epargne_show', ['id' => $compteEpargne->getId()]);
        }

        return $this->render('compte_epargne/transaction/depot.html.twig', [
            'form' => $form->createView(),
            'compte_epargne' => $compteEpargne,
        ]);
    }

    #[Route('/{id}/retrait', name: 'compte_epargne_retrait', methods: ['GET', 'POST'])]
    public function retrait(Request $request, CompteEpargne $compteEpargne, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RetraitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $montant = $data['montant'];

            try {
                $compteEpargne->retrait($montant);
                $em->persist($compteEpargne);
                $em->flush();

                $this->addFlash('success', 'Retrait effectué avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute('app_compte_epargne_show', ['id' => $compteEpargne->getId()]);
        }

        return $this->render('compte_epargne/transaction/retrait.html.twig', [
            'form' => $form->createView(),
            'compte_epargne' => $compteEpargne,
        ]);
    }
}
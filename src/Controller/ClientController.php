<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client')]
  #[IsGranted('ROLE_ADMIN')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository,PaginatorInterface $paginator,Request $request
    ): Response
    {
        $data = $clientRepository->findAll();
        $clients = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            6
        );
        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le Client a été ajouté avec success'
             );

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }
      #[Route('/pdf', name: 'clients2_pdf')]
    public function generatePdf(ClientRepository $clientRepository, PdfService $pdfService): Response
    {
        $clients = $clientRepository->findAll();

        $pdfService->generatePdf('client/Pdf.html.twig', [
            'clients' => $clients
        ]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        // return $this->render('client/show.html.twig', [
        //     'client' => $client,
        // ]);
        $compte = $client->getCompte();

        return $this->render('client/show.html.twig', [
            'client' => $client,
            'compte' => $compte,
        ]);
    }
     #[Route('/{id<\d+>}', name: 'app_client_show')]
    public function detail(Client $client= null): Response {
        if(!$client) {
            $this->addFlash('error', "Le Client n'existe pas ");
            return $this->redirectToRoute('app_client_index');
        }

        return $this->render('client/show.html.twig', ['client' => $client]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Le Client a été mis à jour avec succès");
        } else {
            //Sinon  retourner un flashMessage d'erreur
            $this->addFlash('error', "Client innexistant");
        }
        // return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST']),  IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
               $this->addFlash('success', "Le Client a été supprimé avec succès");
        }
         else {
            //Sinon  retourner un flashMessage d'erreur
            $this->addFlash('error', "Client innexistant");
         }
        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}

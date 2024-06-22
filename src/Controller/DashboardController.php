<?php

namespace App\Controller;
// src/Controller/DashboardController.php

namespace App\Controller;

use App\Repository\AgenceRepository;
use App\Repository\CompteRepository;
use App\Repository\ClientRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
      #[IsGranted('ROLE_ADMIN')]
    public function index(
        AgenceRepository $agenceRepository,
        CompteRepository $compteRepository,
        ClientRepository $clientRepository,
        Security $security
    ): Response {
        $totalAgences = $agenceRepository->countAll();
        $totalComptes = $compteRepository->countAll();
        $totalClients = $clientRepository->countAll();
        $comptesCount = $compteRepository->count([]);
        
  // Récupérer l'utilisateur connecté
        $user = $security->getUser();

    //    dd($totalComptes);
        return $this->render('dashboard/index.html.twig', [
            'totalAgences' => $totalAgences,
            'totalComptes' => $comptesCount,
            'totalClients' => $totalClients,
              'user' => $user,
        ]);
    }
}

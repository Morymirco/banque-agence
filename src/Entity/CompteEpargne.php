<?php

namespace App\Entity;

use App\Repository\CompteEpargneRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Entity(repositoryClass: CompteEpargneRepository::class)]
#[ORM\Table(name:'compte_epargne')]
class CompteEpargne extends Compte
{
    #[ORM\Column]
    // private ?int $tauxInteret = null;
    public $tauxInteret = 0.05;

     public function getTauxInteret(): float
    {
        return (float) $this->tauxInteret;
    }

    public function setTauxInteret(float $tauxInteret): self
    {
        $this->tauxInteret = $tauxInteret;
        return $this;
    }

    public function calculInteret(): void
    {
        $solde = (float) $this->getSolde();
        $tauxInteret = (float) $this->getTauxInteret();
        $interet = $solde * $tauxInteret;
        $this->setSolde($solde + $interet);
    }

    public function depot(float $montant): self
    {
        $this->solde += $montant;
        $this->calculInteret(); // Calculer les intérêts après chaque dépôt
        return $this;
    }

    public function retrait(float $montant): self
    {
        if ($montant > $this->solde) {
            throw new \Exception("Le montant du retrait est supérieur au solde disponible.");
        }
        $this->solde -= $montant;
        return $this;
    }
   
}


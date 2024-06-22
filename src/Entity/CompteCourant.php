<?php

namespace App\Entity;

use App\Repository\CompteCourantRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\Entity(repositoryClass: CompteCourantRepository::class)]
class CompteCourant extends Compte
{
    private const FRAIS_OPERATION = 1000;

    public function depot(float $montant): self
    {
        $this->solde += $montant - self::FRAIS_OPERATION;
        return $this;
    }

    public function retrait(float $montant): self
    {
        $this->solde -= $montant + self::FRAIS_OPERATION;
        return $this;
    }
 
}

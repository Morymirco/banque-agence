<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
#[ORM\Table(name: "Compte")]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"typeCompte", type:"string")]
#[ORM\DiscriminatorMap(["compte_courant" => "CompteCourant", "compte_epargne" => "CompteEpargne"])]
class Compte 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $code;

    #[ORM\Column]
    protected ?float $solde = 0;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Client $Client = null;
  public function __construct()
    {
        // Générer le code aléatoirement lors de la création du compte
        $this->code = mt_rand(100000, 999999); // Exemple de génération de code aléatoire
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): static
    {
        $this->solde = $solde;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): static
    {
        $this->Client = $Client;

        return $this;
    }
      public function depot(float $montant): self
    {
        $this->solde += $montant;
        return $this;
    }

    public function retrait(float $montant): self
    {
        $this->solde -= $montant;
        return $this;
    }
   

    // Ajouter une méthode pour rendre le champ code en lecture seule dans les formulaires
    public function getCodeReadOnly(): ?int
    {
        return $this->code;
    }
    public function debit(float $amount): self
{
    if ($this->solde < $amount) {
        throw new \Exception('Solde insuffisant pour effectuer le transfert.');
    }

    $this->solde -= $amount;
    return $this;
}

public function credit(float $amount): self
{
    $this->solde += $amount;
    return $this;
}
}

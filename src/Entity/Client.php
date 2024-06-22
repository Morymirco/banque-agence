<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroClient = null;

    #[ORM\Column(length: 255)]
    private ?string $nomClient = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomClient = null;

    #[ORM\Column(length: 255)]
    private ?string $sexeClient = null;

    #[ORM\Column(length: 255)]
    private ?string $telephoneClient = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Agence $Agence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroClient(): ?string
    {
        return $this->numeroClient;
    }

    public function setNumeroClient(string $numeroClient): static
    {
        $this->numeroClient = $numeroClient;

        return $this;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getPrenomClient(): ?string
    {
        return $this->prenomClient;
    }

    public function setPrenomClient(string $prenomClient): static
    {
        $this->prenomClient = $prenomClient;

        return $this;
    }

    public function getSexeClient(): ?string
    {
        return $this->sexeClient;
    }

    public function setSexeClient(string $sexeClient): static
    {
        $this->sexeClient = $sexeClient;

        return $this;
    }

    public function getTelephoneClient(): ?string
    {
        return $this->telephoneClient;
    }

    public function setTelephoneClient(string $telephoneClient): static
    {
        $this->telephoneClient = $telephoneClient;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->Agence;
    }

    public function setAgence(?Agence $Agence): static
    {
        $this->Agence = $Agence;

        return $this;
    }
       public function __toString()
    {
        return $this->prenomClient;
    }
     #[ORM\OneToOne(mappedBy: 'Client', cascade: ['persist', 'remove'])]
    private ?Compte $compte = null;

     public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): static
    {
        $this->compte = $compte;

        // Set the owning side of the relation if necessary
        if ($compte->getClient() !== $this) {
            $compte->setClient($this);
        }

        return $this;
    }
}

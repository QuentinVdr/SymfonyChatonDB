<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProprietaireRepository::class)
 */
class Proprietaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Prenom;

    /**
     * @ORM\ManyToMany(targetEntity=Chaton::class, inversedBy="proprietaires")
     */
    private $Chatons;

    public function __construct()
    {
        $this->Chatons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $nom): self
    {
        $this->Nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->Prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Chaton>
     */
    public function getChatons(): Collection
    {
        return $this->Chatons;
    }

    public function addChaton(Chaton $chaton): self
    {
        if (!$this->Chatons->contains($chaton)) {
            $this->Chatons[] = $chaton;
        }

        return $this;
    }

    public function removeChaton(Chaton $chaton): self
    {
        $this->Chatons->removeElement($chaton);

        return $this;
    }
}

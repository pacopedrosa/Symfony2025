<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $valor = null;

    /**
     * @var Collection<int, Matchs>
     */
    #[ORM\ManyToMany(targetEntity: Matchs::class, mappedBy: 'relationCards')]
    private Collection $relationMatchs;

    public function __construct()
    {
        $this->relationMatchs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(int $valor): static
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * @return Collection<int, Matchs>
     */
    public function getRelationMatchs(): Collection
    {
        return $this->relationMatchs;
    }

    public function addRelationMatch(Matchs $relationMatch): static
    {
        if (!$this->relationMatchs->contains($relationMatch)) {
            $this->relationMatchs->add($relationMatch);
            $relationMatch->addRelationCard($this);
        }

        return $this;
    }

    public function removeRelationMatch(Matchs $relationMatch): static
    {
        if ($this->relationMatchs->removeElement($relationMatch)) {
            $relationMatch->removeRelationCard($this);
        }

        return $this;
    }
}

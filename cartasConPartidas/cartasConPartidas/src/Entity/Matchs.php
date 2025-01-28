<?php

namespace App\Entity;

use App\Repository\MatchsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchsRepository::class)]
class Matchs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $open = null;

    #[ORM\ManyToOne(inversedBy: 'relationMatchs')]
    private ?User $relationUser = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $winner = null;

    #[ORM\ManyToOne(targetEntity: Card::class)]
    private ?Card $userCard = null;

    #[ORM\ManyToOne(targetEntity: Card::class)]
    private ?Card $opponentCard = null;

    /**
     * @var Collection<int, Card>
     */
    #[ORM\ManyToMany(targetEntity: Card::class, inversedBy: 'relationMatchs')]
    private Collection $relationCards;

    #[ORM\ManyToOne(inversedBy: 'oponent')]
    private ?User $oponent = null;

    public function __construct()
    {
        $this->relationCards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): static
    {
        $this->open = $open;

        return $this;
    }

    public function getRelationUser(): ?User
    {
        return $this->relationUser;
    }

    public function setRelationUser(?User $relationUser): static
    {
        $this->relationUser = $relationUser;

        return $this;
    }

    public function getWinner(): ?User
    {
        return $this->winner;
    }

    public function setWinner(?User $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getUserCard(): ?Card
    {
        return $this->userCard;
    }

    public function setUserCard(?Card $userCard): self
    {
        $this->userCard = $userCard;

        return $this;
    }

    public function getOpponentCard(): ?Card
    {
        return $this->opponentCard;
    }

    public function setOpponentCard(?Card $opponentCard): self
    {
        $this->opponentCard = $opponentCard;

        return $this;
    }

    /**
     * @return Collection<int, Card>
     */
    public function getRelationCards(): Collection
    {
        return $this->relationCards;
    }

    public function addRelationCard(Card $relationCard): static
    {
        if (!$this->relationCards->contains($relationCard)) {
            $this->relationCards->add($relationCard);
        }

        return $this;
    }

    public function removeRelationCard(Card $relationCard): static
    {
        $this->relationCards->removeElement($relationCard);

        return $this;
    }

    public function getOponent(): ?User
    {
        return $this->oponent;
    }

    public function setOponent(?User $oponent): static
    {
        $this->oponent = $oponent;

        return $this;
    }
}

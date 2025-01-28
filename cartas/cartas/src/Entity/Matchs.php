<?php

namespace App\Entity;

use App\Repository\MatchsRepository;
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
}

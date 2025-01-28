<?php

namespace App\Entity;

use App\Repository\UbicacionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UbicacionRepository::class)]
class Ubicacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pasillo = null;

    #[ORM\Column]
    private ?int $estanteria = null;

    #[ORM\Column]
    private ?int $estante = null;

    #[ORM\ManyToOne(inversedBy: 'ubicacions')]
    private ?objetos $relacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPasillo(): ?int
    {
        return $this->pasillo;
    }

    public function setPasillo(int $pasillo): static
    {
        $this->pasillo = $pasillo;

        return $this;
    }

    public function getEstanteria(): ?int
    {
        return $this->estanteria;
    }

    public function setEstanteria(int $estanteria): static
    {
        $this->estanteria = $estanteria;

        return $this;
    }

    public function getEstante(): ?int
    {
        return $this->estante;
    }

    public function setEstante(int $estante): static
    {
        $this->estante = $estante;

        return $this;
    }

    public function getRelacion(): ?objetos
    {
        return $this->relacion;
    }

    public function setRelacion(?objetos $relacion): static
    {
        $this->relacion = $relacion;

        return $this;
    }
}

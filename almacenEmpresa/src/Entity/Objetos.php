<?php

namespace App\Entity;

use App\Repository\ObjetosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjetosRepository::class)]
class Objetos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\ManyToOne(inversedBy: 'relacion')]
    private ?Registro $relacionRegistros = null;

    #[ORM\ManyToOne(inversedBy: 'relacion')]
    private ?RegistroSalida $relacionRegistroSalida = null;

    /**
     * @var Collection<int, Ubicacion>
     */
    #[ORM\OneToMany(targetEntity: Ubicacion::class, mappedBy: 'relacion')]
    private Collection $ubicacions;

    public function __construct()
    {
        $this->ubicacions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getRelacionRegistros(): ?Registro
    {
        return $this->relacionRegistros;
    }

    public function setRelacionRegistros(?Registro $relacionRegistros): static
    {
        $this->relacionRegistros = $relacionRegistros;

        return $this;
    }

    public function getRelacionRegistroSalida(): ?RegistroSalida
    {
        return $this->relacionRegistroSalida;
    }

    public function setRelacionRegistroSalida(?RegistroSalida $relacionRegistroSalida): static
    {
        $this->relacionRegistroSalida = $relacionRegistroSalida;

        return $this;
    }

    /**
     * @return Collection<int, Ubicacion>
     */
    public function getUbicacions(): Collection
    {
        return $this->ubicacions;
    }

    public function addUbicacion(Ubicacion $ubicacion): static
    {
        if (!$this->ubicacions->contains($ubicacion)) {
            $this->ubicacions->add($ubicacion);
            $ubicacion->setRelacion($this);
        }

        return $this;
    }

    public function removeUbicacion(Ubicacion $ubicacion): static
    {
        if ($this->ubicacions->removeElement($ubicacion)) {
            // set the owning side to null (unless already changed)
            if ($ubicacion->getRelacion() === $this) {
                $ubicacion->setRelacion(null);
            }
        }

        return $this;
    }
}

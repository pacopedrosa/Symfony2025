<?php

namespace App\Entity;

use App\Repository\RegistroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistroRepository::class)]
class Registro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_entrada = null;

    /**
     * @var Collection<int, objetos>
     */
    #[ORM\OneToMany(targetEntity: objetos::class, mappedBy: 'relacionRegistros')]
    private Collection $relacion;

    public function __construct()
    {
        $this->relacion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFechaEntrada(): ?\DateTimeInterface
    {
        return $this->fecha_entrada;
    }

    public function setFechaEntrada(\DateTimeInterface $fecha_entrada): static
    {
        $this->fecha_entrada = $fecha_entrada;

        return $this;
    }

    /**
     * @return Collection<int, objetos>
     */
    public function getRelacion(): Collection
    {
        return $this->relacion;
    }

    public function addRelacion(objetos $relacion): static
    {
        if (!$this->relacion->contains($relacion)) {
            $this->relacion->add($relacion);
            $relacion->setRelacionRegistros($this);
        }

        return $this;
    }

    public function removeRelacion(objetos $relacion): static
    {
        if ($this->relacion->removeElement($relacion)) {
            // set the owning side to null (unless already changed)
            if ($relacion->getRelacionRegistros() === $this) {
                $relacion->setRelacionRegistros(null);
            }
        }

        return $this;
    }
}

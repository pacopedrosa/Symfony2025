<?php

namespace App\Entity;

use App\Repository\RegistroSalidaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegistroSalidaRepository::class)]
class RegistroSalida
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha_salida = null;

    /**
     * @var Collection<int, objetos>
     */
    #[ORM\OneToMany(targetEntity: objetos::class, mappedBy: 'relacionRegistroSalida')]
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

    public function getFechaSalida(): ?\DateTimeInterface
    {
        return $this->fecha_salida;
    }

    public function setFechaSalida(\DateTimeInterface $fecha_salida): static
    {
        $this->fecha_salida = $fecha_salida;

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
            $relacion->setRelacionRegistroSalida($this);
        }

        return $this;
    }

    public function removeRelacion(objetos $relacion): static
    {
        if ($this->relacion->removeElement($relacion)) {
            // set the owning side to null (unless already changed)
            if ($relacion->getRelacionRegistroSalida() === $this) {
                $relacion->setRelacionRegistroSalida(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ComunicacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComunicacionRepository::class)]
class Comunicacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $anotacion = null;

    #[ORM\Column(length: 255)]
    private ?string $nombreContacto = null; // Campo para el nombre del contacto

    /**
     * @var Collection<int, PersonaContacto>
     */
    #[ORM\OneToMany(targetEntity: PersonaContacto::class, mappedBy: 'relacionComunicacion')]
    private Collection $relacion;

    public function __construct()
    {
        $this->relacion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnotacion(): ?string
    {
        return $this->anotacion;
    }

    public function setAnotacion(string $anotacion): static
    {
        $this->anotacion = $anotacion;

        return $this;
    }

    public function getNombreContacto(): ?string
    {
        return $this->nombreContacto;
    }

    public function setNombreContacto(string $nombreContacto): static
    {
        $this->nombreContacto = $nombreContacto;

        return $this;
    }

    /**
     * @return Collection<int, PersonaContacto>
     */
    public function getRelacion(): Collection
    {
        return $this->relacion;
    }

    public function addRelacion(PersonaContacto $relacion): static
    {
        if (!$this->relacion->contains($relacion)) {
            $this->relacion->add($relacion);
            $relacion->setRelacionComunicacion($this);
        }

        return $this;
    }

    public function removeRelacion(PersonaContacto $relacion): static
    {
        if ($this->relacion->removeElement($relacion)) {
            // set the owning side to null (unless already changed)
            if ($relacion->getRelacionComunicacion() === $this) {
                $relacion->setRelacionComunicacion(null);
            }
        }

        return $this;
    }
}

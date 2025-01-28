<?php

namespace App\Entity;

use App\Repository\EmpresaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpresaRepository::class)]
class Empresa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $nif = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column]
    private ?int $telefono = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $visible = null;

    /**
     * @var Collection<int, personaContacto>
     */
    #[ORM\OneToMany(targetEntity: PersonaContacto::class, mappedBy: 'empresa')]
    private Collection $relacion;

    public function __construct()
    {
        $this->relacion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNif(): ?string
    {
        return $this->nif;
    }

    public function setNif(string $nif): static
    {
        $this->nif = $nif;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * @return Collection<int, personaContacto>
     */
    public function getRelacion(): Collection
    {
        return $this->relacion;
    }

    public function addRelacion(personaContacto $relacion): static
    {
        if (!$this->relacion->contains($relacion)) {
            $this->relacion->add($relacion);
            $relacion->setEmpresa($this);
        }

        return $this;
    }

    public function removeRelacion(personaContacto $relacion): static
    {
        if ($this->relacion->removeElement($relacion)) {
            // set the owning side to null (unless already changed)
            if ($relacion->getEmpresa() === $this) {
                $relacion->setEmpresa(null);
            }
        }

        return $this;
    }
}

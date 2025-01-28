<?php

namespace App\Entity;

use App\Repository\PersonaContactoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonaContactoRepository::class)]
class PersonaContacto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $puesto = null;

    #[ORM\Column]
    private ?int $telefono = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'relacion')]
    private ?Empresa $empresa = null;

    #[ORM\Column]
    private ?int $visible = null;

    #[ORM\ManyToOne(inversedBy: 'relacionComunicacion')]
    private ?Comunicacion $relacionComunicacion = null; // Agregar esta propiedad

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

    public function getPuesto(): ?string
    {
        return $this->puesto;
    }

    public function setPuesto(string $puesto): static
    {
        $this->puesto = $puesto;

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

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): static
    {
        $this->empresa = $empresa;

        return $this;
    }

    public function getVisible(): ?int
    {
        return $this->visible;
    }

    public function setVisible(int $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getRelacionComunicacion(): ?Comunicacion
    {
        return $this->relacionComunicacion;
    }

    public function setRelacionComunicacion(?Comunicacion $relacionComunicacion): static
    {
        $this->relacionComunicacion = $relacionComunicacion;

        return $this;
    }
}

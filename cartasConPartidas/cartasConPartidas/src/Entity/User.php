<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Matchs>
     */
    #[ORM\OneToMany(targetEntity: Matchs::class, mappedBy: 'relationUser')]
    private Collection $relationMatchs;

    /**
     * @var Collection<int, Matchs>
     */
    #[ORM\OneToMany(targetEntity: Matchs::class, mappedBy: 'oponent')]
    private Collection $oponent;

    public function __construct()
    {
        $this->relationMatchs = new ArrayCollection();
        $this->oponent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $relationMatch->setRelationUser($this);
        }

        return $this;
    }

    public function removeRelationMatch(Matchs $relationMatch): static
    {
        if ($this->relationMatchs->removeElement($relationMatch)) {
            // set the owning side to null (unless already changed)
            if ($relationMatch->getRelationUser() === $this) {
                $relationMatch->setRelationUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Matchs>
     */
    public function getOponent(): Collection
    {
        return $this->oponent;
    }

    public function addOponent(Matchs $oponent): static
    {
        if (!$this->oponent->contains($oponent)) {
            $this->oponent->add($oponent);
            $oponent->setOponent($this);
        }

        return $this;
    }

    public function removeOponent(Matchs $oponent): static
    {
        if ($this->oponent->removeElement($oponent)) {
            // set the owning side to null (unless already changed)
            if ($oponent->getOponent() === $this) {
                $oponent->setOponent(null);
            }
        }

        return $this;
    }
}

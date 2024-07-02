<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?string $firstname = null;

    #[ORM\Column]
    private ?string $lastname = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserJobTitle $jobTitle = null;

    /**
     * @var Collection<int, ClientCase>
     */
    #[ORM\ManyToMany(targetEntity: ClientCase::class, mappedBy: 'collaborators')]
    private Collection $clientCases;

    public function __construct()
    {
        $this->clientCases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
    public function getPassword(): string
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

    public function getJobTitle(): ?UserJobTitle
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?UserJobTitle $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullName(): string
    {
        return ucfirst($this->lastname) . ' ' . ucfirst($this->firstname);
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }

    /**
     * @return Collection<int, ClientCase>
     */
    public function getClientCases(): Collection
    {
        return $this->clientCases;
    }

    public function addClientCase(ClientCase $clientCase): static
    {
        if (!$this->clientCases->contains($clientCase)) {
            $this->clientCases->add($clientCase);
            $clientCase->addCollaborator($this);
        }

        return $this;
    }

    public function removeClientCase(ClientCase $clientCase): static
    {
        if ($this->clientCases->removeElement($clientCase)) {
            $clientCase->removeCollaborator($this);
        }

        return $this;
    }
}

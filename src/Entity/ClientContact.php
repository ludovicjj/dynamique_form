<?php

namespace App\Entity;

use App\Repository\ClientContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientContactRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class ClientContact
{
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private DateTime $createdAt;

    #[ORM\ManyToOne(inversedBy: 'clientContacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?ClientJobTitle $jobTitle = null;

    /**
     * @var Collection<int, ClientCase>
     */
    #[ORM\ManyToMany(targetEntity: ClientCase::class, mappedBy: 'clientContacts')]
    private Collection $clientCases;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->clientCases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getJobTitle(): ?ClientJobTitle
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?ClientJobTitle $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->firstname . ' ' . $this->lastname;
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
            $clientCase->addClientContact($this);
        }

        return $this;
    }

    public function removeClientCase(ClientCase $clientCase): static
    {
        if ($this->clientCases->removeElement($clientCase)) {
            $clientCase->removeClientContact($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }
}

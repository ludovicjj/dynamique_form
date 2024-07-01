<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class Client
{
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    private ?string $companyName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    private ?string $address1 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $siret = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Country $country = null;

    #[ORM\Column]
    private DateTime $createdAt;

    /**
     * @var Collection<int, ClientContact>
     */
    #[ORM\OneToMany(targetEntity: ClientContact::class, mappedBy: 'client')]
    private Collection $clientContacts;

    /**
     * @var Collection<int, ClientCase>
     */
    #[ORM\OneToMany(targetEntity: ClientCase::class, mappedBy: 'client')]
    private Collection $clientCases;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->clientContacts = new ArrayCollection();
        $this->clientCases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(?string $address1): static
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, ClientContact>
     */
    public function getClientContacts(): Collection
    {
        return $this->clientContacts;
    }

    public function addClientContact(ClientContact $clientContact): static
    {
        if (!$this->clientContacts->contains($clientContact)) {
            $this->clientContacts->add($clientContact);
            $clientContact->setClient($this);
        }

        return $this;
    }

    public function removeClientContact(ClientContact $clientContact): static
    {
        if ($this->clientContacts->removeElement($clientContact)) {
            // set the owning side to null (unless already changed)
            if ($clientContact->getClient() === $this) {
                $clientContact->setClient(null);
            }
        }

        return $this;
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
            $clientCase->setClient($this);
        }

        return $this;
    }

    public function removeClientCase(ClientCase $clientCase): static
    {
        if ($this->clientCases->removeElement($clientCase)) {
            // set the owning side to null (unless already changed)
            if ($clientCase->getClient() === $this) {
                $clientCase->setClient(null);
            }
        }

        return $this;
    }
}

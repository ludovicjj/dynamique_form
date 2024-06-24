<?php

namespace App\Entity;

use App\Repository\PartnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

#[ORM\Entity(repositoryClass: PartnerRepository::class)]
class Partner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $companyName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $address1 = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
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

    #[ORM\Column]
    private DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[Assert\NotBlank]
    private ?Country $country = null;

    #[ORM\OneToMany(targetEntity: PartnerContact::class, mappedBy: 'partner')]
    private Collection $partnerContacts;

    public function __construct()
    {
        $this->partnerContacts = new ArrayCollection();
        $this->createdAt = new DateTime();
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

    public function setCreatedAt(?DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, PartnerContact>
     */
    public function getPartnerContacts(): Collection
    {
        return $this->partnerContacts;
    }

    public function addPartnerContact(PartnerContact $partnerContact): static
    {
        if (!$this->partnerContacts->contains($partnerContact)) {
            $this->partnerContacts->add($partnerContact);
            $partnerContact->setPartner($this);
        }

        return $this;
    }

    public function removePartnerContact(PartnerContact $partnerContact): static
    {
        if ($this->partnerContacts->removeElement($partnerContact)) {
            // set the owning side to null (unless already changed)
            if ($partnerContact->getPartner() === $this) {
                $partnerContact->setPartner(null);
            }
        }

        return $this;
    }
}

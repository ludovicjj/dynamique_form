<?php

namespace App\Entity;

use App\Repository\ClientCaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

#[ORM\Entity(repositoryClass: ClientCaseRepository::class)]
class ClientCase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 16)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $projectName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $address1 = null;

    #[ORM\Column(length: 16)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 16)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    private ?DateTime $signedAt = null;

    /**
     * @var Collection<int, PartnerContact>
     */
    #[ORM\ManyToMany(targetEntity: PartnerContact::class, inversedBy: 'clientCases')]
    private Collection $PartnerContacts;

    #[ORM\ManyToOne(inversedBy: 'clientCases')]
    private ?Country $country = null;

    public function __construct()
    {
        $this->PartnerContacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(?string $projectName): static
    {
        $this->projectName = $projectName;

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

    public function getSignedAt(): ?DateTime
    {
        return $this->signedAt;
    }

    public function setSignedAt(?DateTime $signedAt): static
    {
        $this->signedAt = $signedAt;

        return $this;
    }

    /**
     * @return Collection<int, PartnerContact>
     */
    public function getPartnerContacts(): Collection
    {
        return $this->PartnerContacts;
    }

    public function addPartnerContact(PartnerContact $partnerContact): static
    {
        if (!$this->PartnerContacts->contains($partnerContact)) {
            $this->PartnerContacts->add($partnerContact);
        }

        return $this;
    }

    public function removePartnerContact(PartnerContact $partnerContact): static
    {
        $this->PartnerContacts->removeElement($partnerContact);

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
}

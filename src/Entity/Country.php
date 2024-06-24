<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ClientCase>
     */
    #[ORM\OneToMany(targetEntity: ClientCase::class, mappedBy: 'country')]
    private Collection $clientCases;

    public function __construct()
    {
        $this->clientCases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
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
            $clientCase->setCountry($this);
        }

        return $this;
    }

    public function removeClientCase(ClientCase $clientCase): static
    {
        if ($this->clientCases->removeElement($clientCase)) {
            // set the owning side to null (unless already changed)
            if ($clientCase->getCountry() === $this) {
                $clientCase->setCountry(null);
            }
        }

        return $this;
    }
}

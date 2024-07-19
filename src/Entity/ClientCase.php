<?php

namespace App\Entity;

use App\Repository\ClientCaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

#[ORM\Entity(repositoryClass: ClientCaseRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: true)]
class ClientCase
{
    use SoftDeleteableEntity;

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

    #[ORM\Column]
    private DateTime $createdAt;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column(nullable: true)]
    #[Assert\LessThanOrEqual('today')]
    private ?DateTime $signedAt = null;

    #[ORM\ManyToOne(inversedBy: 'clientCases')]
    #[Assert\NotBlank]
    private ?Country $country = null;

    #[ORM\ManyToOne(inversedBy: 'clientCases')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Client $client = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Length(max: 255)]
    private ?string $directoryName = null;

    #[ORM\Column(nullable: true)]
    private ?DateTime $buildStartedAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTime $buildFinishedAt = null;

    #[ORM\Column(nullable: true)]
    private ?string $agreementAmount = null;

    #[ORM\Column(nullable: true)]
    private ?string $lastKnowCost = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private bool $isDraft;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?BuildingCategory $BuildingCategory = null;

    #[ORM\ManyToOne]
    private ?User $manager = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'clientCases')]
    private ?self $parentCase = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentCase')]
    private Collection $clientCases;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ClientCaseStatus $clientCaseStatus = null;

    /**
     * @var Collection<int, PartnerContact>
     */
    #[ORM\ManyToMany(targetEntity: PartnerContact::class, inversedBy: 'clientCases')]
    private Collection $partnerContacts;

    /**
     * @var Collection<int, Mission>
     */
    #[ORM\ManyToMany(targetEntity: Mission::class, inversedBy: 'clientCases')]
    private Collection $missions;

    /**
     * @var Collection<int, ClientContact>
     */
    #[ORM\ManyToMany(targetEntity: ClientContact::class, inversedBy: 'clientCases')]
    private Collection $clientContacts;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'clientCases')]
    private Collection $collaborators;

    /**
     * @var Collection<int, ProjectFeature>
     */
    #[ORM\ManyToMany(targetEntity: ProjectFeature::class)]
    private Collection $projectFeatures;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'clientCase')]
    #[Assert\Valid]
    private Collection $documents;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'clientCase')]
    private Collection $reports;

    public function __construct()
    {
        $this->partnerContacts = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->clientContacts = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
        $this->clientCases = new ArrayCollection();
        $this->projectFeatures = new ArrayCollection();
        $this->isDraft = true;
        $this->documents = new ArrayCollection();
        $this->reports = new ArrayCollection();
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

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
        return $this->partnerContacts;
    }

    public function addPartnerContact(PartnerContact $partnerContact): static
    {
        if (!$this->partnerContacts->contains($partnerContact)) {
            $this->partnerContacts->add($partnerContact);
        }

        return $this;
    }

    public function removePartnerContact(PartnerContact $partnerContact): static
    {
        $this->partnerContacts->removeElement($partnerContact);

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

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
        }

        return $this;
    }

    public function removeClientContact(ClientContact $clientContact): static
    {
        $this->clientContacts->removeElement($clientContact);

        return $this;
    }


    public function getDirectoryName(): ?string
    {
        return $this->directoryName;
    }


    public function setDirectoryName(?string $directoryName): static
    {
        $this->directoryName = $directoryName;

        return $this;
    }

    public function getBuildStartedAt(): ?DateTime
    {
        return $this->buildStartedAt;
    }

    public function setBuildStartedAt(?DateTime $buildStartedAt): static
    {
        $this->buildStartedAt = $buildStartedAt;

        return $this;
    }

    public function getBuildFinishedAt(): ?DateTime
    {
        return $this->buildFinishedAt;
    }

    public function setBuildFinishedAt(?DateTime $buildFinishedAt): static
    {
        $this->buildFinishedAt = $buildFinishedAt;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAgreementAmount(): ?string
    {
        return $this->agreementAmount;
    }


    public function setAgreementAmount(?string $agreementAmount): static
    {
        $this->agreementAmount = $agreementAmount;

        return $this;
    }

    public function getLastKnowCost(): ?string
    {
        return $this->lastKnowCost;
    }

    public function setLastKnowCost(?string $lastKnowCost): static
    {
        $this->lastKnowCost = $lastKnowCost;

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        $this->missions->removeElement($mission);

        return $this;
    }

    public function getBuildingCategory(): ?BuildingCategory
    {
        return $this->BuildingCategory;
    }

    public function setBuildingCategory(?BuildingCategory $BuildingCategory): static
    {
        $this->BuildingCategory = $BuildingCategory;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): static
    {
        $this->manager = $manager;

        return $this;
    }

    public function isDraft(): ?bool
    {
        return $this->isDraft;
    }

    public function setIsDraft(bool $isDraft): static
    {
        $this->isDraft = $isDraft;

        return $this;
    }

    public function getParentCase(): ?self
    {
        return $this->parentCase;
    }

    public function setParentCase(?self $parentCase): static
    {
        $this->parentCase = $parentCase;

        return $this;
    }


    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCollaborators(): Collection
    {
        return $this->collaborators;
    }

    public function addCollaborator(User $collaborator): static
    {
        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators->add($collaborator);
        }

        return $this;
    }

    public function removeCollaborator(User $collaborator): static
    {
        $this->collaborators->removeElement($collaborator);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getClientCases(): Collection
    {
        return $this->clientCases;
    }

    public function addClientCase(self $clientCase): static
    {
        if (!$this->clientCases->contains($clientCase)) {
            $this->clientCases->add($clientCase);
            $clientCase->setParentCase($this);
        }

        return $this;
    }

    public function removeClientCase(self $clientCase): static
    {
        if ($this->clientCases->removeElement($clientCase)) {
            // set the owning side to null (unless already changed)
            if ($clientCase->getParentCase() === $this) {
                $clientCase->setParentCase(null);
            }
        }

        return $this;
    }

    public function getClientCaseStatus(): ?ClientCaseStatus
    {
        return $this->clientCaseStatus;
    }

    public function setClientCaseStatus(?ClientCaseStatus $clientCaseStatus): static
    {
        $this->clientCaseStatus = $clientCaseStatus;

        return $this;
    }

    /**
     * @return Collection<int, ProjectFeature>
     */
    public function getProjectFeatures(): Collection
    {
        return $this->projectFeatures;
    }

    public function addProjectFeature(ProjectFeature $projectFeature): static
    {
        if (!$this->projectFeatures->contains($projectFeature)) {
            $this->projectFeatures->add($projectFeature);
        }

        return $this;
    }

    public function removeProjectFeature(ProjectFeature $projectFeature): static
    {
        $this->projectFeatures->removeElement($projectFeature);

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setClientCase($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getClientCase() === $this) {
                $document->setClientCase(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setClientCase($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getClientCase() === $this) {
                $report->setClientCase(null);
            }
        }

        return $this;
    }

}

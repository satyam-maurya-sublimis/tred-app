<?php

namespace App\Entity\Transaction;

use App\Entity\Master\MstHighlights;
use App\Entity\Master\MstProductFeature;
use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstRating;
use App\Repository\Transaction\TrnProjectTowerDetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnProjectTowerDetailsRepository::class)
 * @ORM\Table("trnprojecttowerdetails")
 */
class TrnProjectTowerDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class, inversedBy="trnProjectTowerDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trnProject;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $towerName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $userIpAddress;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private $locationLatitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private $locationLongitude;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectTowerFloorPlan::class, mappedBy="trnProjectTowerDetails", orphanRemoval=true, cascade={"persist"})
     */
    private $trnProjectTowerFloorPlans;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $noOfFloors;

    /**
     * @ORM\ManyToMany(targetEntity=MstProductFeature::class)
     */
    private $mstTowerFeature;

    /**
     * @ORM\ManyToMany(targetEntity=MstProjectAmenities::class)
     */
    private $mstTowerAmenities;

    /**
     * @ORM\ManyToMany(targetEntity=MstHighlights::class)
     */
    private $mstTowerHighlights;

    /**
     * @ORM\ManyToOne(targetEntity=MstRating::class)
     */
    private $mstTowerRating;

    /**
     * @ORM\OneToMany(targetEntity=TrnUploadDocument::class, mappedBy="trnProjectTowerDetails", cascade={"persist"})
     */
    private $trnUploadDocuments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdOn;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectTowerAdditionalDetail::class, mappedBy="trnProjectTowerDetails", cascade={"persist","remove"})
     */
    private $trnProjectTowerAdditionalDetails;

    public function __construct()
    {
        $this->trnProjectTowerFloorPlans = new ArrayCollection();
        $this->mstTowerFeature = new ArrayCollection();
        $this->mstTowerAmenities = new ArrayCollection();
        $this->mstTowerHighlights = new ArrayCollection();
        $this->trnUploadDocuments = new ArrayCollection();
        $this->trnProjectTowerAdditionalDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrnProject(): ?TrnProject
    {
        return $this->trnProject;
    }

    public function setTrnProject(?TrnProject $trnProject): self
    {
        $this->trnProject = $trnProject;

        return $this;
    }

    public function getTowerName(): ?string
    {
        return $this->towerName;
    }

    public function setTowerName(string $towerName): self
    {
        $this->towerName = $towerName;

        return $this;
    }


    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUserIpAddress(): ?string
    {
        return $this->userIpAddress;
    }

    public function setUserIpAddress(string $userIpAddress): self
    {
        $this->userIpAddress = $userIpAddress;

        return $this;
    }

    public function getLocationLatitude(): ?string
    {
        return $this->locationLatitude;
    }

    public function setLocationLatitude(?string $locationLatitude): self
    {
        $this->locationLatitude = $locationLatitude;

        return $this;
    }

    public function getLocationLongitude(): ?string
    {
        return $this->locationLongitude;
    }

    public function setLocationLongitude(?string $locationLongitude): self
    {
        $this->locationLongitude = $locationLongitude;

        return $this;
    }

    /**
     * @return Collection|TrnProjectTowerFloorPlan[]
     */
    public function getTrnProjectTowerFloorPlans(): Collection
    {
        return $this->trnProjectTowerFloorPlans;
    }

    public function addTrnProjectTowerFloorPlan(TrnProjectTowerFloorPlan $trnProjectTowerFloorPlan): self
    {
        if (!$this->trnProjectTowerFloorPlans->contains($trnProjectTowerFloorPlan)) {
            $this->trnProjectTowerFloorPlans[] = $trnProjectTowerFloorPlan;
            $trnProjectTowerFloorPlan->setTrnProjectTowerDetails($this);
        }

        return $this;
    }

    public function removeTrnProjectTowerFloorPlan(TrnProjectTowerFloorPlan $trnProjectTowerFloorPlan): self
    {
        if ($this->trnProjectTowerFloorPlans->contains($trnProjectTowerFloorPlan)) {
            $this->trnProjectTowerFloorPlans->removeElement($trnProjectTowerFloorPlan);
            // set the owning side to null (unless already changed)
            if ($trnProjectTowerFloorPlan->getTrnProjectTowerDetails() === $this) {
                $trnProjectTowerFloorPlan->setTrnProjectTowerDetails(null);
            }
        }

        return $this;
    }

    public function getNoOfFloors(): ?string
    {
        return $this->noOfFloors;
    }

    public function setNoOfFloors(string $noOfFloors): self
    {
        $this->noOfFloors = $noOfFloors;

        return $this;
    }

    /**
     * @return Collection|MstProductFeature[]
     */
    public function getMstTowerFeature(): Collection
    {
        return $this->mstTowerFeature;
    }

    public function addMstTowerFeature(MstProductFeature $mstTowerFeature): self
    {
        if (!$this->mstTowerFeature->contains($mstTowerFeature)) {
            $this->mstTowerFeature[] = $mstTowerFeature;
        }

        return $this;
    }

    public function removeMstTowerFeature(MstProductFeature $mstTowerFeature): self
    {
        if ($this->mstTowerFeature->contains($mstTowerFeature)) {
            $this->mstTowerFeature->removeElement($mstTowerFeature);
        }

        return $this;
    }

    /**
     * @return Collection|MstProjectAmenities[]
     */
    public function getMstTowerAmenities(): Collection
    {
        return $this->mstTowerAmenities;
    }

    public function addMstTowerAmenity(MstProjectAmenities $mstTowerAmenity): self
    {
        if (!$this->mstTowerAmenities->contains($mstTowerAmenity)) {
            $this->mstTowerAmenities[] = $mstTowerAmenity;
        }

        return $this;
    }

    public function removeMstTowerAmenity(MstProjectAmenities $mstTowerAmenity): self
    {
        if ($this->mstTowerAmenities->contains($mstTowerAmenity)) {
            $this->mstTowerAmenities->removeElement($mstTowerAmenity);
        }

        return $this;
    }

    /**
     * @return Collection|MstHighlights[]
     */
    public function getMstTowerHighlights(): Collection
    {
        return $this->mstTowerHighlights;
    }

    public function addMstTowerHighlight(MstHighlights $mstTowerHighlight): self
    {
        if (!$this->mstTowerHighlights->contains($mstTowerHighlight)) {
            $this->mstTowerHighlights[] = $mstTowerHighlight;
        }

        return $this;
    }

    public function removeMstTowerHighlight(MstHighlights $mstTowerHighlight): self
    {
        if ($this->mstTowerHighlights->contains($mstTowerHighlight)) {
            $this->mstTowerHighlights->removeElement($mstTowerHighlight);
        }

        return $this;
    }

    public function getMstTowerRating(): ?MstRating
    {
        return $this->mstTowerRating;
    }

    public function setMstTowerRating(?MstRating $mstTowerRating): self
    {
        $this->mstTowerRating = $mstTowerRating;

        return $this;
    }

    /**
     * @return Collection|TrnUploadDocument[]
     */
    public function getTrnUploadDocuments(): Collection
    {
        return $this->trnUploadDocuments;
    }

    public function addTrnUploadDocument(TrnUploadDocument $trnUploadDocument): self
    {
        if (!$this->trnUploadDocuments->contains($trnUploadDocument)) {
            $this->trnUploadDocuments[] = $trnUploadDocument;
            $trnUploadDocument->setTrnProjectTowerDetails($this);
        }

        return $this;
    }

    public function removeTrnUploadDocument(TrnUploadDocument $trnUploadDocument): self
    {
        if ($this->trnUploadDocuments->contains($trnUploadDocument)) {
            $this->trnUploadDocuments->removeElement($trnUploadDocument);
            // set the owning side to null (unless already changed)
            if ($trnUploadDocument->getTrnProjectTowerDetails() === $this) {
                $trnUploadDocument->setTrnProjectTowerDetails(null);
            }
        }

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(?\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return Collection|TrnProjectTowerAdditionalDetail[]
     */
    public function getTrnProjectTowerAdditionalDetails(): Collection
    {
        return $this->trnProjectTowerAdditionalDetails;
    }

    public function addTrnProjectTowerAdditionalDetail(TrnProjectTowerAdditionalDetail $trnProjectTowerAdditionalDetail): self
    {
        if (!$this->trnProjectTowerAdditionalDetails->contains($trnProjectTowerAdditionalDetail)) {
            $this->trnProjectTowerAdditionalDetails[] = $trnProjectTowerAdditionalDetail;
            $trnProjectTowerAdditionalDetail->setTrnProjectTowerDetails($this);
        }

        return $this;
    }

    public function removeTrnProjectTowerAdditionalDetail(TrnProjectTowerAdditionalDetail $trnProjectTowerAdditionalDetail): self
    {
        if ($this->trnProjectTowerAdditionalDetails->removeElement($trnProjectTowerAdditionalDetail)) {
            // set the owning side to null (unless already changed)
            if ($trnProjectTowerAdditionalDetail->getTrnProjectTowerDetails() === $this) {
                $trnProjectTowerAdditionalDetail->setTrnProjectTowerDetails(null);
            }
        }

        return $this;
    }
}

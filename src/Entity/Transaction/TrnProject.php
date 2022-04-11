<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Master\MstAreaInCity;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstHighlights;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstPossession;
use App\Entity\Master\MstPreferredTenant;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductFeature;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstPropertyIn;
use App\Entity\Master\MstPropertyTransactionCategory;
use App\Entity\Master\MstPropertyType;
use App\Entity\Master\MstProjectType;
use App\Entity\Master\MstRating;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Master\MstState;
use App\Entity\Master\MstVendorType;
use App\Entity\Organization\OrgCompany;
use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnProjectRepository;
use App\Service\FileUploaderHelper;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}},
 *     collectionOperations={
 *          "get"={},
 *     },
 *     itemOperations={
 *          "get"={},
 *     }
 * )
 * @ORM\Entity(repositoryClass=TrnProjectRepository::class)
 * @ORM\Table("trnproject")
 */
class TrnProject
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $mstProductCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductType::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $mstProductType;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductSubType::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $mstProductSubType;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read"})
     */
    private $projectName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $projectOverview;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"read"})
     */
    private $phase;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"read"})
     */
    private $mahaReraRegisterationNo;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"read"})
     */
    private $totalNoOfTower;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"read"})
     */
    private $openSpacePercentage;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"read"})
     */
    private $approvedBy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"read"})
     */
    private $occupancyCerificate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"read"})
     */
    private $commencementCerificate;

    /**
     * @ORM\ManyToMany(targetEntity=MstProjectAmenities::class)
     * @Groups({"read"})
     */
    private $mstAmenities;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $mapView;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read"})
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity=MstHighlights::class)
     * @Groups({"read"})
     */
    private $mstProjectHighlights;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectTowerDetails::class, mappedBy="trnProject", orphanRemoval=true)
     * @Groups({"read"})
     */
    private $trnProjectTowerDetails;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectTowerFloorPlan::class, mappedBy="trnProject", orphanRemoval=true)
     * @Groups({"read"})
     */
    private $trnProjectTowerFloorPlans;

    /**
     * @ORM\ManyToOne(targetEntity=OrgCompany::class)
     */
    private $orgCompany;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    private $createdOn;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     * @Groups({"read"})
     */
    private $locationLatitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     * @Groups({"read"})
     */
    private $locationLongitude;

    /**
     * @ORM\ManyToOne(targetEntity=TrnVendorPartnerDetails::class, inversedBy="trnProjects")
     * @Groups({"read"})
     */
    private $trnVendorPartnerDetails;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $possessionNote;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectArea::class)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read"})
     */
    private $mstProjectAreaSuperArea;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"read"})
     */
    private $superArea;

    /**
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read"})
     */
    private $mstSuperAreaCurrency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"read"})
     */
    private $superAreaPricePer;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectArea::class)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read"})
     */
    private $mstProjectAreaCarpetArea;

    /**
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read"})
     */
    private $mstCurrencyCarpetArea;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"read"})
     */
    private $carpetAreaPricePer;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $loading;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read"})
     */
    private $carParking;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"read"})
     */
    private $bankApproved;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read"})
     */
    private $totalNoUnits;

    /**
     * @ORM\ManyToMany(targetEntity=MstProductFeature::class)
     * @Groups({"read"})
     */
    private $mstProjectFeature;

    /**
     * @ORM\ManyToOne(targetEntity=MstRating::class)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read"})
     */
    private $mstProjectRating;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectType::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $mstPropertyType;

    /**
     * @ORM\ManyToOne(targetEntity=MstCountry::class)
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $mstCountry;

    /**
     * @ORM\ManyToOne(targetEntity=MstState::class)
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $mstState;

    /**
     * @ORM\ManyToOne(targetEntity=MstCity::class)
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $mstCity;

    /**
     * @ORM\ManyToOne(targetEntity=MstAreaInCity::class)
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read"})
     */
    private $mstAreaInCity;

    /**
     * @ORM\ManyToOne(targetEntity=MstPropertyType::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $mstProjectStatus;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"read"})
     */
    private $carpetArea;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $canonicalUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $metaTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read"})
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read"})
     */
    private $metaKeyword;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read"})
     */
    private $focusKeyPhrase;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read"})
     */
    private $keyPhraseSynonyms;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read"})
     */
    private $seoSchema;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $ogTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read"})
     */
    private $ogDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $ogType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $ogImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $ogImagePath;

    /**
     * @ORM\OneToMany(targetEntity=TrnUploadDocument::class, mappedBy="trnProject", cascade={"persist","remove"})
     *
     */
    private $trnUploadDocument;

    /**
     * @ORM\ManyToMany(targetEntity=MstRoomConfiguration::class)
     * @Groups({"read"})
     */
    private $mstRoomConfiguration;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectAdditionalDetail::class, mappedBy="trnProject", cascade={"persist","remove"})
     */
    private $trnProjectAdditionalDetail;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectAreaDetail::class, mappedBy="trnProject", cascade={"persist","remove"})
     */
    private $trnProjectAreaDetail;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectRoomConfiguration::class, mappedBy="trnProject", cascade={"persist","remove"})
     */
    private $trnProjectRoomConfigurations;

    /**
     * @ORM\ManyToOne(targetEntity=MstPossession::class, inversedBy="trnProjects")
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstPossession;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isRera;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTredRecommended;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isNewProperty;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $projectLikes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $projectViews;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectAmenities::class, mappedBy="trnProject", cascade={"persist", "remove"})
     */
    private $trnProjectAmenities;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $brochureName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brochure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brochurePath;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFeatured;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $possessionDate;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $possessionYear;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $possessionMonth;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $availabilityFromDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $noOfLifts;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $propertyAge;

    /**
     * @ORM\ManyToMany(targetEntity=MstPreferredTenant::class, mappedBy="mstProjects")
     */
    private $mstPreferredTenants;

    /**
     * @ORM\ManyToOne(targetEntity=MstPropertyIn::class)
     */
    private $mstPropertyIn;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $electricityStatus;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $waterAvailibility;

    /**
     * @ORM\ManyToOne(targetEntity=MstVendorType::class)
     */
    private $mstVendorType;

    /**
     * @ORM\ManyToOne(targetEntity=MstPincode::class)
     */
    private $mstPincode;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $actualPossessionDate;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity=TrnProjectFeedback::class, mappedBy="trnProjects")
     */
    private $trnProjectFeedback;

    public function __construct()
    {
        $this->mstAmenities = new ArrayCollection();
        $this->mstProjectHighlights = new ArrayCollection();
        $this->trnProjectTowerDetails = new ArrayCollection();
        $this->trnProjectTowerFloorPlans = new ArrayCollection();
        $this->mstProjectFeature = new ArrayCollection();
        $this->trnUploadDocument = new ArrayCollection();
        $this->mstRoomConfiguration = new ArrayCollection();
        $this->trnProjectAdditionalDetail = new ArrayCollection();
        $this->trnProjectAreaDetail = new ArrayCollection();
        $this->trnProjectRoomConfigurations = new ArrayCollection();
        $this->trnProjectAmenities = new ArrayCollection();
        $this->mstPreferredTenants = new ArrayCollection();
        $this->trnProjectFeedback = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMstProductCategory(): ?MstProductCategory
    {
        return $this->mstProductCategory;
    }

    public function setMstProductCategory(?MstProductCategory $mstProductCategory): self
    {
        $this->mstProductCategory = $mstProductCategory;

        return $this;
    }

    public function getMstProductType(): ?MstProductType
    {
        return $this->mstProductType;
    }

    public function setMstProductType(?MstProductType $mstProductType): self
    {
        $this->mstProductType = $mstProductType;

        return $this;
    }

    public function getMstProductSubType(): ?MstProductSubType
    {
        return $this->mstProductSubType;
    }

    public function setMstProductSubType(?MstProductSubType $mstProductSubType): self
    {
        $this->mstProductSubType = $mstProductSubType;

        return $this;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(string $projectName): self
    {
        $this->projectName = $projectName;

        return $this;
    }

    public function getProjectOverview(): ?string
    {
        return $this->projectOverview;
    }

    public function setProjectOverview(string $projectOverview): self
    {
        $this->projectOverview = $projectOverview;

        return $this;
    }

    public function getPhase(): ?string
    {
        return $this->phase;
    }

    public function setPhase(string $phase): self
    {
        $this->phase = $phase;

        return $this;
    }

    public function getMahaReraRegisterationNo(): ?string
    {
        return $this->mahaReraRegisterationNo;
    }

    public function setMahaReraRegisterationNo(?string $mahaReraRegisterationNo): self
    {
        $this->mahaReraRegisterationNo = $mahaReraRegisterationNo;

        return $this;
    }

    public function getTotalNoOfTower(): ?int
    {
        return $this->totalNoOfTower;
    }

    public function setTotalNoOfTower(?int $totalNoOfTower): self
    {
        $this->totalNoOfTower = $totalNoOfTower;

        return $this;
    }

    public function getOpenSpacePercentage(): ?string
    {
        return $this->openSpacePercentage;
    }

    public function setOpenSpacePercentage(string $openSpacePercentage): self
    {
        $this->openSpacePercentage = $openSpacePercentage;

        return $this;
    }

    public function getApprovedBy(): ?string
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(string $approvedBy): self
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    public function getOccupancyCerificate(): ?bool
    {
        return $this->occupancyCerificate;
    }

    public function setOccupancyCerificate(bool $occupancyCerificate): self
    {
        $this->occupancyCerificate = $occupancyCerificate;

        return $this;
    }

    public function getCommencementCerificate(): ?bool
    {
        return $this->commencementCerificate;
    }

    public function setCommencementCerificate(bool $commencementCerificate): self
    {
        $this->commencementCerificate = $commencementCerificate;

        return $this;
    }

    /**
     * @return Collection|MstProjectAmenities[]
     */
    public function getMstAmenities(): Collection
    {
        return $this->mstAmenities;
    }

    public function addMstAmenity(MstProjectAmenities $mstAmenity): self
    {
        if (!$this->mstAmenities->contains($mstAmenity)) {
            $this->mstAmenities[] = $mstAmenity;
        }

        return $this;
    }

    public function removeMstAmenity(MstProjectAmenities $mstAmenity): self
    {
        if ($this->mstAmenities->contains($mstAmenity)) {
            $this->mstAmenities->removeElement($mstAmenity);
        }

        return $this;
    }

    public function getMapView(): ?string
    {
        return $this->mapView;
    }

    public function setMapView(?string $mapView): self
    {
        $this->mapView = $mapView;

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

    /**
     * @return Collection|MstHighlights[]
     */
    public function getMstProjectHighlights(): Collection
    {
        return $this->mstProjectHighlights;
    }

    public function addMstProjectHighlight(MstHighlights $mstProjectHighlight): self
    {
        if (!$this->mstProjectHighlights->contains($mstProjectHighlight)) {
            $this->mstProjectHighlights[] = $mstProjectHighlight;
        }

        return $this;
    }

    public function removeMstProjectHighlight(MstHighlights $mstProjectHighlight): self
    {
        if ($this->mstProjectHighlights->contains($mstProjectHighlight)) {
            $this->mstProjectHighlights->removeElement($mstProjectHighlight);
        }

        return $this;
    }

    /**
     * @return Collection|TrnProjectTowerDetails[]
     */
    public function getTrnProjectTowerDetails(): Collection
    {
        return $this->trnProjectTowerDetails;
    }

    public function addTrnProjectTowerDetail(TrnProjectTowerDetails $trnProjectTowerDetail): self
    {
        if (!$this->trnProjectTowerDetails->contains($trnProjectTowerDetail)) {
            $this->trnProjectTowerDetails[] = $trnProjectTowerDetail;
            $trnProjectTowerDetail->setTrnProject($this);
        }

        return $this;
    }

    public function removeTrnProjectTowerDetail(TrnProjectTowerDetails $trnProjectTowerDetail): self
    {
        if ($this->trnProjectTowerDetails->contains($trnProjectTowerDetail)) {
            $this->trnProjectTowerDetails->removeElement($trnProjectTowerDetail);
            // set the owning side to null (unless already changed)
            if ($trnProjectTowerDetail->getTrnProject() === $this) {
                $trnProjectTowerDetail->setTrnProject(null);
            }
        }

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
            $trnProjectTowerFloorPlan->setTrnProject($this);
        }

        return $this;
    }

    public function removeTrnProjectTowerFloorPlan(TrnProjectTowerFloorPlan $trnProjectTowerFloorPlan): self
    {
        if ($this->trnProjectTowerFloorPlans->contains($trnProjectTowerFloorPlan)) {
            $this->trnProjectTowerFloorPlans->removeElement($trnProjectTowerFloorPlan);
            // set the owning side to null (unless already changed)
            if ($trnProjectTowerFloorPlan->getTrnProject() === $this) {
                $trnProjectTowerFloorPlan->setTrnProject(null);
            }
        }

        return $this;
    }

    public function getOrgCompany(): ?OrgCompany
    {
        return $this->orgCompany;
    }

    public function setOrgCompany(?OrgCompany $orgCompany): self
    {
        $this->orgCompany = $orgCompany;

        return $this;
    }

    public function getCreatedOn(): ?DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

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

    public function getTrnVendorPartnerDetails(): ?TrnVendorPartnerDetails
    {
        return $this->trnVendorPartnerDetails;
    }

    public function setTrnVendorPartnerDetails(?TrnVendorPartnerDetails $trnVendorPartnerDetails): self
    {
        $this->trnVendorPartnerDetails = $trnVendorPartnerDetails;

        return $this;
    }

    public function getPossessionNote(): ?string
    {
        return $this->possessionNote;
    }

    public function setPossessionNote(?string $possessionNote): self
    {
        $this->possessionNote = $possessionNote;

        return $this;
    }

    public function getMstProjectAreaSuperArea(): ?MstProjectArea
    {
        return $this->mstProjectAreaSuperArea;
    }

    public function setMstProjectAreaSuperArea(?MstProjectArea $mstProjectAreaSuperArea): self
    {
        $this->mstProjectAreaSuperArea = $mstProjectAreaSuperArea;

        return $this;
    }

    public function getSuperArea(): ?string
    {
        return $this->superArea;
    }

    public function setSuperArea(?string $superArea): self
    {
        $this->superArea = $superArea;

        return $this;
    }

    public function getMstSuperAreaCurrency(): ?MstCurrency
    {
        return $this->mstSuperAreaCurrency;
    }

    public function setMstSuperAreaCurrency(?MstCurrency $mstSuperAreaCurrency): self
    {
        $this->mstSuperAreaCurrency = $mstSuperAreaCurrency;

        return $this;
    }

    public function getSuperAreaPricePer(): ?string
    {
        return $this->superAreaPricePer;
    }

    public function setSuperAreaPricePer(?string $superAreaPricePer): self
    {
        $this->superAreaPricePer = $superAreaPricePer;

        return $this;
    }

    public function getMstProjectAreaCarpetArea(): ?MstProjectArea
    {
        return $this->mstProjectAreaCarpetArea;
    }

    public function setMstProjectAreaCarpetArea(?MstProjectArea $mstProjectAreaCarpetArea): self
    {
        $this->mstProjectAreaCarpetArea = $mstProjectAreaCarpetArea;

        return $this;
    }

    public function getMstCurrencyCarpetArea(): ?MstCurrency
    {
        return $this->mstCurrencyCarpetArea;
    }

    public function setMstCurrencyCarpetArea(?MstCurrency $mstCurrencyCarpetArea): self
    {
        $this->mstCurrencyCarpetArea = $mstCurrencyCarpetArea;

        return $this;
    }

    public function getCarpetAreaPricePer(): ?string
    {
        return $this->carpetAreaPricePer;
    }

    public function setCarpetAreaPricePer(?string $carpetAreaPricePer): self
    {
        $this->carpetAreaPricePer = $carpetAreaPricePer;

        return $this;
    }

    public function getLoading(): ?string
    {
        return $this->loading;
    }

    public function setLoading(?string $loading): self
    {
        $this->loading = $loading;

        return $this;
    }

    public function getCarParking(): ?string
    {
        return $this->carParking;
    }

    public function setCarParking(string $carParking): self
    {
        $this->carParking = $carParking;

        return $this;
    }

    public function getBankApproved(): ?bool
    {
        return $this->bankApproved;
    }

    public function setBankApproved(?bool $bankApproved): self
    {
        $this->bankApproved = $bankApproved;

        return $this;
    }

    public function getTotalNoUnits(): ?int
    {
        return $this->totalNoUnits;
    }

    public function setTotalNoUnits(?int $totalNoUnits): self
    {
        $this->totalNoUnits = $totalNoUnits;

        return $this;
    }

    /**
     * @return Collection|MstProductFeature[]
     */
    public function getMstProjectFeature(): Collection
    {
        return $this->mstProjectFeature;
    }

    public function addMstProjectFeature(MstProductFeature $mstProjectFeature): self
    {
        if (!$this->mstProjectFeature->contains($mstProjectFeature)) {
            $this->mstProjectFeature[] = $mstProjectFeature;
        }

        return $this;
    }

    public function removeMstProjectFeature(MstProductFeature $mstProjectFeature): self
    {
        if ($this->mstProjectFeature->contains($mstProjectFeature)) {
            $this->mstProjectFeature->removeElement($mstProjectFeature);
        }

        return $this;
    }

    public function getMstProjectRating(): ?MstRating
    {
        return $this->mstProjectRating;
    }

    public function setMstProjectRating(?MstRating $mstProjectRating): self
    {
        $this->mstProjectRating = $mstProjectRating;

        return $this;
    }

    public function getMstPropertyType(): ?MstProjectType
    {
        return $this->mstPropertyType;
    }

    public function setMstPropertyType(?MstProjectType $mstPropertyType): self
    {
        $this->mstPropertyType = $mstPropertyType;

        return $this;
    }

    public function getMstCountry(): ?MstCountry
    {
        return $this->mstCountry;
    }

    public function setMstCountry(?MstCountry $mstCountry): self
    {
        $this->mstCountry = $mstCountry;

        return $this;
    }

    public function getMstState(): ?MstState
    {
        return $this->mstState;
    }

    public function setMstState(?MstState $mstState): self
    {
        $this->mstState = $mstState;

        return $this;
    }

    public function getMstCity(): ?MstCity
    {
        return $this->mstCity;
    }

    public function setMstCity(?MstCity $mstCity): self
    {
        $this->mstCity = $mstCity;

        return $this;
    }

    public function getMstAreaInCity(): ?MstAreaInCity
    {
        return $this->mstAreaInCity;
    }

    public function setMstAreaInCity(?MstAreaInCity $mstAreaInCity): self
    {
        $this->mstAreaInCity = $mstAreaInCity;

        return $this;
    }

    public function getMstProjectStatus(): ?MstPropertyType
    {
        return $this->mstProjectStatus;
    }

    public function setMstProjectStatus(?MstPropertyType $mstProjectStatus): self
    {
        $this->mstProjectStatus = $mstProjectStatus;

        return $this;
    }

    public function getCarpetArea(): ?string
    {
        return $this->carpetArea;
    }

    public function setCarpetArea(?string $carpetArea): self
    {
        $this->carpetArea = $carpetArea;

        return $this;
    }

    /**
     * @return Collection|TrnUploadDocument[]
     */
    public function getTrnUploadDocument(): Collection
    {
        return $this->trnUploadDocument;
    }

    public function addTrnUploadDocument(TrnUploadDocument $trnUploadDocument): self
    {
        if (!$this->trnUploadDocument->contains($trnUploadDocument)) {
            $this->trnUploadDocument[] = $trnUploadDocument;
            $trnUploadDocument->setTrnProject($this);
        }

        return $this;
    }

    public function removeTrnUploadDocument(TrnUploadDocument $trnUploadDocument): self
    {
        if ($this->trnUploadDocument->contains($trnUploadDocument)) {
            $this->trnUploadDocument->removeElement($trnUploadDocument);
            // set the owning side to null (unless already changed)
            if ($trnUploadDocument->getTrnProject() === $this) {
                $trnUploadDocument->setTrnProject(null);
            }
        }

        return $this;
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function setCanonicalUrl(string $canonicalUrl): self
    {
        $this->canonicalUrl = $canonicalUrl;
        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    public function getMetaKeyword(): ?string
    {
        return $this->metaKeyword;
    }

    public function setMetaKeyword(?string $metaKeyword): self
    {
        $this->metaKeyword = $metaKeyword;
        return $this;
    }

    public function getFocusKeyPhrase(): ?string
    {
        return $this->focusKeyPhrase;
    }

    public function setFocusKeyPhrase(?string $focusKeyPhrase): self
    {
        $this->focusKeyPhrase = $focusKeyPhrase;
        return $this;
    }

    public function getKeyPhraseSynonyms(): ?string
    {
        return $this->keyPhraseSynonyms;
    }

    public function setKeyPhraseSynonyms(?string $keyPhraseSynonyms): self
    {
        $this->keyPhraseSynonyms = $keyPhraseSynonyms;
        return $this;
    }

    public function getSeoSchema(): ?string
    {
        return $this->seoSchema;
    }

    public function setSeoSchema(?string $seoSchema): self
    {
        $this->seoSchema = $seoSchema;
        return $this;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function setOgTitle(?string $ogTitle): self
    {
        $this->ogTitle = $ogTitle;
        return $this;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }

    public function setOgDescription(?string $ogDescription): self
    {
        $this->ogDescription = $ogDescription;
        return $this;
    }

    public function getOgType(): ?string
    {
        return $this->ogType;
    }

    public function setOgType(?string $ogType): self
    {
        $this->ogType = $ogType;
        return $this;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function setOgImage(string $ogImage): self
    {
        $this->ogImage = $ogImage;
        return $this;
    }

    public function getOgImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getOgImage();
    }

    public function setOgImagePath(string $ogImagePath): self
    {
        $this->ogImagePath = $ogImagePath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString() {
        return $this->projectName;
    }

    /**
     * @return Collection|MstRoomConfiguration[]
     */
    public function getMstRoomConfiguration(): Collection
    {
        return $this->mstRoomConfiguration;
    }

    public function addMstRoomConfiguration(MstRoomConfiguration $mstRoomConfiguration): self
    {
        if (!$this->mstRoomConfiguration->contains($mstRoomConfiguration)) {
            $this->mstRoomConfiguration[] = $mstRoomConfiguration;
        }

        return $this;
    }

    public function removeMstRoomConfiguration(MstRoomConfiguration $mstRoomConfiguration): self
    {
        if ($this->mstRoomConfiguration->contains($mstRoomConfiguration)) {
            $this->mstRoomConfiguration->removeElement($mstRoomConfiguration);
        }

        return $this;
    }

    /**
     * @return Collection|TrnProjectAdditionalDetail[]
     */
    public function getTrnProjectAdditionalDetail(): Collection
    {
        return $this->trnProjectAdditionalDetail;
    }

    public function addTrnProjectAdditionalDetail(TrnProjectAdditionalDetail $trnProjectAdditionalDetail): self
    {
        if (!$this->trnProjectAdditionalDetail->contains($trnProjectAdditionalDetail)) {
            $this->trnProjectAdditionalDetail[] = $trnProjectAdditionalDetail;
            $trnProjectAdditionalDetail->setTrnProject($this);
        }

        return $this;
    }

    public function removeTrnProjectAdditionalDetail(TrnProjectAdditionalDetail $trnProjectAdditionalDetail): self
    {
        if ($this->trnProjectAdditionalDetail->removeElement($trnProjectAdditionalDetail)) {
            // set the owning side to null (unless already changed)
            if ($trnProjectAdditionalDetail->getTrnProject() === $this) {
                $trnProjectAdditionalDetail->setTrnProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrnProjectAreaDetail[]
     */
    public function getTrnProjectAreaDetail(): Collection
    {
        return $this->trnProjectAreaDetail;
    }

    public function addTrnProjectAreaDetail(TrnProjectAreaDetail $trnProjectAreaDetail): self
    {
        if (!$this->trnProjectAreaDetail->contains($trnProjectAreaDetail)) {
            $this->trnProjectAreaDetail[] = $trnProjectAreaDetail;
            $trnProjectAreaDetail->setTrnProject($this);
        }

        return $this;
    }

    public function removeTrnProjectAreaDetail(TrnProjectAreaDetail $trnProjectAreaDetail): self
    {
        if ($this->trnProjectAreaDetail->removeElement($trnProjectAreaDetail)) {
            // set the owning side to null (unless already changed)
            if ($trnProjectAreaDetail->getTrnProject() === $this) {
                $trnProjectAreaDetail->setTrnProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrnProjectRoomConfiguration[]
     */
    public function getTrnProjectRoomConfigurations(): Collection
    {
        return $this->trnProjectRoomConfigurations;
    }

    public function addTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        if (!$this->trnProjectRoomConfigurations->contains($trnProjectRoomConfiguration)) {
            $this->trnProjectRoomConfigurations[] = $trnProjectRoomConfiguration;
            $trnProjectRoomConfiguration->setTrnProject($this);
        }

        return $this;
    }

    public function removeTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        if ($this->trnProjectRoomConfigurations->removeElement($trnProjectRoomConfiguration)) {
            // set the owning side to null (unless already changed)
            if ($trnProjectRoomConfiguration->getTrnProject() === $this) {
                $trnProjectRoomConfiguration->setTrnProject(null);
            }
        }

        return $this;
    }

    public function getMstPossession(): ?MstPossession
    {
        return $this->mstPossession;
    }

    public function setMstPossession(?MstPossession $mstPossession): self
    {
        $this->mstPossession = $mstPossession;

        return $this;
    }

    public function getIsRera(): ?bool
    {
        return $this->isRera;
    }

    public function setIsRera(?bool $isRera): self
    {
        $this->isRera = $isRera;

        return $this;
    }

    public function getIsTredRecommended(): ?bool
    {
        return $this->isTredRecommended;
    }

    public function setIsTredRecommended(?bool $isTredRecommended): self
    {
        $this->isTredRecommended = $isTredRecommended;

        return $this;
    }

    public function getIsNewProperty(): ?bool
    {
        return $this->isNewProperty;
    }

    public function setIsNewProperty(?bool $isNewProperty): self
    {
        $this->isNewProperty = $isNewProperty;

        return $this;
    }

    public function getProjectLikes(): ?int
    {
        return $this->projectLikes;
    }

    public function setProjectLikes(?int $projectLikes): self
    {
        $this->projectLikes = $projectLikes;

        return $this;
    }

    public function getProjectViews(): ?int
    {
        return $this->projectViews;
    }

    public function setProjectViews(?int $projectViews): self
    {
        $this->projectViews = $projectViews;

        return $this;
    }

    /**
     * @return Collection|TrnProjectAmenities[]
     */
    public function getTrnProjectAmenities(): Collection
    {
        return $this->trnProjectAmenities;
    }

    public function addTrnProjectAmenity(TrnProjectAmenities $trnProjectAmenity): self
    {
        if (!$this->trnProjectAmenities->contains($trnProjectAmenity)) {
            $this->trnProjectAmenities[] = $trnProjectAmenity;
            $trnProjectAmenity->setTrnProject($this);
        }

        return $this;
    }

    public function removeTrnProjectAmenity(TrnProjectAmenities $trnProjectAmenity): self
    {
        if ($this->trnProjectAmenities->removeElement($trnProjectAmenity)) {
            // set the owning side to null (unless already changed)
            if ($trnProjectAmenity->getTrnProject() === $this) {
                $trnProjectAmenity->setTrnProject(null);
            }
        }

        return $this;
    }

    public function getBrochureName(): ?string
    {
        return $this->brochureName;
    }

    public function setBrochureName(?string $brochureName): self
    {
        $this->brochureName = $brochureName;

        return $this;
    }

    public function getBrochure(): ?string
    {
        return $this->brochure;
    }

    public function setBrochure(?string $brochure): self
    {
        $this->brochure = $brochure;

        return $this;
    }

    public function getBrochurePath(): ?string
    {

        return $this->brochurePath.'/'.$this->getBrochure();
    }

    public function setBrochurePath(?string $brochurePath): self
    {
        $this->brochurePath = $brochurePath;

        return $this;
    }

    public function getIsFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(?bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    public function getPossessionDate(): ?\DateTimeInterface
    {
        return $this->possessionDate;
    }

    public function setPossessionDate(?\DateTimeInterface $possessionDate): self
    {
        $this->possessionDate = $possessionDate;

        return $this;
    }

    public function getPossessionYear(): ?string
    {
        return $this->possessionYear;
    }

    public function setPossessionYear(?string $possessionYear): self
    {
        $this->possessionYear = $possessionYear;

        return $this;
    }

    public function getPossessionMonth(): ?string
    {
        return $this->possessionMonth;
    }

    public function setPossessionMonth(?string $possessionMonth): self
    {
        $this->possessionMonth = $possessionMonth;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAvailabilityFromDate(): ?\DateTimeInterface
    {
        return $this->availabilityFromDate;
    }

    public function setAvailabilityFromDate(?\DateTimeInterface $availabilityFromDate): self
    {
        $this->availabilityFromDate = $availabilityFromDate;

        return $this;
    }

    public function getNoOfLifts(): ?int
    {
        return $this->noOfLifts;
    }

    public function setNoOfLifts(?int $noOfLifts): self
    {
        $this->noOfLifts = $noOfLifts;

        return $this;
    }

    public function getPropertyAge(): ?string
    {
        return $this->propertyAge;
    }

    public function setPropertyAge(?string $propertyAge): self
    {
        $this->propertyAge = $propertyAge;

        return $this;
    }

    /**
     * @return Collection|MstPreferredTenant[]
     */
    public function getMstPreferredTenants(): Collection
    {
        return $this->mstPreferredTenants;
    }

    public function addMstPreferredTenant(MstPreferredTenant $mstPreferredTenant): self
    {
        if (!$this->mstPreferredTenants->contains($mstPreferredTenant)) {
            $this->mstPreferredTenants[] = $mstPreferredTenant;
            $mstPreferredTenant->addMstProject($this);
        }

        return $this;
    }

    public function removeMstPreferredTenant(MstPreferredTenant $mstPreferredTenant): self
    {
        if ($this->mstPreferredTenants->removeElement($mstPreferredTenant)) {
            $mstPreferredTenant->removeMstProject($this);
        }

        return $this;
    }

    public function getMstPropertyIn(): ?MstPropertyIn
    {
        return $this->mstPropertyIn;
    }

    public function setMstPropertyIn(?MstPropertyIn $mstPropertyIn): self
    {
        $this->mstPropertyIn = $mstPropertyIn;

        return $this;
    }

    public function getElectricityStatus(): ?string
    {
        return $this->electricityStatus;
    }

    public function setElectricityStatus(?string $electricityStatus): self
    {
        $this->electricityStatus = $electricityStatus;

        return $this;
    }

    public function getWaterAvailibility(): ?string
    {
        return $this->waterAvailibility;
    }

    public function setWaterAvailibility(?string $waterAvailibility): self
    {
        $this->waterAvailibility = $waterAvailibility;

        return $this;
    }

    public function getMstVendorType(): ?MstVendorType
    {
        return $this->mstVendorType;
    }

    public function setMstVendorType(?MstVendorType $mstVendorType): self
    {
        $this->mstVendorType = $mstVendorType;

        return $this;
    }

    public function getMstPincode(): ?MstPincode
    {
        return $this->mstPincode;
    }

    public function setMstPincode(?MstPincode $mstPincode): self
    {
        $this->mstPincode = $mstPincode;

        return $this;
    }

    public function getActualPossessionDate(): ?\DateTimeInterface
    {
        return $this->actualPossessionDate;
    }

    public function setActualPossessionDate(?\DateTimeInterface $actualPossessionDate): self
    {
        $this->actualPossessionDate = $actualPossessionDate;

        return $this;
    }

    public function getCreatedBy(): ?AppUser
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?AppUser $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|TrnProjectFeedback[]
     */
    public function getTrnProjectFeedback(): Collection
    {
        return $this->trnProjectFeedback;
    }

    public function addTrnProjectFeedback(TrnProjectFeedback $trnProjectFeedback): self
    {
        if (!$this->trnProjectFeedback->contains($trnProjectFeedback)) {
            $this->trnProjectFeedback[] = $trnProjectFeedback;
            $trnProjectFeedback->setTrnProjects($this);
        }

        return $this;
    }

    public function removeTrnProjectFeedback(TrnProjectFeedback $trnProjectFeedback): self
    {
        if ($this->trnProjectFeedback->removeElement($trnProjectFeedback)) {
            // set the owning side to null (unless already changed)
            if ($trnProjectFeedback->getTrnProjects() === $this) {
                $trnProjectFeedback->setTrnProjects(null);
            }
        }

        return $this;
    }
}

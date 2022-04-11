<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstDenomination;
use App\Entity\Master\MstDeviceType;
use App\Entity\Master\MstFacing;
use App\Entity\Master\MstFurnishing;
use App\Entity\Master\MstPreferredTenant;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstProjectAreaCategory;
use App\Entity\Master\MstPropertySaleCategory;
use App\Entity\Master\MstPropertyTransactionCategory;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Master\MstUploadDocumentType;
use App\Entity\Seo\SeoContent;
use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnProjectRoomConfigurationRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TrnProjectRoomConfigurationRepository::class)
 * @ORM\Table("trnprojectroomconfiguration")
 */
class TrnProjectRoomConfiguration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstUploadDocumentType::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstUploadDocumentType;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mediaFileName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mediaName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mediaAltText;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mediaTitle;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $mediaURL;

    /**
     * @ORM\ManyToOne(targetEntity=MstRoomConfiguration::class)
     */
    private $mstRoomConfiguration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $noOfBedRoom;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $noOfBathRooms;

    /**
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     */
    private $mstCurrency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $agreementAmount;

    /**
     * @ORM\ManyToOne(targetEntity=MstDenomination::class)
     */
    private $mstDenomination;

    /**
     * @ORM\ManyToOne(targetEntity=MstDeviceType::class)
     */
    private $mstDeviceType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaFilePath;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $noOfBalcony;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class, inversedBy="trnProjectRoomConfigurations")
     */
    private $trnProject;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectArea::class)
     */
    private $mstProjectArea;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectAreaCategory::class)
     */
    private $mstProjectAreaCategory;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $areaValue;

    /**
     * @ORM\ManyToOne(targetEntity=MstPropertyTransactionCategory::class)
     */
    private $mstPropertyTransactionCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstFacing::class)
     */
    private $mstFacing;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $rentPerMonth;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $deposit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isNegotiable;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $floor;

    /**
     * @ORM\ManyToOne(targetEntity=MstFurnishing::class, inversedBy="trnProjectRoomConfigurations")
     */
    private $mstFurnishing;

    /**
     * @ORM\ManyToOne(targetEntity=MstPropertySaleCategory::class, inversedBy="trnProjectRoomConfiguration")
     */
    private $mstPropertySaleCategory;

    /**
     * @ORM\ManyToMany(targetEntity=MstPreferredTenant::class, inversedBy="trnProjectRoomConfigurations")
     */
    private $mstPreferredTenant;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $roomConfigurationLikes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $roomConfigurationViews;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $createdBy;

    /**
     * @ORM\OneToOne(targetEntity=SeoContent::class, inversedBy="trnProjectRoomConfiguration", cascade={"persist", "remove"})
     */
    private $seoContent;

    /**
     * @ORM\ManyToMany(targetEntity=TrnProjectAdditionalDetail::class, mappedBy="trnProjectRoomConfigurations")
     */
    private $trnProjectAdditionalDetails;

    /**
     * @ORM\ManyToMany(targetEntity=TrnUploadDocument::class, mappedBy="trnProjectRoomConfigurations")
     */
    private $trnUploadDocuments;

    public function __construct()
    {
        $this->mstPreferredTenant = new ArrayCollection();
        $this->trnProjectAdditionalDetails = new ArrayCollection();
        $this->trnUploadDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMstUploadDocumentType(): ?MstUploadDocumentType
    {
        return $this->mstUploadDocumentType;
    }

    public function setMstUploadDocumentType(?MstUploadDocumentType $mstUploadDocumentType): self
    {
        $this->mstUploadDocumentType = $mstUploadDocumentType;

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

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getMediaFileName(): ?string
    {
        return $this->mediaFileName;
    }

    public function setMediaFileName(?string $mediaFileName): self
    {
        $this->mediaFileName = $mediaFileName;

        return $this;
    }

    public function getMediaName(): ?string
    {
        return $this->mediaName;
    }

    public function setMediaName(string $mediaName): self
    {
        $this->mediaName = $mediaName;

        return $this;
    }

    public function getMediaAltText(): ?string
    {
        return $this->mediaAltText;
    }

    public function setMediaAltText(?string $mediaAltText): self
    {
        $this->mediaAltText = $mediaAltText;

        return $this;
    }

    public function getMediaTitle(): ?string
    {
        return $this->mediaTitle;
    }

    public function setMediaTitle(?string $mediaTitle): self
    {
        $this->mediaTitle = $mediaTitle;

        return $this;
    }

    public function getMediaURL(): ?string
    {
        return $this->mediaURL;
    }

    public function setMediaURL(?string $mediaURL): self
    {
        $this->mediaURL = $mediaURL;

        return $this;
    }

    public function getMstRoomConfiguration(): ?MstRoomConfiguration
    {
        return $this->mstRoomConfiguration;
    }

    public function setMstRoomConfiguration(?MstRoomConfiguration $mstRoomConfiguration): self
    {
        $this->mstRoomConfiguration = $mstRoomConfiguration;

        return $this;
    }

    public function getNoOfBedRoom(): ?int
    {
        return $this->noOfBedRoom;
    }

    public function setNoOfBedRoom(?int $noOfBedRoom): self
    {
        $this->noOfBedRoom = $noOfBedRoom;

        return $this;
    }

    public function getNoOfBathRooms(): ?int
    {
        return $this->noOfBathRooms;
    }

    public function setNoOfBathRooms(?int $noOfBathRooms): self
    {
        $this->noOfBathRooms = $noOfBathRooms;

        return $this;
    }

    public function getMstCurrency(): ?MstCurrency
    {
        return $this->mstCurrency;
    }

    public function setMstCurrency(?MstCurrency $mstCurrency): self
    {
        $this->mstCurrency = $mstCurrency;

        return $this;
    }

    public function getAgreementAmount(): ?string
    {
        return $this->agreementAmount;
    }

    public function setAgreementAmount(?string $agreementAmount): self
    {
        $this->agreementAmount = $agreementAmount;

        return $this;
    }

    public function getMstDenomination(): ?MstDenomination
    {
        return $this->mstDenomination;
    }

    public function setMstDenomination(?MstDenomination $mstDenomination): self
    {
        $this->mstDenomination = $mstDenomination;

        return $this;
    }

    public function getMstDeviceType(): ?MstDeviceType
    {
        return $this->mstDeviceType;
    }

    public function setMstDeviceType(?MstDeviceType $mstDeviceType): self
    {
        $this->mstDeviceType = $mstDeviceType;

        return $this;
    }

    public function getMediaFilePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getMediaFileName();
    }

    public function setMediaFilePath(?string $mediaFilePath): self
    {
        $this->mediaFilePath = $mediaFilePath;

        return $this;
    }

    public function getNoOfBalcony(): ?int
    {
        return $this->noOfBalcony;
    }

    public function setNoOfBalcony(?int $noOfBalcony): self
    {
        $this->noOfBalcony = $noOfBalcony;

        return $this;
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

    public function getMstProjectArea(): ?MstProjectArea
    {
        return $this->mstProjectArea;
    }

    public function setMstProjectArea(?MstProjectArea $mstProjectArea): self
    {
        $this->mstProjectArea = $mstProjectArea;

        return $this;
    }

    public function getMstProjectAreaCategory(): ?MstProjectAreaCategory
    {
        return $this->mstProjectAreaCategory;
    }

    public function setMstProjectAreaCategory(?MstProjectAreaCategory $mstProjectAreaCategory): self
    {
        $this->mstProjectAreaCategory = $mstProjectAreaCategory;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAreaValue(): ?int
    {
        return $this->areaValue;
    }

    public function setAreaValue(?int $areaValue): self
    {
        $this->areaValue = $areaValue;

        return $this;
    }

    public function getMstPropertyTransactionCategory(): ?MstPropertyTransactionCategory
    {
        return $this->mstPropertyTransactionCategory;
    }

    public function setMstPropertyTransactionCategory(?MstPropertyTransactionCategory $mstPropertyTransactionCategory): self
    {
        $this->mstPropertyTransactionCategory = $mstPropertyTransactionCategory;

        return $this;
    }

    public function getMstFacing(): ?MstFacing
    {
        return $this->mstFacing;
    }

    public function setMstFacing(?MstFacing $mstFacing): self
    {
        $this->mstFacing = $mstFacing;

        return $this;
    }

    public function getRentPerMonth(): ?string
    {
        return $this->rentPerMonth;
    }

    public function setRentPerMonth(?string $rentPerMonth): self
    {
        $this->rentPerMonth = $rentPerMonth;

        return $this;
    }

    public function getDeposit(): ?string
    {
        return $this->deposit;
    }

    public function setDeposit(?string $deposit): self
    {
        $this->deposit = $deposit;

        return $this;
    }

    public function getIsNegotiable(): ?bool
    {
        return $this->isNegotiable;
    }

    public function setIsNegotiable(?bool $isNegotiable): self
    {
        $this->isNegotiable = $isNegotiable;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getMstFurnishing(): ?MstFurnishing
    {
        return $this->mstFurnishing;
    }

    public function setMstFurnishing(?MstFurnishing $mstFurnishing): self
    {
        $this->mstFurnishing = $mstFurnishing;

        return $this;
    }

    public function getMstPropertySaleCategory(): ?MstPropertySaleCategory
    {
        return $this->mstPropertySaleCategory;
    }

    public function setMstPropertySaleCategory(?MstPropertySaleCategory $mstPropertySaleCategory): self
    {
        $this->mstPropertySaleCategory = $mstPropertySaleCategory;

        return $this;
    }

    /**
     * @return Collection|MstPreferredTenant[]
     */
    public function getMstPreferredTenant(): Collection
    {
        return $this->mstPreferredTenant;
    }

    public function addMstPreferredTenant(MstPreferredTenant $mstPreferredTenant): self
    {
        if (!$this->mstPreferredTenant->contains($mstPreferredTenant)) {
            $this->mstPreferredTenant[] = $mstPreferredTenant;
        }

        return $this;
    }

    public function removeMstPreferredTenant(MstPreferredTenant $mstPreferredTenant): self
    {
        $this->mstPreferredTenant->removeElement($mstPreferredTenant);

        return $this;
    }
    public function getRoomConfigurationLikes(): ?int
    {
        return $this->roomConfigurationLikes;
    }

    public function setRoomConfigurationLikes(?int $roomConfigurationLikes): self
    {
        $this->roomConfigurationLikes = $roomConfigurationLikes;

        return $this;
    }

    public function getRoomConfigurationViews(): ?int
    {
        return $this->roomConfigurationViews;
    }

    public function setRoomConfigurationViews(?int $roomConfigurationViews): self
    {
        $this->roomConfigurationViews = $roomConfigurationViews;

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

    public function getSeoContent(): ?SeoContent
    {
        return $this->seoContent;
    }

    public function setSeoContent(?SeoContent $seoContent): self
    {
        $this->seoContent = $seoContent;

        return $this;
    }

    /**
     * @return Collection|TrnProjectAdditionalDetail[]
     */
    public function getTrnProjectAdditionalDetails(): Collection
    {
        return $this->trnProjectAdditionalDetails;
    }

    public function addTrnProjectAdditionalDetail(TrnProjectAdditionalDetail $trnProjectAdditionalDetail): self
    {
        if (!$this->trnProjectAdditionalDetails->contains($trnProjectAdditionalDetail)) {
            $this->trnProjectAdditionalDetails[] = $trnProjectAdditionalDetail;
            $trnProjectAdditionalDetail->addTrnProjectRoomConfiguration($this);
        }

        return $this;
    }

    public function removeTrnProjectAdditionalDetail(TrnProjectAdditionalDetail $trnProjectAdditionalDetail): self
    {
        if ($this->trnProjectAdditionalDetails->removeElement($trnProjectAdditionalDetail)) {
            $trnProjectAdditionalDetail->removeTrnProjectRoomConfiguration($this);
        }

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
            $trnUploadDocument->addTrnProjectRoomConfiguration($this);
        }

        return $this;
    }

    public function removeTrnUploadDocument(TrnUploadDocument $trnUploadDocument): self
    {
        if ($this->trnUploadDocuments->removeElement($trnUploadDocument)) {
            $trnUploadDocument->removeTrnProjectRoomConfiguration($this);
        }

        return $this;
    }
}

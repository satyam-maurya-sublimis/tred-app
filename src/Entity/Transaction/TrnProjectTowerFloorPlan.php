<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstDenomination;
use App\Entity\Master\MstDeviceType;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Master\MstUploadDocumentType;
use App\Entity\Organization\OrgCompany;
use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnProjectTowerFloorPlanRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TrnProjectTowerFloorPlanRepository::class)
 * @ORM\Table("trnprojecttowerfloorplan")
 */
class TrnProjectTowerFloorPlan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class, inversedBy="trnProjectTowerFloorPlans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trnProject;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProjectTowerDetails::class, inversedBy="trnProjectTowerFloorPlans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trnProjectTowerDetails;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $uploadUserIpAddress;

    /**
     * @ORM\ManyToOne(targetEntity=MstUploadDocumentType::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstUploadDocumentType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private $locationLatitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private $locationLongitude;

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
     * @ORM\ManyToOne(targetEntity=OrgCompany::class)
     */
    private $orgCompany;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $appUser;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $uploadedByAppUser;

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
     * @ORM\ManyToOne(targetEntity=MstProjectArea::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstProjectSuperArea;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $superArea;

    /**
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstSuperAreaCurrency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $superAreaPerUnit;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectArea::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstProjectCarpetArea;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $carpetArea;

    /**
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstCarpetAreaCurrency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $carpetAreaPerUnit;

    /**
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     */
    private $mstCurrencyAgreementPrice;

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

    public function __construct()
    {
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

    public function getTrnProjectTowerDetails(): ?TrnProjectTowerDetails
    {
        return $this->trnProjectTowerDetails;
    }

    public function setTrnProjectTowerDetails(?TrnProjectTowerDetails $trnProjectTowerDetails): self
    {
        $this->trnProjectTowerDetails = $trnProjectTowerDetails;

        return $this;
    }

    public function getUploadUserIpAddress(): ?string
    {
        return $this->uploadUserIpAddress;
    }

    public function setUploadUserIpAddress(string $uploadUserIpAddress): self
    {
        $this->uploadUserIpAddress = $uploadUserIpAddress;

        return $this;
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

    public function getOrgCompany(): ?OrgCompany
    {
        return $this->orgCompany;
    }

    public function setOrgCompany(?OrgCompany $orgCompany): self
    {
        $this->orgCompany = $orgCompany;

        return $this;
    }

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(?AppUser $appUser): self
    {
        $this->appUser = $appUser;

        return $this;
    }

    public function getUploadedByAppUser(): ?AppUser
    {
        return $this->uploadedByAppUser;
    }

    public function setUploadedByAppUser(?AppUser $uploadedByAppUser): self
    {
        $this->uploadedByAppUser = $uploadedByAppUser;

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

    public function getMstProjectSuperArea(): ?MstProjectArea
    {
        return $this->mstProjectSuperArea;
    }

    public function setMstProjectSuperArea(?MstProjectArea $mstProjectSuperArea): self
    {
        $this->mstProjectSuperArea = $mstProjectSuperArea;

        return $this;
    }

    public function getSuperArea(): ?int
    {
        return $this->superArea;
    }

    public function setSuperArea(?int $superArea): self
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

    public function getSuperAreaPerUnit(): ?string
    {
        return $this->superAreaPerUnit;
    }

    public function setSuperAreaPerUnit(?string $superAreaPerUnit): self
    {
        $this->superAreaPerUnit = $superAreaPerUnit;

        return $this;
    }

    public function getMstProjectCarpetArea(): ?MstProjectArea
    {
        return $this->mstProjectCarpetArea;
    }

    public function setMstProjectCarpetArea(?MstProjectArea $mstProjectCarpetArea): self
    {
        $this->mstProjectCarpetArea = $mstProjectCarpetArea;

        return $this;
    }

    public function getCarpetArea(): ?int
    {
        return $this->carpetArea;
    }

    public function setCarpetArea(?int $carpetArea): self
    {
        $this->carpetArea = $carpetArea;

        return $this;
    }

    public function getMstCarpetAreaCurrency(): ?MstCurrency
    {
        return $this->mstCarpetAreaCurrency;
    }

    public function setMstCarpetAreaCurrency(?MstCurrency $mstCarpetAreaCurrency): self
    {
        $this->mstCarpetAreaCurrency = $mstCarpetAreaCurrency;

        return $this;
    }

    public function getCarpetAreaPerUnit(): ?string
    {
        return $this->carpetAreaPerUnit;
    }

    public function setCarpetAreaPerUnit(?string $carpetAreaPerUnit): self
    {
        $this->carpetAreaPerUnit = $carpetAreaPerUnit;

        return $this;
    }

    public function getMstCurrencyAgreementPrice(): ?MstCurrency
    {
        return $this->mstCurrencyAgreementPrice;
    }

    public function setMstCurrencyAgreementPrice(?MstCurrency $mstCurrencyAgreementPrice): self
    {
        $this->mstCurrencyAgreementPrice = $mstCurrencyAgreementPrice;

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
}

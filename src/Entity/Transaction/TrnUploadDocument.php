<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Master\MstDeviceType;
use App\Entity\Master\MstUploadDocumentType;
use App\Entity\Organization\OrgCompany;
use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnUploadDocumentRepository;
use App\Service\FileUploaderHelper;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnUploadDocumentRepository::class)
 * @ORM\Table("trnuploaddocument")
 */
class TrnUploadDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $uploadUserIpAddress;

    /**
     * @ORM\ManyToOne(targetEntity=MstUploadDocumentType::class)
     * @ORM\JoinColumn(nullable=false)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaName;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $mediaPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaAltText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaFileName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaFilePath;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity=OrgCompany::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $orgCompany;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $appUser;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $uploadedByAppUser;

    /**
     * @ORM\ManyToOne(targetEntity=MstDeviceType::class)
     */
    private $mstDeviceType;



    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class, inversedBy="trnUploadDocument")
     */
    private $trnProject;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProjectTowerDetails::class, inversedBy="trnUploadDocuments")
     */
    private $trnProjectTowerDetails;

    /**
     * @ORM\ManyToMany(targetEntity=TrnProjectRoomConfiguration::class, inversedBy="trnUploadDocuments")
     */
    private $trnProjectRoomConfigurations;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $createdBy;

    public function __construct()
    {
        $this->trnProjectRoomConfigurations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMediaPath(): ?string
    {
        return $this->mediaPath;
    }

    public function setMediaPath(?string $mediaPath): self
    {
        $this->mediaPath = $mediaPath;

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

    public function getMediaFilePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->mediaFileName;
    }

    public function setMediaFilePath(string $mediaFilePath): self
    {
        $this->mediaFilePath = $mediaFilePath;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

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

    public function getMstDeviceType(): ?MstDeviceType
    {
        return $this->mstDeviceType;
    }

    public function setMstDeviceType(?MstDeviceType $mstDeviceType): self
    {
        $this->mstDeviceType = $mstDeviceType;

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

    public function getTrnProjectTowerDetails(): ?TrnProjectTowerDetails
    {
        return $this->trnProjectTowerDetails;
    }

    public function setTrnProjectTowerDetails(?TrnProjectTowerDetails $trnProjectTowerDetails): self
    {
        $this->trnProjectTowerDetails = $trnProjectTowerDetails;

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
        }

        return $this;
    }

    public function removeTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        $this->trnProjectRoomConfigurations->removeElement($trnProjectRoomConfiguration);

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
}

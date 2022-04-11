<?php

namespace App\Entity\Cms;

use App\Service\FileUploaderHelper;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cms\CmsAdvertisementRepository")
 * @ORM\Table("cmsadvertisement")
 */
class CmsAdvertisement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CmsPage::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $cmsPage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $advertisementName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $advertisementDescription;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $advertisementUrl;

    /**
     * @ORM\Column(type="date")
     */
    private $advertisementValidFromDate;

    /**
     * @ORM\Column(type="date")
     */
    private $advertisementValidToDate;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $advertisementMediaType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $advertisementDesktopImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $advertisementTabletImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $advertisementMobileImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $advertisementImageSetName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $advertisementImageAlt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $advertisementImageTitle;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $advertisementDesktopImagePath;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $advertisementTabletImagePath;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $advertisementMobileImagePath;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $advertisementVideo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $advertisementVideoPath;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;


    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $advertisementPosition;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCmsPage(): ?CmsPage
    {
        return $this->cmsPage;
    }

    public function setCmsPage(?CmsPage $cmsPage): self
    {
        $this->cmsPage = $cmsPage;

        return $this;
    }

    public function getAdvertisementName(): ?string
    {
        return $this->advertisementName;
    }

    public function setAdvertisementName(string $advertisementName): self
    {
        $this->advertisementName = $advertisementName;

        return $this;
    }

    public function getAdvertisementDescription(): ?string
    {
        return $this->advertisementDescription;
    }

    public function setAdvertisementDescription(?string $advertisementDescription): self
    {
        $this->advertisementDescription = $advertisementDescription;

        return $this;
    }

    public function getAdvertisementUrl(): ?string
    {
        return $this->advertisementUrl;
    }

    public function setAdvertisementUrl(?string $advertisementUrl): self
    {
        $this->advertisementUrl = $advertisementUrl;

        return $this;
    }

    public function getAdvertisementValidFromDate(): ?DateTimeInterface
    {
        return $this->advertisementValidFromDate;
    }

    public function setAdvertisementValidFromDate(DateTimeInterface $advertisementValidFromDate): self
    {
        $this->advertisementValidFromDate = $advertisementValidFromDate;

        return $this;
    }

    public function getAdvertisementValidToDate(): ?DateTimeInterface
    {
        return $this->advertisementValidToDate;
    }

    public function setAdvertisementValidToDate(DateTimeInterface $advertisementValidToDate): self
    {
        $this->advertisementValidToDate = $advertisementValidToDate;

        return $this;
    }

    public function getAdvertisementMediaType(): ?string
    {
        return $this->advertisementMediaType;
    }

    public function setAdvertisementMediaType(string $advertisementMediaType): self
    {
        $this->advertisementMediaType = $advertisementMediaType;

        return $this;
    }

    public function getAdvertisementDesktopImage(): ?string
    {
        return $this->advertisementDesktopImage;
    }

    public function setAdvertisementDesktopImage(string $advertisementDesktopImage): self
    {
        $this->advertisementDesktopImage = $advertisementDesktopImage;

        return $this;
    }

    public function getAdvertisementTabletImage(): ?string
    {
        return $this->advertisementTabletImage;
    }

    public function setAdvertisementTabletImage(string $advertisementTabletImage): self
    {
        $this->advertisementTabletImage = $advertisementTabletImage;

        return $this;
    }

    public function getAdvertisementMobileImage(): ?string
    {
        return $this->advertisementMobileImage;
    }

    public function setAdvertisementMobileImage(string $advertisementMobileImage): self
    {
        $this->advertisementMobileImage = $advertisementMobileImage;

        return $this;
    }

    public function getAdvertisementImageSetName(): ?string
    {
        return $this->advertisementImageSetName;
    }

    public function setAdvertisementImageSetName(?string $advertisementImageSetName): self
    {
        $this->advertisementImageSetName = $advertisementImageSetName;

        return $this;
    }

    public function getAdvertisementImageAlt(): ?string
    {
        return $this->advertisementImageAlt;
    }

    public function setAdvertisementImageAlt(?string $advertisementImageAlt): self
    {
        $this->advertisementImageAlt = $advertisementImageAlt;

        return $this;
    }

    public function getAdvertisementImageTitle(): ?string
    {
        return $this->advertisementImageTitle;
    }

    public function setAdvertisementImageTitle(?string $advertisementImageTitle): self
    {
        $this->advertisementImageTitle = $advertisementImageTitle;

        return $this;
    }

    public function getAdvertisementDesktopImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getAdvertisementDesktopImage();
    }

    public function setAdvertisementDesktopImagePath(string $advertisementDesktopImagePath): self
    {
        $this->advertisementDesktopImagePath = $advertisementDesktopImagePath;

        return $this;
    }

    public function getAdvertisementTabletImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getAdvertisementTabletImage();
    }

    public function setAdvertisementTabletImagePath(string $advertisementTabletImagePath): self
    {
        $this->advertisementTabletImagePath = $advertisementTabletImagePath;

        return $this;
    }

    public function getAdvertisementMobileImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getAdvertisementMobileImage();
    }

    public function setAdvertisementMobileImagePath(string $advertisementMobileImagePath): self
    {
        $this->advertisementMobileImagePath = $advertisementMobileImagePath;

        return $this;
    }

    public function getAdvertisementVideo(): ?string
    {
        return $this->advertisementVideo;
    }

    public function setAdvertisementVideo(?string $advertisementVideo): self
    {
        $this->advertisementVideo = $advertisementVideo;
        return $this;
    }

    public function getAdvertisementVideoPath(): ?string
    {
        return $this->advertisementVideoPath;
    }

    public function setAdvertisementVideoPath(?string $advertisementVideoPath): self
    {
        $this->advertisementVideoPath = $advertisementVideoPath;
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

    public function getRowId(): ?string
    {
        return $this->rowId;
    }

    public function setRowId(string $rowId): self
    {
        $this->rowId = $rowId;

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

    public function getAdvertisementPosition(): ?string
    {
        return $this->advertisementPosition;
    }

    public function setAdvertisementPosition(?string $advertisementPosition): self
    {
        $this->advertisementPosition = $advertisementPosition;

        return $this;
    }


}

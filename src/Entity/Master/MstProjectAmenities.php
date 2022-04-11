<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Media\MediaIcon;
use App\Repository\Master\MstProjectAmenitiesRepository;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
 * @ORM\Entity(repositoryClass=MstProjectAmenitiesRepository::class)
 * @ORM\Table("mstprojectamenities")
 * @UniqueEntity(fields={"projectAmenities"}, message="The value is already in the system")
 */
class MstProjectAmenities
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"read"})
     */
    private $projectAmenities;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read"})
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=MstCategory::class)
     * @Groups({"read"})
     */
    private $mstCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstSubCategory::class)
     * @Groups({"read"})
     */
    private $mstSubCategory;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $desktopImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $tabletImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private $mobileImage;

    /**
     * @ORM\OneToOne(targetEntity=MediaIcon::class, mappedBy="mstProjectAmenities", cascade={"persist", "remove"})
     */
    private $mediaIcon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectAmenities(): ?string
    {
        return $this->projectAmenities;
    }

    public function setProjectAmenities(string $projectAmenities): self
    {
        $this->projectAmenities = $projectAmenities;

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

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->projectAmenities;
    }

    public function getMstCategory(): ?MstCategory
    {
        return $this->mstCategory;
    }

    public function setMstCategory(?MstCategory $mstCategory): self
    {
        $this->mstCategory = $mstCategory;

        return $this;
    }

    public function getMstSubCategory(): ?MstSubCategory
    {
        return $this->mstSubCategory;
    }

    public function setMstSubCategory(?MstSubCategory $mstSubCategory): self
    {
        $this->mstSubCategory = $mstSubCategory;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getDesktopImage(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->desktopImage;
    }

    public function setDesktopImage(?string $desktopImage): self
    {
        $this->desktopImage = $desktopImage;

        return $this;
    }

    public function getTabletImage(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->tabletImage;
    }

    public function setTabletImage(?string $tabletImage): self
    {
        $this->tabletImage = $tabletImage;

        return $this;
    }

    public function getMobileImage(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->mobileImage;
    }

    public function setMobileImage(?string $mobileImage): self
    {
        $this->mobileImage = $mobileImage;

        return $this;
    }

    public function getMediaIcon(): ?MediaIcon
    {
        return $this->mediaIcon;
    }

    public function setMediaIcon(?MediaIcon $mediaIcon): self
    {
        // unset the owning side of the relation if necessary
        if ($mediaIcon === null && $this->mediaIcon !== null) {
            $this->mediaIcon->setMstProjectAmenities(null);
        }

        // set the owning side of the relation if necessary
        if ($mediaIcon !== null && $mediaIcon->getMstProjectAmenities() !== $this) {
            $mediaIcon->setMstProjectAmenities($this);
        }

        $this->mediaIcon = $mediaIcon;

        return $this;
    }
}

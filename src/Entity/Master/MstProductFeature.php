<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Master\MstProductFeatureRepository;
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
 * @ORM\Entity(repositoryClass=MstProductFeatureRepository::class)
 * @ORM\Table("mstproductfeature")
 * @UniqueEntity(fields={"productFeature"}, message="The value is already in the system")
 */
class MstProductFeature
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
    private $productFeature;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductFeature(): ?string
    {
        return $this->productFeature;
    }

    public function setProductFeature(string $productFeature): self
    {
        $this->productFeature = $productFeature;

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
        return $this->productFeature;
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
}

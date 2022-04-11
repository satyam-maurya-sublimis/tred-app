<?php

namespace App\Entity\Media;

use App\Entity\Master\MstFurnitureUniqueValuePreposition;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProjectAmenities;
use App\Repository\Media\MediaIconRepository;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaIconRepository::class)
 * @ORM\Table("mediaicon")
 */
class MediaIcon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $iconImage;

    /**
     * @ORM\OneToOne(targetEntity=MstProjectAmenities::class, inversedBy="mediaIcon", cascade={"persist", "remove"})
     */
    private $mstProjectAmenities;

    /**
     * @ORM\OneToOne(targetEntity=MstProductSubType::class, inversedBy="mediaIcon", cascade={"persist", "remove"})
     */
    private $mstProductSubType;

    /**
     * @ORM\OneToOne(targetEntity=MstFurnitureUniqueValuePreposition::class, cascade={"persist", "remove"})
     */
    private $mstFurnitureUniqueValuePreposition;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIconImage(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->iconImage;
    }

    public function setIconImage(string $iconImage): self
    {
        $this->iconImage = $iconImage;
        return $this;
    }

    public function getMstProjectAmenities(): ?MstProjectAmenities
    {
        return $this->mstProjectAmenities;
    }

    public function setMstProjectAmenities(?MstProjectAmenities $mstProjectAmenities): self
    {
        $this->mstProjectAmenities = $mstProjectAmenities;

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

    public function getMstFurnitureUniqueValuePreposition(): ?MstFurnitureUniqueValuePreposition
    {
        return $this->mstFurnitureUniqueValuePreposition;
    }

    public function setMstFurnitureUniqueValuePreposition(?MstFurnitureUniqueValuePreposition $mstFurnitureUniqueValuePreposition): self
    {
        $this->mstFurnitureUniqueValuePreposition = $mstFurnitureUniqueValuePreposition;

        return $this;
    }
}

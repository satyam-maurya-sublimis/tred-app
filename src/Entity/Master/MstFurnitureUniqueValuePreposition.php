<?php

namespace App\Entity\Master;

use App\Entity\Media\MediaIcon;
use App\Repository\Master\MstFurnitureUniqueValuePrepositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MstFurnitureUniqueValuePrepositionRepository::class)
 * @ORM\Table("mstfurnitureuniquevaluepreposition")
 */
class MstFurnitureUniqueValuePreposition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductType::class, inversedBy="mstFurnitureUniqueValuePreposition")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProductType;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $uniqueValuePreposition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\OneToOne(targetEntity=MediaIcon::class, mappedBy="mstFurnitureUniqueValuePreposition", cascade={"persist", "remove"})
     */
    private $mediaIcon;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaAlText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaTitle;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUniqueValuePreposition(): ?string
    {
        return $this->uniqueValuePreposition;
    }

    public function setUniqueValuePreposition(string $uniqueValuePreposition): self
    {
        $this->uniqueValuePreposition = $uniqueValuePreposition;

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

    public function getMediaIcon(): ?MediaIcon
    {
        return $this->mediaIcon;
    }

    public function setMediaIcon(?MediaIcon $mediaIcon): self
    {
        // unset the owning side of the relation if necessary
        if ($mediaIcon === null && $this->mediaIcon !== null) {
            $this->mediaIcon->setMstFurnitureUniqueValuePreposition(null);
        }

        // set the owning side of the relation if necessary
        if ($mediaIcon !== null && $mediaIcon->getMstFurnitureUniqueValuePreposition() !== $this) {
            $mediaIcon->setMstFurnitureUniqueValuePreposition($this);
        }

        $this->mediaIcon = $mediaIcon;

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

    public function getMediaAlText(): ?string
    {
        return $this->mediaAlText;
    }

    public function setMediaAlText(?string $mediaAlText): self
    {
        $this->mediaAlText = $mediaAlText;

        return $this;
    }

    public function getMediaTitle(): ?string
    {
        return $this->mediaAlText;
    }

    public function setMediaTitle(?string $mediaTitle): self
    {
        $this->mediaTitle = $mediaTitle;
        return $this;
    }
}

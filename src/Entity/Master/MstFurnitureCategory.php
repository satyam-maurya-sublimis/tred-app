<?php

namespace App\Entity\Master;

use App\Repository\Master\MstFurnitureCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MstFurnitureCategoryRepository::class)
 * @ORM\Table("mstfurniturecategory")
 * @UniqueEntity(fields={"furnitureCategory"}, message="The value is already in the system")
 */
class MstFurnitureCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $furnitureCategory;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductType::class)
     */
    private $mstProductType;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductSubType::class)
     */
    private $mstProductSubType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $furnitureCategorySlugName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFurnitureCategory(): ?string
    {
        return $this->furnitureCategory;
    }

    public function setFurnitureCategory(string $furnitureCategory): self
    {
        $this->furnitureCategory = $furnitureCategory;

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

    public function getRowId(): ?string
    {
        return $this->rowId;
    }

    public function setRowId(string $rowId): self
    {
        $this->rowId = $rowId;

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

    public function getFurnitureCategorySlugName(): ?string
    {
        return $this->furnitureCategorySlugName;
    }

    public function setFurnitureCategorySlugName(?string $furnitureCategorySlugName): self
    {
        $this->furnitureCategorySlugName = $furnitureCategorySlugName;

        return $this;
    }

    public function __toString(){
        return $this->furnitureCategory;
    }

}

<?php

namespace App\Entity\Product;

use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Repository\Product\PrdColorRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PrdColorRepository::class)
 * @ORM\Table("prdcolor", indexes={
 *          @Index(name="active", columns={"isActive"})
 *     }))
 * @UniqueEntity(fields={"colorName"}, message="The value is already in the system")
 */
class PrdColor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $colorName;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $colorValue;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $colorImage;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $colorImagePath;

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
    private $colorCode;

    /**
     * @ORM\ManyToMany(targetEntity=TrnFurnitureProductCatalog::class, mappedBy="prdColor")
     */
    private $trnFurnitureProductCatalogs;

    public function __construct()
    {
        $this->trnFurnitureProductCatalogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColorName(): ?string
    {
        return $this->colorName;
    }

    public function setColorName(string $colorName): self
    {
        $this->colorName = $colorName;

        return $this;
    }

    public function getColorValue(): ?string
    {
        return $this->colorValue;
    }

    public function setColorValue(?string $colorValue): self
    {
        $this->colorValue = $colorValue;

        return $this;
    }

    public function getColorImage(): ?string
    {
        return $this->colorImage;
    }

    public function setColorImage(string $colorImage): self
    {
        $this->colorImage = $colorImage;

        return $this;
    }

    public function getColorImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getColorImage();
    }

    public function setColorImagePath(?string $colorImagePath): self
    {
        $this->colorImagePath = $colorImagePath;

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

    public function __toString()
    {
        return $this->colorName;
    }

    public function getColorCode()
    {
        return $this->colorCode;
    }

    public function setColorCode($colorCode): void
    {
        $this->colorCode = $colorCode;
    }

    /**
     * @return Collection|TrnFurnitureProductCatalog[]
     */
    public function getTrnFurnitureProductCatalogs(): Collection
    {
        return $this->trnFurnitureProductCatalogs;
    }

    public function addTrnFurnitureProductCatalog(TrnFurnitureProductCatalog $trnFurnitureProductCatalog): self
    {
        if (!$this->trnFurnitureProductCatalogs->contains($trnFurnitureProductCatalog)) {
            $this->trnFurnitureProductCatalogs[] = $trnFurnitureProductCatalog;
            $trnFurnitureProductCatalog->addPrdColor($this);
        }

        return $this;
    }

    public function removeTrnFurnitureProductCatalog(TrnFurnitureProductCatalog $trnFurnitureProductCatalog): self
    {
        if ($this->trnFurnitureProductCatalogs->removeElement($trnFurnitureProductCatalog)) {
            $trnFurnitureProductCatalog->removePrdColor($this);
        }

        return $this;
    }
}

<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Transaction\TrnFurniture;
use App\Repository\Master\MstProductTypeRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 * @ORM\Entity(repositoryClass=MstProductTypeRepository::class)
 * @ORM\Table("mstproducttype")
 * @UniqueEntity(fields={"productType"}, message="The value is already in the system")
 */
class MstProductType
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
    private $productType;

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
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class, inversedBy="mstProductTypes")
     */
    private $mstProductCategory;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $productTypeDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productTypeImage;

    /**
     * @ORM\Column(type="string", length=24, nullable=true)
     */
    private $productTypeMediaType;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $productTypeImagePath;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $productTypeVideo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $productTypeVideoPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productTypeImageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productTypeSlugName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productTypeFormType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $productTypePosition;

    /**
     * @ORM\ManyToMany(targetEntity=MstProductSubType::class, mappedBy="mstProductType")
     */
    private $mstProductSubTypes;

    /**
     * @ORM\OneToMany(targetEntity=TrnFurniture::class, mappedBy="mstProductType")
     */
    private $trnFurniture;

    public function __construct()
    {
        $this->mstProductSubTypes = new ArrayCollection();
        $this->trnFurniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function setProductType(string $productType): self
    {
        $this->productType = $productType;

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
        return $this->productType;
    }

    public function getMstProductCategory(): ?MstProductCategory
    {
        return $this->mstProductCategory;
    }

    public function setMstProductCategory(?MstProductCategory $mstProductCategory): self
    {
        $this->mstProductCategory = $mstProductCategory;

        return $this;
    }

    public function getProductTypeDescription(): ?string
    {
        return $this->productTypeDescription;
    }

    public function setProductTypeDescription(?string $productTypeDescription): self
    {
        $this->productTypeDescription = $productTypeDescription;

        return $this;
    }

    public function getProductTypeImage(): ?string
    {
        return $this->productTypeImage;
    }

    public function setProductTypeImage(?string $productTypeImage): self
    {
        $this->productTypeImage = $productTypeImage;

        return $this;
    }

    public function getProductTypeMediaType(): ?string
    {
        return $this->productTypeMediaType;
    }

    public function setProductTypeMediaType(?string $productTypeMediaType): self
    {
        $this->productTypeMediaType = $productTypeMediaType;

        return $this;
    }

    public function getProductTypeImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getProductTypeImage();
    }

    public function setProductTypeImagePath(?string $productTypeImagePath): self
    {
        $this->productTypeImagePath = $productTypeImagePath;

        return $this;
    }

    public function getProductTypeVideo(): ?string
    {
        return $this->productTypeVideo;
    }

    public function setProductTypeVideo(?string $productTypeVideo): self
    {
        $this->productTypeVideo = $productTypeVideo;

        return $this;
    }

    public function getProductTypeVideoPath(): ?string
    {
        return $this->productTypeVideoPath;
    }

    public function setProductTypeVideoPath(?string $productTypeVideoPath): self
    {
        $this->productTypeVideoPath = $productTypeVideoPath;

        return $this;
    }

    public function getProductTypeImageName(): ?string
    {
        return $this->productTypeImageName;
    }

    public function setProductTypeImageName(?string $productTypeImageName): self
    {
        $this->productTypeImageName = $productTypeImageName;

        return $this;
    }

    public function getProductTypeSlugName(): ?string
    {
        return $this->productTypeSlugName;
    }

    public function setProductTypeSlugName(?string $productTypeSlugName): self
    {
        $this->productTypeSlugName = $productTypeSlugName;

        return $this;
    }

    public function getProductTypeFormType(): ?string
    {
        return $this->productTypeFormType;
    }

    public function setProductTypeFormType(?string $productTypeFormType): self
    {
        $this->productTypeFormType = $productTypeFormType;

        return $this;
    }

    public function getProductTypePosition(): ?int
    {
        return $this->productTypePosition;
    }

    public function setProductTypePosition(?int $productTypePosition): self
    {
        $this->productTypePosition = $productTypePosition;

        return $this;
    }

    /**
     * @return Collection|MstProductSubType[]
     */
    public function getMstProductSubTypes(): Collection
    {
        return $this->mstProductSubTypes;
    }

    public function addMstProductSubType(MstProductSubType $mstProductSubType): self
    {
        if (!$this->mstProductSubTypes->contains($mstProductSubType)) {
            $this->mstProductSubTypes[] = $mstProductSubType;
            $mstProductSubType->addMstProductType($this);
        }

        return $this;
    }

    public function removeMstProductSubType(MstProductSubType $mstProductSubType): self
    {
        if ($this->mstProductSubTypes->removeElement($mstProductSubType)) {
            $mstProductSubType->removeMstProductType($this);
        }

        return $this;
    }

    /**
     * @return Collection|TrnFurniture[]
     */
    public function getTrnFurniture(): Collection
    {
        return $this->trnFurniture;
    }

    public function addTrnFurniture(TrnFurniture $trnFurniture): self
    {
        if (!$this->trnFurniture->contains($trnFurniture)) {
            $this->trnFurniture[] = $trnFurniture;
            $trnFurniture->setMstProductType($this);
        }

        return $this;
    }

    public function removeTrnFurniture(TrnFurniture $trnFurniture): self
    {
        if ($this->trnFurniture->removeElement($trnFurniture)) {
            // set the owning side to null (unless already changed)
            if ($trnFurniture->getMstProductType() === $this) {
                $trnFurniture->setMstProductType(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Media\MediaIcon;
use App\Entity\Transaction\TrnFurniture;
use App\Repository\Master\MstProductSubTypeRepository;
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
 * @ORM\Entity(repositoryClass=MstProductSubTypeRepository::class)
 * @ORM\Table("mstproductsubtype")
 * @UniqueEntity(fields={"productSubType"}, message="The value is already in the system")
 */
class MstProductSubType
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
    private $productSubType;

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
     * @ORM\ManyToMany(targetEntity=MstProductType::class, inversedBy="mstProductSubTypes")
     */
    private $mstProductType;

    /**
     * @ORM\OneToMany(targetEntity=TrnFurniture::class, mappedBy="mstProductSubType")
     */
    private $trnFurniture;

    /**
     * @ORM\OneToOne(targetEntity=MediaIcon::class, mappedBy="mstProductSubType", cascade={"persist", "remove"})
     */
    private $mediaIcon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productSubTypeSlugName;

    public function __construct()
    {
        $this->mstProductType = new ArrayCollection();
        $this->trnFurniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductSubType(): ?string
    {
        return $this->productSubType;
    }

    public function setProductSubType(string $productSubType): self
    {
        $this->productSubType = $productSubType;

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
     * @return Collection|MstProductType[]
     */
    public function getMstProductType(): Collection
    {
        return $this->mstProductType;
    }

    public function addMstProductType(MstProductType $mstProductType): self
    {
        if (!$this->mstProductType->contains($mstProductType)) {
            $this->mstProductType[] = $mstProductType;
        }

        return $this;
    }

    public function removeMstProductType(MstProductType $mstProductType): self
    {
        $this->mstProductType->removeElement($mstProductType);

        return $this;
    }

    public function __toString()
    {
        return $this->productSubType;
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
            $trnFurniture->setMstProductSubType($this);
        }

        return $this;
    }

    public function removeTrnFurniture(TrnFurniture $trnFurniture): self
    {
        if ($this->trnFurniture->removeElement($trnFurniture)) {
            // set the owning side to null (unless already changed)
            if ($trnFurniture->getMstProductSubType() === $this) {
                $trnFurniture->setMstProductSubType(null);
            }
        }

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
            $this->mediaIcon->setMstProductSubType(null);
        }

        // set the owning side of the relation if necessary
        if ($mediaIcon !== null && $mediaIcon->getMstProductSubType() !== $this) {
            $mediaIcon->setMstProductSubType($this);
        }

        $this->mediaIcon = $mediaIcon;

        return $this;
    }

    public function getProductSubTypeSlugName(): ?string
    {
        return $this->productSubTypeSlugName;
    }

    public function setProductSubTypeSlugName(?string $productSubTypeSlugName): self
    {
        $this->productSubTypeSlugName = $productSubTypeSlugName;

        return $this;
    }
}

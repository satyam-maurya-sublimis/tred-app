<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Master\MstMediaSubCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
 * @ORM\Entity(repositoryClass=MstMediaSubCategoryRepository::class)
 * @ORM\Table("mstmediasubcategory")
 */
class MstMediaSubCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstMediaCategory::class, inversedBy="mstMediaSubCategory")
     * @Groups({"read"})
     */
    private $mediaCategory;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $mediaSubCategory;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"read"})
     */
    private $sequenceNo;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read"})
     */
    private $isActive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaCategory(): ?MstMediaCategory
    {
        return $this->mediaCategory;
    }

    public function setMediaCategory(?MstMediaCategory $mediaCategory): self
    {
        $this->mediaCategory = $mediaCategory;

        return $this;
    }

    public function getMediaSubCategory(): ?string
    {
        return $this->mediaSubCategory;
    }

    public function setMediaSubCategory(string $mediaSubCategory): self
    {
        $this->mediaSubCategory = $mediaSubCategory;
        return $this;
    }

    public function getSequenceNo(): ?int
    {
        return $this->sequenceNo;
    }

    public function setSequenceNo(int $sequenceNo): self
    {
        $this->sequenceNo = $sequenceNo;
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
        return $this->mediaSubCategory;
    }
}

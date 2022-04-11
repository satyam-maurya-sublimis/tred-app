<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Master\MstTaxRepository;
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
 * @ORM\Entity(repositoryClass=MstTaxRepository::class)
 * @ORM\Table("msttax")
 * @UniqueEntity(fields={"tax"}, message="The value is already in the system")
 */
class MstTax
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
    private $tax;

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
     * @ORM\ManyToOne(targetEntity=MstTaxCategory::class)
     * @Groups({"read"})
     */
    private $mstTaxCategory;

    /**
     * @ORM\Column(type="string", length=40)
     * @Groups({"read"})
     */
    private $taxAmountType;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"read"})
     */
    private $taxValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTax(): ?string
    {
        return $this->tax;
    }

    public function setTax(string $tax): self
    {
        $this->tax = $tax;

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

    public function getMstTaxCategory(): ?MstTaxCategory
    {
        return $this->mstTaxCategory;
    }

    public function setMstTaxCategory(?MstTaxCategory $mstTaxCategory): self
    {
        $this->mstTaxCategory = $mstTaxCategory;

        return $this;
    }

    public function getTaxAmountType(): ?string
    {
        return $this->taxAmountType;
    }

    public function setTaxAmountType(string $taxAmountType): self
    {
        $this->taxAmountType = $taxAmountType;

        return $this;
    }

    public function getTaxValue(): ?string
    {
        return $this->taxValue;
    }

    public function setTaxValue(string $taxValue): self
    {
        $this->taxValue = $taxValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->tax;
    }
}

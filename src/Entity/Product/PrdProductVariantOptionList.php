<?php

namespace App\Entity\Product;

use App\Repository\Product\PrdProductVariantOptionListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrdProductVariantOptionListRepository::class)
 * @ORM\Table("prdproductvariantoptionlist")
 */
class PrdProductVariantOptionList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PrdProductVariant::class, inversedBy="prdProductVariantOptionLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prdProductVariant;

    /**
     * @ORM\ManyToOne(targetEntity=PrdOption::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $prdOption;

    /**
     * @ORM\ManyToOne(targetEntity=PrdOptionList::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $prdOptionList;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrdProductVariant(): ?PrdProductVariant
    {
        return $this->prdProductVariant;
    }

    public function setPrdProductVariant(?PrdProductVariant $prdProductVariant): self
    {
        $this->prdProductVariant = $prdProductVariant;

        return $this;
    }

    public function getPrdOption(): ?PrdOption
    {
        return $this->prdOption;
    }

    public function setPrdOption(?PrdOption $prdOption): self
    {
        $this->prdOption = $prdOption;

        return $this;
    }

    public function getPrdOptionList(): ?PrdOptionList
    {
        return $this->prdOptionList;
    }

    public function setPrdOptionList(?PrdOptionList $prdOptionList): self
    {
        $this->prdOptionList = $prdOptionList;

        return $this;
    }

    public function __toString()
    {
        $return = '';
        return $return;
    }
}

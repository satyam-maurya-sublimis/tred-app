<?php

namespace App\Entity\Product;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstDelivery;
use App\Repository\Product\PrdProductVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=PrdProductVariantRepository::class)
 * @ORM\Table("prdproductvariant", indexes={
 *          @Index (name="active", columns={"isActive"})
 *     })
 */
class PrdProductVariant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstCurrency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $productVariantPrice;


    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $productVariantHeight;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $productVariantWidth;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $productVariantDepth;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $productVariantWeight;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=PrdProductVariantOptionList::class, mappedBy="prdProductVariant", orphanRemoval=true)
     */
    private $prdProductVariantOptionLists;

    public function __construct()
    {
        $this->prdProductVariantOptionLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getMstCurrency(): ?MstCurrency
    {
        return $this->mstCurrency;
    }

    public function setMstCurrency(?MstCurrency $mstCurrency): self
    {
        $this->mstCurrency = $mstCurrency;

        return $this;
    }

    public function getProductVariantPrice(): ?string
    {
        return $this->productVariantPrice;
    }

    public function setProductVariantPrice(string $productVariantPrice): self
    {
        $this->productVariantPrice = $productVariantPrice;

        return $this;
    }

    public function getProductVariantHeight(): ?string
    {
        return $this->productVariantHeight;
    }

    public function setProductVariantHeight(?string $productVariantHeight): self
    {
        $this->productVariantHeight = $productVariantHeight;

        return $this;
    }

    public function getProductVariantWidth(): ?string
    {
        return $this->productVariantWidth;
    }

    public function setProductVariantWidth(?string $productVariantWidth): self
    {
        $this->productVariantWidth = $productVariantWidth;

        return $this;
    }

    public function getProductVariantDepth(): ?string
    {
        return $this->productVariantDepth;
    }

    public function setProductVariantDepth(?string $productVariantDepth): self
    {
        $this->productVariantDepth = $productVariantDepth;

        return $this;
    }

    public function getProductVariantWeight(): ?int
    {
        return $this->productVariantWeight;
    }

    public function setProductVariantWeight(?int $productVariantWeight): self
    {
        $this->productVariantWeight = $productVariantWeight;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

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
}

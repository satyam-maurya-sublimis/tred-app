<?php

namespace App\Entity\Product;

use App\Entity\Master\MstProductCategory;
use App\Entity\Transaction\TrnFurniture;
use App\Entity\Transaction\TrnTopVendorPartners;
use App\Repository\Product\PrdBrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PrdBrandRepository::class)
 * @ORM\Table("prdbrand")
 * @UniqueEntity(fields={"brandName"}, message="The value is already in the system")
 */
class PrdBrand
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
    private $brandName;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=TrnFurniture::class, mappedBy="prdBrand")
     */
    private $trnFurniture;

    /**
     * @ORM\ManyToMany(targetEntity=TrnTopVendorPartners::class, mappedBy="prdBrands")
     */
    private $trnTopVendorPartners;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class)
     */
    private $mstProductCategory;


    public function __construct()
    {
        $this->trnTopVendorPartners = new ArrayCollection();
        $this->trnFurniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrandName(): ?string
    {
        return $this->brandName;
    }

    public function setBrandName(string $brandName): self
    {
        $this->brandName = $brandName;

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
        return $this->brandName;
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
            $trnFurniture->setPrdBrand($this);
        }

        return $this;
    }

    public function removeTrnFurniture(TrnFurniture $trnFurniture): self
    {
        if ($this->trnFurniture->removeElement($trnFurniture)) {
            // set the owning side to null (unless already changed)
            if ($trnFurniture->getPrdBrand() === $this) {
                $trnFurniture->setPrdBrand(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrnTopVendorPartners[]
     */
    public function getTrnTopVendorPartners(): Collection
    {
        return $this->trnTopVendorPartners;
    }

    public function addTrnTopVendorPartner(TrnTopVendorPartners $trnTopVendorPartner): self
    {
        if (!$this->trnTopVendorPartners->contains($trnTopVendorPartner)) {
            $this->trnTopVendorPartners[] = $trnTopVendorPartner;
            $trnTopVendorPartner->addMstBrand($this);
        }

        return $this;
    }

    public function removeTrnTopVendorPartner(TrnTopVendorPartners $trnTopVendorPartner): self
    {
        if ($this->trnTopVendorPartners->removeElement($trnTopVendorPartner)) {
            $trnTopVendorPartner->removeMstBrand($this);
        }

        return $this;
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


}

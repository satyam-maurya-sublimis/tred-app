<?php

namespace App\Entity\Cms;

use App\Entity\Product\PrdProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cms\CmsFaqRepository")
 * @ORM\Table("cmsfaq")
 */
class CmsFaq
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $faq;

    /**
     * @ORM\ManyToOne(targetEntity=PrdProduct::Class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $prdProduct;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=CmsFaqDetail::class, mappedBy="cmsFaq")
     */
    private $cmsFaqDetail;

    public function __construct()
    {
        $this->cmsFaqDetail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFaq(): ?string
    {
        return $this->faq;
    }

    public function setFaq(string $faq): self
    {
        $this->faq = $faq;

        return $this;
    }

    public function getPrdProduct(): ?PrdProduct
    {
        return $this->prdProduct;
    }

    public function setPrdProduct(?PrdProduct $prdProduct): self
    {
        $this->prdProduct = $prdProduct;
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
        return $this->faq;
    }

    /**
     * @return Collection|CmsFaqDetail[]
     */
    public function getCmsFaqDetail(): Collection
    {
        return $this->cmsFaqDetail;
    }

    public function addCmsFaqDetail(CmsFaqDetail $cmsFaqDetail): self
    {
        if (!$this->cmsFaqDetail->contains($cmsFaqDetail)) {
            $this->cmsFaqDetail[] = $cmsFaqDetail;
            $cmsFaqDetail->setCmsFaq($this);
        }

        return $this;
    }

    public function removeCmsFaqDetail(CmsFaqDetail $cmsFaqDetail): self
    {
        if ($this->cmsFaqDetail->contains($cmsFaqDetail)) {
            $this->cmsFaqDetail->removeElement($cmsFaqDetail);
            // set the owning side to null (unless already changed)
            if ($cmsFaqDetail->getCmsFaq() === $this) {
                $cmsFaqDetail->setCmsFaq(null);
            }
        }

        return $this;
    }

}

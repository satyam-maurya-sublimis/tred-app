<?php

namespace App\Entity\Transaction;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstProjectAreaCategory;
use App\Repository\Transaction\TrnProjectAreaDetailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnProjectAreaDetailRepository::class)
 * @ORM\Table ("trnprojectareadetail")
 */
class TrnProjectAreaDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectArea::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProjectArea;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectAreaCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProjectAreaCategory;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $areaValue;

    /**
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstCurrency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class, inversedBy="trnProjectAreaDetail")
     */
    private $trnProject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMstProjectArea(): ?MstProjectArea
    {
        return $this->mstProjectArea;
    }

    public function setMstProjectArea(?MstProjectArea $mstProjectArea): self
    {
        $this->mstProjectArea = $mstProjectArea;

        return $this;
    }

    public function getMstProjectAreaCategory(): ?MstProjectAreaCategory
    {
        return $this->mstProjectAreaCategory;
    }

    public function setMstProjectAreaCategory(?MstProjectAreaCategory $mstProjectAreaCategory): self
    {
        $this->mstProjectAreaCategory = $mstProjectAreaCategory;

        return $this;
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTrnProject(): ?TrnProject
    {
        return $this->trnProject;
    }

    public function setTrnProject(?TrnProject $trnProject): self
    {
        $this->trnProject = $trnProject;

        return $this;
    }

    public function getAreaValue(): ?int
    {
        return $this->areaValue;
    }

    public function setAreaValue(?int $areaValue): self
    {
        $this->areaValue = $areaValue;

        return $this;
    }
}

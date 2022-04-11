<?php

namespace App\Entity\Transaction;

use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstSubCategory;
use App\Repository\Transaction\TrnProjectAmenitiesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnProjectAmenitiesRepository::class)
 * @ORM\Table("trnprojectamenities")
 */
class TrnProjectAmenities
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstSubCategory::class)
     */
    private $mstSubCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trnAmenitiesDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class, inversedBy="trnProjectAmenities")
     */
    private $trnProject;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectAmenities::class)
     */
    private $mstProjectAmenities;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMstSubCategory(): ?MstSubCategory
    {
        return $this->mstSubCategory;
    }

    public function setMstSubCategory(?MstSubCategory $mstSubCategory): self
    {
        $this->mstSubCategory = $mstSubCategory;

        return $this;
    }

    public function getTrnAmenitiesDescription(): ?string
    {
        return $this->trnAmenitiesDescription;
    }

    public function setTrnAmenitiesDescription(?string $trnAmenitiesDescription): self
    {
        $this->trnAmenitiesDescription = $trnAmenitiesDescription;

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

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

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

    public function getMstProjectAmenities(): ?MstProjectAmenities
    {
        return $this->mstProjectAmenities;
    }

    public function setMstProjectAmenities(?MstProjectAmenities $mstProjectAmenities): self
    {
        $this->mstProjectAmenities = $mstProjectAmenities;

        return $this;
    }
}

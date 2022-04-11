<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Master\MstProjectSpecification;
use App\Repository\Transaction\TrnProjectSpecificationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TrnProjectSpecificationsRepository::class)
 * @ORM\Table("trnprojectspecifications")
 */
class TrnProjectSpecifications
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $trnProject;

    /**
     * @ORM\ManyToOne(targetEntity=MstProjectSpecification::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProjectSpecification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $specificationDescription;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMstProjectSpecification(): ?MstProjectSpecification
    {
        return $this->mstProjectSpecification;
    }

    public function setMstProjectSpecification(?MstProjectSpecification $mstProjectSpecification): self
    {
        $this->mstProjectSpecification = $mstProjectSpecification;

        return $this;
    }

    public function getSpecificationDescription(): ?string
    {
        return $this->specificationDescription;
    }

    public function setSpecificationDescription(?string $specificationDescription): self
    {
        $this->specificationDescription = $specificationDescription;

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

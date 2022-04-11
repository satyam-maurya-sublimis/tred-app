<?php

namespace App\Entity\Transaction;

use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnProjectAdditionalDetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnProjectAdditionalDetailRepository::class)
 * @ORM\Table ("trnprojectadditionaldetail")
 */
class TrnProjectAdditionalDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $additionalDetail;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProject::class, inversedBy="trnProjectAdditionalDetail")
     */
    private $trnProject;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $additionalDetailType;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdOn;

    /**
     * @ORM\ManyToMany(targetEntity=TrnProjectRoomConfiguration::class, inversedBy="trnProjectAdditionalDetails")
     */
    private $trnProjectRoomConfigurations;

    public function __construct()
    {
        $this->trnProjectRoomConfigurations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdditionalDetail(): ?string
    {
        return $this->additionalDetail;
    }

    public function setAdditionalDetail(?string $additionalDetail): self
    {
        $this->additionalDetail = $additionalDetail;

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

    public function getAdditionalDetailType(): ?string
    {
        return $this->additionalDetailType;
    }

    public function setAdditionalDetailType(?string $additionalDetailType): self
    {
        $this->additionalDetailType = $additionalDetailType;

        return $this;
    }

    public function getCreatedBy(): ?AppUser
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?AppUser $createdBy): self
    {
        $this->createdBy = $createdBy;

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

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * @return Collection|TrnProjectRoomConfiguration[]
     */
    public function getTrnProjectRoomConfigurations(): Collection
    {
        return $this->trnProjectRoomConfigurations;
    }

    public function addTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        if (!$this->trnProjectRoomConfigurations->contains($trnProjectRoomConfiguration)) {
            $this->trnProjectRoomConfigurations[] = $trnProjectRoomConfiguration;
        }

        return $this;
    }

    public function removeTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        $this->trnProjectRoomConfigurations->removeElement($trnProjectRoomConfiguration);

        return $this;
    }
}

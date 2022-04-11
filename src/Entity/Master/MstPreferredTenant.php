<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Repository\Master\MstPreferredTenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 * @ORM\Entity(repositoryClass=MstPreferredTenantRepository::class)
 * @ORM\Table("mstpreferredtenant")
 * @UniqueEntity(fields={"preferredTenant"}, message="The value is already in the system")
 */
class MstPreferredTenant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Groups({"read"})
     */
    private $preferredTenant;

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
     * @ORM\ManyToMany(targetEntity=TrnProject::class, inversedBy="mstPreferredTenants")
     */
    private $mstProjects;

    /**
     * @ORM\ManyToMany(targetEntity=TrnProjectRoomConfiguration::class, mappedBy="mstPreferredTenant")
     */
    private $trnProjectRoomConfigurations;

    public function __construct()
    {
        $this->mstProjects = new ArrayCollection();
        $this->trnProjectRoomConfigurations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPreferredTenant(): ?string
    {
        return $this->preferredTenant;
    }

    public function setPreferredTenant(string $preferredTenant): self
    {
        $this->preferredTenant = $preferredTenant;

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

    /**
     * @return mixed
     */
    public function __toString() {
        return $this->preferredTenant;
    }

    /**
     * @return Collection|TrnProject[]
     */
    public function getMstProjects(): Collection
    {
        return $this->mstProjects;
    }

    public function addMstProject(TrnProject $mstProject): self
    {
        if (!$this->mstProjects->contains($mstProject)) {
            $this->mstProjects[] = $mstProject;
        }

        return $this;
    }

    public function removeMstProject(TrnProject $mstProject): self
    {
        $this->mstProjects->removeElement($mstProject);

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
            $trnProjectRoomConfiguration->addMstPreferredTenant($this);
        }

        return $this;
    }

    public function removeTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        if ($this->trnProjectRoomConfigurations->removeElement($trnProjectRoomConfiguration)) {
            $trnProjectRoomConfiguration->removeMstPreferredTenant($this);
        }

        return $this;
    }
}

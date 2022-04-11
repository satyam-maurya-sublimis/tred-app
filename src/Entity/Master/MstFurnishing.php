<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Repository\Master\MstFurnishingRepository;
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
 * @ORM\Entity(repositoryClass=MstFurnishingRepository::class)
 * @ORM\Table("mstfurnishing")
 * @UniqueEntity(fields={"furnishing"}, message="The value is already in the system")
 */
class MstFurnishing
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
    private $furnishing;

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
     * @ORM\OneToMany(targetEntity=TrnProjectRoomConfiguration::class, mappedBy="mstFurnishing")
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

    public function getFurnishing(): ?string
    {
        return $this->furnishing;
    }

    public function setFurnishing(string $furnishing): self
    {
        $this->furnishing = $furnishing;

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
    public function __toString(){
        return $this->furnishing;
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
            $trnProjectRoomConfiguration->setMstFurnishing($this);
        }

        return $this;
    }

    public function removeTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        if ($this->trnProjectRoomConfigurations->removeElement($trnProjectRoomConfiguration)) {
            // set the owning side to null (unless already changed)
            if ($trnProjectRoomConfiguration->getMstFurnishing() === $this) {
                $trnProjectRoomConfiguration->setMstFurnishing(null);
            }
        }

        return $this;
    }
}

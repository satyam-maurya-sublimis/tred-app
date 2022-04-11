<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Sale\TrnProject;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Repository\Master\MstPropertySaleCategoryRepository;
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
 * @ORM\Entity(repositoryClass=MstPropertySaleCategoryRepository::class)
 * @ORM\Table("mstpropertysalecategory")
 * @UniqueEntity(fields={"propertySaleCategory"}, message="The value is already in the system")
 */
class MstPropertySaleCategory
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
    private $propertySaleCategory;

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
     * @ORM\OneToMany(targetEntity=TrnProjectRoomConfiguration::class, mappedBy="mstPropertySaleCategory")
     */
    private $trnProjectRoomConfiguration;

    public function __construct()
    {
        $this->trnProjectRoomConfiguration = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPropertySaleCategory(): ?string
    {
        return $this->propertySaleCategory;
    }

    public function setPropertySaleCategory(string $propertySaleCategory): self
    {
        $this->propertySaleCategory = $propertySaleCategory;

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
        return $this->propertySaleCategory;
    }

    /**
     * @return Collection|TrnProjectRoomConfiguration[]
     */
    public function getTrnProjectRoomConfiguration(): Collection
    {
        return $this->trnProjectRoomConfiguration;
    }

    public function addTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        if (!$this->trnProjectRoomConfiguration->contains($trnProjectRoomConfiguration)) {
            $this->trnProjectRoomConfiguration[] = $trnProjectRoomConfiguration;
            $trnProjectRoomConfiguration->setMstPropertySaleCategory($this);
        }

        return $this;
    }

    public function removeTrnProjectRoomConfiguration(TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        if ($this->trnProjectRoomConfiguration->removeElement($trnProjectRoomConfiguration)) {
            // set the owning side to null (unless already changed)
            if ($trnProjectRoomConfiguration->getMstPropertySaleCategory() === $this) {
                $trnProjectRoomConfiguration->setMstPropertySaleCategory(null);
            }
        }

        return $this;
    }
}

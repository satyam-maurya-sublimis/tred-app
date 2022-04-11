<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Repository\Master\MstFurnitureFinishRepository;
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
 * @ORM\Entity(repositoryClass=MstFurnitureFinishRepository::class)
 * @ORM\Table("mstfurnitureFinish")
 * @UniqueEntity(fields={"furnitureFinish"}, message="The value is already in the system")
 */
class MstFurnitureFinish
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
    private $furnitureFinish;

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
     * @ORM\OneToMany(targetEntity=TrnFurnitureProductCatalog::class, mappedBy="mstFurnitureFinish")
     */
    private $trnFurnitureProductCatalogs;

    public function __construct()
    {
        $this->trnFurnitureProductCatalogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFurnitureFinish(): ?string
    {
        return $this->furnitureFinish;
    }

    public function setFurnitureFinish(string $furnitureFinish): self
    {
        $this->furnitureFinish = $furnitureFinish;

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
    public function __toString()
    {
        return $this->furnitureFinish;
    }

    /**
     * @return Collection|TrnFurnitureProductCatalog[]
     */
    public function getTrnFurnitureProductCatalogs(): Collection
    {
        return $this->trnFurnitureProductCatalogs;
    }

    public function addTrnFurnitureProductCatalog(TrnFurnitureProductCatalog $trnFurnitureProductCatalog): self
    {
        if (!$this->trnFurnitureProductCatalogs->contains($trnFurnitureProductCatalog)) {
            $this->trnFurnitureProductCatalogs[] = $trnFurnitureProductCatalog;
            $trnFurnitureProductCatalog->setMstFurnitureFinish($this);
        }

        return $this;
    }

    public function removeTrnFurnitureProductCatalog(TrnFurnitureProductCatalog $trnFurnitureProductCatalog): self
    {
        if ($this->trnFurnitureProductCatalogs->removeElement($trnFurnitureProductCatalog)) {
            // set the owning side to null (unless already changed)
            if ($trnFurnitureProductCatalog->getMstFurnitureFinish() === $this) {
                $trnFurnitureProductCatalog->setMstFurnitureFinish(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Transaction\TrnProject;
use App\Repository\Master\MstPropertyTransactionCategoryRepository;
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
 * @ORM\Entity(repositoryClass=MstPropertyTransactionCategoryRepository::class)
 * @ORM\Table("mstpropertytransactioncategory")
 * @UniqueEntity(fields={"propertyTransactionCategory"}, message="The value is already in the system")
 */
class MstPropertyTransactionCategory
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
    private $propertyTransactionCategory;

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
     * @ORM\ManyToMany(targetEntity=TrnProject::class, mappedBy="mstPropertyTransactionCategories")
     */
    private $trnProjects;

    public function __construct()
    {
        $this->trnProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPropertyTransactionCategory(): ?string
    {
        return $this->propertyTransactionCategory;
    }

    public function setPropertyTransactionCategory(string $propertyTransactionCategory): self
    {
        $this->propertyTransactionCategory = $propertyTransactionCategory;

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
        return $this->propertyTransactionCategory;
    }

    /**
     * @return Collection|TrnProject[]
     */
    public function getTrnProjects(): Collection
    {
        return $this->trnProjects;
    }

    public function addTrnProject(TrnProject $trnProject): self
    {
        if (!$this->trnProjects->contains($trnProject)) {
            $this->trnProjects[] = $trnProject;
            $trnProject->addMstPropertyTransactionCategory($this);
        }

        return $this;
    }

    public function removeTrnProject(TrnProject $trnProject): self
    {
        if ($this->trnProjects->removeElement($trnProject)) {
            $trnProject->removeMstPropertyTransactionCategory($this);
        }

        return $this;
    }
}

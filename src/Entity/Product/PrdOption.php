<?php

namespace App\Entity\Product;

use App\Entity\Transaction\TrnFurniture;
use App\Repository\Product\PrdOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PrdOptionRepository::class)
 * @ORM\Table("prdoption", indexes={
 *          @Index(name="active", columns={"isActive"})
 *     }))
 * @UniqueEntity(fields={"optionName"}, message="The value is already in the system")
 */
class PrdOption
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
    private $optionName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $optionCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $optionDescription;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=PrdOptionList::class, cascade={"persist"}, mappedBy="prdOption")
     */
    private $prdOptionList;

    /**
     * @ORM\ManyToMany(targetEntity=TrnFurniture::class, mappedBy="prdOption")
     */
    private $trnFurniture;

    public function __construct()
    {
        $this->prdOptionList = new ArrayCollection();
        $this->trnFurniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionName(): ?string
    {
        return $this->optionName;
    }

    public function setOptionName(string $optionName): self
    {
        $this->optionName = $optionName;

        return $this;
    }

    public function getOptionCode(): ?string
    {
        return $this->optionCode;
    }

    public function setOptionCode(string $optionCode): self
    {
        $this->optionCode = $optionCode;
        return $this;
    }

    public function getOptionDescription(): ?string
    {
        return $this->optionDescription;
    }

    public function setOptionDescription(?string $optionDescription): self
    {
        $this->optionDescription = $optionDescription;

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
        return $this->optionName;
    }

    /**
     * @return Collection|PrdOptionList[]
     */
    public function getPrdOptionList(): Collection
    {
        return $this->prdOptionList;
    }

    public function addPrdOptionList(PrdOptionList $prdOptionList): self
    {
        if (!$this->prdOptionList->contains($prdOptionList)) {
            $this->prdOptionList[] = $prdOptionList;
            $prdOptionList->setPrdOption($this);
        }

        return $this;
    }

    public function removePrdOptionList(PrdOptionList $prdOptionList): self
    {
        if ($this->prdOptionList->contains($prdOptionList)) {
            $this->prdOptionList->removeElement($prdOptionList);
            // set the owning side to null (unless already changed)
            if ($prdOptionList->getPrdOption() === $this) {
                $prdOptionList->setPrdOption(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrnFurniture[]
     */
    public function getTrnFurniture(): Collection
    {
        return $this->trnFurniture;
    }

    public function addTrnFurniture(TrnFurniture $trnFurniture): self
    {
        if (!$this->trnFurniture->contains($trnFurniture)) {
            $this->trnFurniture[] = $trnFurniture;
            $trnFurniture->addPrdOption($this);
        }

        return $this;
    }

    public function removeTrnFurniture(TrnFurniture $trnFurniture): self
    {
        if ($this->trnFurniture->removeElement($trnFurniture)) {
            $trnFurniture->removePrdOption($this);
        }

        return $this;
    }
}

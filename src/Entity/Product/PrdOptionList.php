<?php

namespace App\Entity\Product;

use App\Repository\Product\PrdOptionListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=PrdOptionListRepository::class)
 * @ORM\Table("prdoptionlist")
 */
class PrdOptionList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $optionValue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $optionValueDescription;


    /**
     * @ORM\Column(type="smallint")
     */
    private $position;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity=PrdOption::class, inversedBy="prdOptionList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prdOption;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionValue(): ?string
    {
        return $this->optionValue;
    }

    public function setOptionValue(string $optionValue): self
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    public function getOptionValueDescription(): ?string
    {
        return $this->optionValueDescription;
    }

    public function setOptionValueDescription(string $optionValueDescription): self
    {
        $this->optionValueDescription = $optionValueDescription;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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

    public function getPrdOption(): ?PrdOption
    {
        return $this->prdOption;
    }

    public function setPrdOption(?PrdOption $prdOption): self
    {
        $this->prdOption = $prdOption;
        return $this;
    }

    public function __toString()
    {
        return $this->optionValue;
    }
}

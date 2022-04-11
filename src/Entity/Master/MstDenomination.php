<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Master\MstDenominationRepository;
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
 * @ORM\Entity(repositoryClass=MstDenominationRepository::class)
 * @ORM\Table("mstdenomination")
 * @UniqueEntity(fields={"denomination"}, message="The value is already in the system")
 */
class MstDenomination
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
    private $denomination;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $denominationNumericValue;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $abbreviation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDenomination(): ?string
    {
        return $this->denomination;
    }

    public function setDenomination(string $denomination): self
    {
        $this->denomination = $denomination;

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
        return $this->denomination;
    }

    public function getDenominationNumericValue(): ?int
    {
        return $this->denominationNumericValue;
    }

    public function setDenominationNumericValue(?int $denominationNumericValue): self
    {
        $this->denominationNumericValue = $denominationNumericValue;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }
}

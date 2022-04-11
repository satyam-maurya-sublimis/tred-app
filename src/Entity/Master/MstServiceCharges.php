<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Master\MstServiceChargesRepository;
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
 * @ORM\Entity(repositoryClass=MstServiceChargesRepository::class)
 * @ORM\Table("mstservicecharges")
 * @UniqueEntity(fields={"serviceCharges"}, message="The value is already in the system")
 */
class MstServiceCharges
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
    private $serviceCharges;

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
     * @ORM\Column(type="string", length=40)
     * @Groups({"read"})
     */
    private $serviceChargesAmountType;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"read"})
     */
    private $serviceChargesValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceCharges(): ?string
    {
        return $this->serviceCharges;
    }

    public function setServiceCharges(string $serviceCharges): self
    {
        $this->serviceCharges = $serviceCharges;

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

    public function getServiceChargesAmountType(): ?string
    {
        return $this->serviceChargesAmountType;
    }

    public function setServiceChargesAmountType(string $serviceChargesAmountType): self
    {
        $this->serviceChargesAmountType = $serviceChargesAmountType;

        return $this;
    }

    public function getServiceChargesValue(): ?string
    {
        return $this->serviceChargesValue;
    }

    public function setServiceChargesValue(string $serviceChargesValue): self
    {
        $this->serviceChargesValue = $serviceChargesValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->serviceCharges;
    }
}

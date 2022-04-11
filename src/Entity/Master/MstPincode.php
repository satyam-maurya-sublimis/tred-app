<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Transaction\TrnTopVendorPartnersLocality;
use App\Repository\Master\MstPincodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MstPincodeRepository::class)
 */
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
 * @ORM\Entity(repositoryClass=MstPincodeRepository::class)
 * @ORM\Table("mstpincode")
 */
class MstPincode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $circleName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $regionName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $divisionName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $officeName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read"})
     */
    private $pincode;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $officeType;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $delivery;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $stateName;

    /**
     * @ORM\ManyToMany(targetEntity=TrnTopVendorPartnersLocality::class, mappedBy="mstPincode")
     */
    private $trnTopVendorPartnersLocalities;

    public function __construct()
    {
        $this->trnTopVendorPartnersLocalities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCircleName(): ?string
    {
        return $this->circleName;
    }

    public function setCircleName(?string $circleName): self
    {
        $this->circleName = $circleName;

        return $this;
    }

    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    public function setRegionName(?string $regionName): self
    {
        $this->regionName = $regionName;

        return $this;
    }

    public function getDivisionName(): ?string
    {
        return $this->divisionName;
    }

    public function setDivisionName(?string $divisionName): self
    {
        $this->divisionName = $divisionName;

        return $this;
    }

    public function getOfficeName(): ?string
    {
        return $this->officeName;
    }

    public function setOfficeName(?string $officeName): self
    {
        $this->officeName = $officeName;

        return $this;
    }

    public function getPincode(): ?int
    {
        return $this->pincode;
    }

    public function setPincode(?int $pincode): self
    {
        $this->pincode = $pincode;

        return $this;
    }

    public function getOfficeType(): ?string
    {
        return $this->officeType;
    }

    public function setOfficeType(string $officeType): self
    {
        $this->officeType = $officeType;

        return $this;
    }

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(?string $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(?string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getStateName(): ?string
    {
        return $this->stateName;
    }

    public function setStateName(?string $stateName): self
    {
        $this->stateName = $stateName;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->pincode;
    }

    /**
     * @return Collection|TrnTopVendorPartnersLocality[]
     */
    public function getTrnTopVendorPartnersLocalities(): Collection
    {
        return $this->trnTopVendorPartnersLocalities;
    }

    public function addTrnTopVendorPartnersLocality(TrnTopVendorPartnersLocality $trnTopVendorPartnersLocality): self
    {
        if (!$this->trnTopVendorPartnersLocalities->contains($trnTopVendorPartnersLocality)) {
            $this->trnTopVendorPartnersLocalities[] = $trnTopVendorPartnersLocality;
            $trnTopVendorPartnersLocality->addMstPincode($this);
        }

        return $this;
    }

    public function removeTrnTopVendorPartnersLocality(TrnTopVendorPartnersLocality $trnTopVendorPartnersLocality): self
    {
        if ($this->trnTopVendorPartnersLocalities->removeElement($trnTopVendorPartnersLocality)) {
            $trnTopVendorPartnersLocality->removeMstPincode($this);
        }

        return $this;
    }
}

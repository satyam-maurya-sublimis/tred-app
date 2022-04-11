<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Transaction\TrnTopVendorPartners;
use App\Entity\Transaction\TrnTopVendorPartnersLocality;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

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
 * @ORM\Entity(repositoryClass="App\Repository\Master\MstCityRepository")
 * @UniqueEntity(fields={"ccity"}, message="The value is already in the system")
 * @ORM\Table("mstcity", indexes={@ORM\Index(name="City_idx", columns={"city"})})
 */
class MstCity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=MstState::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $mstState;

    /**
     * @ORM\ManyToOne(targetEntity=MstCountry::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $mstCountry;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8)
     * @Groups({"read"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8)
     * @Groups({"read"})
     */
    private $longitude;

    /**
     * @ORM\ManyToMany(targetEntity=TrnTopVendorPartners::class, mappedBy="mstCities")
     */
    private $trnTopVendorPartners;

    /**
     * @ORM\ManyToMany(targetEntity=TrnVendorPartnerDetails::class, mappedBy="mstCitiesOperatingIn")
     */
    private $trnVendorPartnerDetails;

    /**
     * @ORM\OneToMany(targetEntity=TrnTopVendorPartnersLocality::class, mappedBy="mstCity")
     */
    private $trnTopVendorPartnersLocalities;

    public function __construct()
    {
        $this->trnTopVendorPartners = new ArrayCollection();
        $this->trnVendorPartnerDetails = new ArrayCollection();
        $this->trnTopVendorPartnersLocalities = new ArrayCollection();
    }

     public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getMstState(): ?MstState
    {
        return $this->mstState;
    }

    public function setMstState(?MstState $mstState): self
    {
        $this->mstState = $mstState;

        return $this;
    }

    public function getMstCountry(): ?MstCountry
    {
        return $this->mstCountry;
    }

    public function setMstCountry(?MstCountry $mstCountry): self
    {
        $this->mstCountry = $mstCountry;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->city;
    }

    /**
     * @return Collection|TrnTopVendorPartners[]
     */
    public function getTrnTopVendorPartners(): Collection
    {
        return $this->trnTopVendorPartners;
    }

    public function addTrnTopVendorPartner(TrnTopVendorPartners $trnTopVendorPartner): self
    {
        if (!$this->trnTopVendorPartners->contains($trnTopVendorPartner)) {
            $this->trnTopVendorPartners[] = $trnTopVendorPartner;
            $trnTopVendorPartner->addMstCity($this);
        }

        return $this;
    }

    public function removeTrnTopVendorPartner(TrnTopVendorPartners $trnTopVendorPartner): self
    {
        if ($this->trnTopVendorPartners->removeElement($trnTopVendorPartner)) {
            $trnTopVendorPartner->removeMstCity($this);
        }

        return $this;
    }
    /**
     * @return Collection|TrnVendorPartnerDetails[]
     */
    public function getTrnVendorPartnerDetails(): Collection
    {
        return $this->trnVendorPartnerDetails;
    }

    public function addTrnVendorPartnerDetails(TrnVendorPartnerDetails $trnVendorPartnerDetails): self
    {
        if (!$this->trnVendorPartnerDetails->contains($trnVendorPartnerDetails)) {
            $this->trnVendorPartnerDetails[] = $trnVendorPartnerDetails;
            $trnVendorPartnerDetails->addMstCitiesOperatingIn($this);
        }

        return $this;
    }

    public function removeTrnVendorPartnerDetails(TrnVendorPartnerDetails $trnVendorPartnerDetails): self
    {
        if ($this->trnVendorPartnerDetails->removeElement($trnVendorPartnerDetails)) {
            $trnVendorPartnerDetails->removeMstCitiesOperatingIn($this);
        }

        return $this;
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
            $trnTopVendorPartnersLocality->setMstCity($this);
        }

        return $this;
    }

    public function removeTrnTopVendorPartnersLocality(TrnTopVendorPartnersLocality $trnTopVendorPartnersLocality): self
    {
        if ($this->trnTopVendorPartnersLocalities->removeElement($trnTopVendorPartnersLocality)) {
            // set the owning side to null (unless already changed)
            if ($trnTopVendorPartnersLocality->getMstCity() === $this) {
                $trnTopVendorPartnersLocality->setMstCity(null);
            }
        }

        return $this;
    }
}

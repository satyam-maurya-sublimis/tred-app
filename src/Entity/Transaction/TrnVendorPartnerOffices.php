<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Master\MstAreaInCity;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstDaysOfWeek;
use App\Entity\Master\MstOfficeCategory;
use App\Entity\Master\MstState;
use App\Repository\Transaction\TrnVendorPartnerOfficesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TrnVendorPartnerOfficesRepository::class)
 * @ORM\Table("trnvendorpartneroffices")
 */
class TrnVendorPartnerOffices
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TrnVendorPartnerDetails::class, inversedBy="trnVendorPartnerOffices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trnVendorPartnerDetails;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $officeName;

    /**
     * @ORM\ManyToOne(targetEntity=MstOfficeCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstOfficeCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstCountry::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstCountry;

    /**
     * @ORM\ManyToOne(targetEntity=MstState::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstState;

    /**
     * @ORM\ManyToOne(targetEntity=MstCity::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstCity;

    /**
     * @ORM\ManyToOne(targetEntity=MstAreaInCity::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstAreaInCity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $pincode;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $mobileNoCountryCode;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $mobileNumber;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $faxNoCountryCode;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $faxNoCityCode;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $faxNumber;
    /**
     * @ORM\ManyToMany(targetEntity=MstDaysOfWeek::class)
     */
    private $mstDaysOfWeek;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $workingTimeFrom;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $workingTimeTo;

    /**
     * @ORM\OneToMany(targetEntity=TrnVendorPartnerOfficeLandLine::class, cascade={"persist", "remove"}, mappedBy="trnVendorPartnerOffices")
     */
    private $trnVendorPartnerOfficeLandLines;

    public function __construct()
    {
        $this->mstDaysOfWeek = new ArrayCollection();
        $this->trnVendorPartnerOfficeLandLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrnVendorPartnerDetails(): ?TrnVendorPartnerDetails
    {
        return $this->trnVendorPartnerDetails;
    }

    public function setTrnVendorPartnerDetails(?TrnVendorPartnerDetails $trnVendorPartnerDetails): self
    {
        $this->trnVendorPartnerDetails = $trnVendorPartnerDetails;

        return $this;
    }

    public function getOfficeName(): ?string
    {
        return $this->officeName;
    }

    public function setOfficeName(string $officeName): self
    {
        $this->officeName = $officeName;

        return $this;
    }

    public function getMstOfficeCategory(): ?MstOfficeCategory
    {
        return $this->mstOfficeCategory;
    }

    public function setMstOfficeCategory(?MstOfficeCategory $mstOfficeCategory): self
    {
        $this->mstOfficeCategory = $mstOfficeCategory;

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

    public function getMstState(): ?MstState
    {
        return $this->mstState;
    }

    public function setMstState(?MstState $mstState): self
    {
        $this->mstState = $mstState;

        return $this;
    }

    public function getMstCity(): ?MstCity
    {
        return $this->mstCity;
    }

    public function setMstCity(?MstCity $mstCity): self
    {
        $this->mstCity = $mstCity;

        return $this;
    }

    public function getMstAreaInCity(): ?MstAreaInCity
    {
        return $this->mstAreaInCity;
    }

    public function setMstAreaInCity(?MstAreaInCity $mstAreaInCity): self
    {
        $this->mstAreaInCity = $mstAreaInCity;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getPincode(): ?string
    {
        return $this->pincode;
    }

    public function setPincode(string $pincode): self
    {
        $this->pincode = $pincode;

        return $this;
    }

    public function getMobileNoCountryCode(): ?string
    {
        return $this->mobileNoCountryCode;
    }

    public function setMobileNoCountryCode(string $mobileNoCountryCode): self
    {
        $this->mobileNoCountryCode = $mobileNoCountryCode;

        return $this;
    }

    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber(string $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function getFaxNoCountryCode(): ?string
    {
        return $this->faxNoCountryCode;
    }

    public function setFaxNoCountryCode(?string $faxNoCountryCode): self
    {
        $this->faxNoCountryCode = $faxNoCountryCode;

        return $this;
    }

    public function getFaxNoCityCode(): ?string
    {
        return $this->faxNoCityCode;
    }

    public function setFaxNoCityCode(?string $faxNoCityCode): self
    {
        $this->faxNoCityCode = $faxNoCityCode;

        return $this;
    }

    public function getFaxNumber(): ?string
    {
        return $this->faxNumber;
    }

    public function setFaxNumber(?string $faxNumber): self
    {
        $this->faxNumber = $faxNumber;

        return $this;
    }

    /**
     * @return Collection|MstDaysOfWeek[]
     */
    public function getMstDaysOfWeek(): Collection
    {
        return $this->mstDaysOfWeek;
    }

    public function addMstDaysOfWeek(MstDaysOfWeek $mstDaysOfWeek): self
    {
        if (!$this->mstDaysOfWeek->contains($mstDaysOfWeek)) {
            $this->mstDaysOfWeek[] = $mstDaysOfWeek;
        }

        return $this;
    }

    public function removeMstDaysOfWeek(MstDaysOfWeek $mstDaysOfWeek): self
    {
        if ($this->mstDaysOfWeek->contains($mstDaysOfWeek)) {
            $this->mstDaysOfWeek->removeElement($mstDaysOfWeek);
        }

        return $this;
    }

    /*public function getWorkingTimeFrom(): ?DateTimeInterface
    {
        return $this->workingTimeFrom;
    }

    public function setWorkingTimeFrom(DateTimeInterface $workingTimeFrom): self
    {
        $this->workingTimeFrom = $workingTimeFrom;

        return $this;
    }

    public function getWorkingTimeTo(): ?string
    {
        return $this->workingTimeTo;
    }

    public function setWorkingTimeTo(?string $workingTimeTo): self
    {
        $this->workingTimeTo = $workingTimeTo;

        return $this;
    }*/

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getWorkingTimeFrom(): ?\DateTimeInterface
    {
        return $this->workingTimeFrom;
    }

    public function setWorkingTimeFrom(?\DateTimeInterface $workingTimeFrom): self
    {
        $this->workingTimeFrom = $workingTimeFrom;

        return $this;
    }

    public function getWorkingTimeTo(): ?\DateTimeInterface
    {
        return $this->workingTimeTo;
    }

    public function setWorkingTimeTo(?\DateTimeInterface $workingTimeTo): self
    {
        $this->workingTimeTo = $workingTimeTo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString(){
        return $this->officeName;
    }

    /**
     * @return Collection|TrnVendorPartnerOfficeLandLine[]
     */
    public function getTrnVendorPartnerOfficeLandLines(): Collection
    {
        return $this->trnVendorPartnerOfficeLandLines;
    }

    public function addTrnVendorPartnerOfficeLandLine(TrnVendorPartnerOfficeLandLine $trnVendorPartnerOfficeLandLine): self
    {
        if (!$this->trnVendorPartnerOfficeLandLines->contains($trnVendorPartnerOfficeLandLine)) {
            $this->trnVendorPartnerOfficeLandLines[] = $trnVendorPartnerOfficeLandLine;
            $trnVendorPartnerOfficeLandLine->setTrnVendorPartnerOffices($this);
        }

        return $this;
    }

    public function removeTrnVendorPartnerOfficeLandLine(TrnVendorPartnerOfficeLandLine $trnVendorPartnerOfficeLandLine): self
    {
        if ($this->trnVendorPartnerOfficeLandLines->contains($trnVendorPartnerOfficeLandLine)) {
            $this->trnVendorPartnerOfficeLandLines->removeElement($trnVendorPartnerOfficeLandLine);
            // set the owning side to null (unless already changed)
            if ($trnVendorPartnerOfficeLandLine->getTrnVendorPartnerOffices() === $this) {
                $trnVendorPartnerOfficeLandLine->setTrnVendorPartnerOffices(null);
            }
        }

        return $this;
    }
}

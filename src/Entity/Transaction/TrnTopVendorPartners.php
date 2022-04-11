<?php

namespace App\Entity\Transaction;

use App\Entity\Master\MstCountry;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Master\MstSubscriptionCategory;
use App\Entity\Product\PrdBrand;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstRating;
use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnTopVendorPartnersRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnTopVendorPartnersRepository::class)
 * @ORM\Table("trntopvendorpartners")
 */
class TrnTopVendorPartners
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $noOfYearsInBusiness;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $teamSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $annualTurnOver;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numberOfUnitSoldAnnually;

    /**
     * @ORM\ManyToMany(targetEntity=PrdBrand::class, inversedBy="trnTopVendorPartners")
     */
    private $prdBrands;

    /**
     * @ORM\ManyToOne(targetEntity=TrnVendorPartnerDetails::class, inversedBy="trnTopVendorPartners")
     */
    private $trnVendorPartnerDetails;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=MstRating::class)
     */
    private $mstRating;

    /**
     * @ORM\ManyToMany(targetEntity=MstCity::class, inversedBy="trnTopVendorPartners")
     */
    private $mstCities;

    /**
     * @ORM\ManyToOne(targetEntity=MstSubscriptionCategory::class)
     */
    private $mstSubscriptionCategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactPersonImage;

    /**
     * @ORM\ManyToOne(targetEntity=MstCountry::class)
     */
    private $contactPersonCountry;

    /**
     * @ORM\ManyToOne(targetEntity=MstState::class)
     */
    private $contactPersonState;

    /**
     * @ORM\ManyToOne(targetEntity=MstCity::class)
     */
    private $contactPersonCity;

    /**
     * @ORM\ManyToOne(targetEntity=MstPincode::class)
     */
    private $contactPersonPincode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contactPersonName;

    /**
     * @ORM\ManyToOne(targetEntity=MstSalutation::class)
     */
    private $mstSalutation;

    /**
     * @ORM\OneToMany(targetEntity=TrnTopVendorPartnersLocality::class, mappedBy="trnTopVendorPartners", cascade={"persist","remove"})
     */
    private $trnTopVendorPartnersLocalities;

    public function __construct()
    {
        $this->prdBrands = new ArrayCollection();
        $this->mstCities = new ArrayCollection();
        $this->trnTopVendorPartnersLocalities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoOfYearsInBusiness(): ?string
    {
        return $this->noOfYearsInBusiness;
    }

    public function setNoOfYearsInBusiness(?string $noOfYearsInBusiness): self
    {
        $this->noOfYearsInBusiness = $noOfYearsInBusiness;

        return $this;
    }

    public function getTeamSize(): ?string
    {
        return $this->teamSize;
    }

    public function setTeamSize(?string $teamSize): self
    {
        $this->teamSize = $teamSize;

        return $this;
    }

    public function getAnnualTurnOver(): ?string
    {
        return $this->annualTurnOver;
    }

    public function setAnnualTurnOver(?string $annualTurnOver): self
    {
        $this->annualTurnOver = $annualTurnOver;

        return $this;
    }

    public function getNumberOfUnitSoldAnnually(): ?string
    {
        return $this->numberOfUnitSoldAnnually;
    }

    public function setNumberOfUnitSoldAnnually(?string $numberOfUnitSoldAnnually): self
    {
        $this->numberOfUnitSoldAnnually = $numberOfUnitSoldAnnually;

        return $this;
    }

    /**
     * @return Collection|PrdBrand[]
     */
    public function getPrdBrands(): Collection
    {
        return $this->prdBrands;
    }

    public function addPrdBrand(PrdBrand $prdBrand): self
    {
        if (!$this->prdBrands->contains($prdBrand)) {
            $this->prdBrands[] = $prdBrand;
        }

        return $this;
    }

    public function removePrdBrand(PrdBrand $prdBrand): self
    {
        $this->prdBrands->removeElement($prdBrand);

        return $this;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getCreatedBy(): ?AppUser
    {
        return $this->ceatedBy;
    }

    public function setCreatedBy(?AppUser $createdBy): self
    {
        $this->ceatedBy = $createdBy;

        return $this;
    }

    public function getMstRating(): ?MstRating
    {
        return $this->mstRating;
    }

    public function setMstRating(?MstRating $mstRating): self
    {
        $this->mstRating = $mstRating;

        return $this;
    }

    /**
     * @return Collection|MstCity[]
     */
    public function getMstCities(): Collection
    {
        return $this->mstCities;
    }

    public function addMstCity(MstCity $mstCity): self
    {
        if (!$this->mstCities->contains($mstCity)) {
            $this->mstCities[] = $mstCity;
        }

        return $this;
    }

    public function removeMstCity(MstCity $mstCity): self
    {
        $this->mstCities->removeElement($mstCity);

        return $this;
    }

    public function getMstSubscriptionCategory(): ?MstSubscriptionCategory
    {
        return $this->mstSubscriptionCategory;
    }

    public function setMstSubscriptionCategory(?MstSubscriptionCategory $mstSubscriptionCategory): self
    {
        $this->mstSubscriptionCategory = $mstSubscriptionCategory;

        return $this;
    }

    public function getContactPersonImage(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->contactPersonImage;
    }

    public function setContactPersonImage(?string $contactPersonImage): self
    {
        $this->contactPersonImage = $contactPersonImage;

        return $this;
    }
    public function getContactPersonCountry(): ?MstCountry
    {
        return $this->contactPersonCountry;
    }

    public function setContactPersonCountry(?MstCountry $mstCountry): self
    {
        $this->contactPersonCountry = $mstCountry;

        return $this;
    }

    public function getContactPersonState(): ?MstState
    {
        return $this->contactPersonState;
    }

    public function setContactPersonState(?MstState $mstState): self
    {
        $this->contactPersonState = $mstState;

        return $this;
    }

    public function getContactPersonCity(): ?MstCity
    {
        return $this->contactPersonCity;
    }

    public function setContactPersonCity(?MstCity $mstCity): self
    {
        $this->contactPersonCity = $mstCity;

        return $this;
    }

    public function getContactPersonPincode(): ?MstPincode
    {
        return $this->contactPersonPincode;
    }

    public function setContactPersonPincode(?MstPincode $mstPincode): self
    {
        $this->contactPersonPincode = $mstPincode;

        return $this;
    }

    public function getContactPersonName(): ?string
    {
        return $this->contactPersonName;
    }

    public function setContactPersonName(?string $contactPersonName): self
    {
        $this->contactPersonName = $contactPersonName;

        return $this;
    }

    public function getMstSalutation(): ?MstSalutation
    {
        return $this->mstSalutation;
    }

    public function setMstSalutation(?MstSalutation $mstSalutation): self
    {
        $this->mstSalutation = $mstSalutation;

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
            $trnTopVendorPartnersLocality->setTrnTopVendorPartners($this);
        }

        return $this;
    }

    public function removeTrnTopVendorPartnersLocality(TrnTopVendorPartnersLocality $trnTopVendorPartnersLocality): self
    {
        if ($this->trnTopVendorPartnersLocalities->removeElement($trnTopVendorPartnersLocality)) {
            // set the owning side to null (unless already changed)
            if ($trnTopVendorPartnersLocality->getTrnTopVendorPartners() === $this) {
                $trnTopVendorPartnersLocality->setTrnTopVendorPartners(null);
            }
        }

        return $this;
    }

}

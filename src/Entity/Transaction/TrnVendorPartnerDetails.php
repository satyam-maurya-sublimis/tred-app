<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstNatureOfBusiness;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstState;
use App\Entity\Master\MstVendorType;
use App\Entity\Organization\OrgCompany;
use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnVendorPartnerDetailsRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TrnVendorPartnerDetailsRepository::class)
 * @ORM\Table("trnvendorpartnerdetails")
 */
class TrnVendorPartnerDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=OrgCompany::class)
     */
    private $orgCompany;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vendorPartnerName;

    /**
     * @ORM\ManyToMany(targetEntity=MstCity::class, inversedBy="trnVendorPartnerDetails")
     */
    private $mstCitiesOperatingIn;

    /**
     * @ORM\ManyToMany(targetEntity=MstProductType::class)
     */
    private $mstProductType;

    /**
     * @ORM\ManyToMany(targetEntity=MstProductSubType::class)
     */
    private $mstProductSubType;

    /**
     * @ORM\ManyToMany(targetEntity=MstNatureOfBusiness::class)
     */
    private $mstNatureOfBusiness;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $gstNumber;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $legalStatusOfFirm;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $establishmentYear;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $noOfEmployees;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $annualTurnOver;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $projectsCompleted;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $websiteUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $introduction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $companyLogo;

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
    private $appUserCreatedBy;

    /**
     * @ORM\ManyToOne(targetEntity=MstVendorType::class)
     */
    private $mstVendorType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $experience;

    /**
     * @ORM\OneToMany(targetEntity=TrnVendorPartnerOffices::class, mappedBy="trnVendorPartnerDetails", orphanRemoval=true)
     */
    private $trnVendorPartnerOffices;

    /**
     * @ORM\OneToMany(targetEntity=TrnProject::class, mappedBy="trnVendorPartnerDetails")
     */
    private $trnProjects;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTopPartner;

    /**
     * @ORM\OneToMany(targetEntity=TrnTopVendorPartners::class, mappedBy="trnVendorPartnerDetails")
     */
    private $trnTopVendorPartners;

    /**
     * @ORM\ManyToOne(targetEntity=MstCountry::class)
     */
    private $mstCountry;

    /**
     * @ORM\ManyToOne(targetEntity=MstState::class)
     */
    private $mstState;

    /**
     * @ORM\ManyToOne(targetEntity=MstCity::class)
     */
    private $mstCity;

    /**
     * @ORM\ManyToOne(targetEntity=MstPincode::class)
     */
    private $mstPincode;

    public function __construct()
    {
        $this->mstCitiesOperatingIn = new ArrayCollection();
        $this->mstProductType = new ArrayCollection();
        $this->mstProductSubType = new ArrayCollection();
        $this->mstNatureOfBusiness = new ArrayCollection();
        $this->trnVendorPartnerOffices = new ArrayCollection();
        $this->trnProjects = new ArrayCollection();
        $this->trnTopVendorPartners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrgCompany(): ?OrgCompany
    {
        return $this->orgCompany;
    }

    public function setOrgCompany(?OrgCompany $orgCompany): self
    {
        $this->orgCompany = $orgCompany;

        return $this;
    }

    public function getVendorPartnerName(): ?string
    {
        return $this->vendorPartnerName;
    }

    public function setVendorPartnerName(string $vendorPartnerName): self
    {
        $this->vendorPartnerName = $vendorPartnerName;

        return $this;
    }

    /**
     * @return Collection|MstCity[]
     */
    public function getMstCitiesOperatingIn(): Collection
    {
        return $this->mstCitiesOperatingIn;
    }

    public function addMstCitiesOperatingIn(MstCity $mstCitiesOperatingIn): self
    {
        if (!$this->mstCitiesOperatingIn->contains($mstCitiesOperatingIn)) {
            $this->mstCitiesOperatingIn[] = $mstCitiesOperatingIn;
        }

        return $this;
    }

    public function removeMstCitiesOperatingIn(MstCity $mstCitiesOperatingIn): self
    {
        if ($this->mstCitiesOperatingIn->contains($mstCitiesOperatingIn)) {
            $this->mstCitiesOperatingIn->removeElement($mstCitiesOperatingIn);
        }

        return $this;
    }

    /**
     * @return Collection|MstProductType[]
     */
    public function getMstProductType(): Collection
    {
        return $this->mstProductType;
    }

    public function addMstProductType(MstProductType $mstProductType): self
    {
        if (!$this->mstProductType->contains($mstProductType)) {
            $this->mstProductType[] = $mstProductType;
        }

        return $this;
    }

    public function removeMstProductType(MstProductType $mstProductType): self
    {
        if ($this->mstProductType->contains($mstProductType)) {
            $this->mstProductType->removeElement($mstProductType);
        }

        return $this;
    }

    /**
     * @return Collection|MstProductSubType[]
     */
    public function getMstProductSubType(): Collection
    {
        return $this->mstProductSubType;
    }

    public function addMstProductSubType(MstProductSubType $mstProductSubType): self
    {
        if (!$this->mstProductSubType->contains($mstProductSubType)) {
            $this->mstProductSubType[] = $mstProductSubType;
        }

        return $this;
    }

    public function removeMstProductSubType(MstProductSubType $mstProductSubType): self
    {
        if ($this->mstProductSubType->contains($mstProductSubType)) {
            $this->mstProductSubType->removeElement($mstProductSubType);
        }

        return $this;
    }

    /**
     * @return Collection|MstNatureOfBusiness[]
     */
    public function getMstNatureOfBusiness(): Collection
    {
        return $this->mstNatureOfBusiness;
    }

    public function addMstNatureOfBusiness(MstNatureOfBusiness $mstNatureOfBusiness): self
    {
        if (!$this->mstNatureOfBusiness->contains($mstNatureOfBusiness)) {
            $this->mstNatureOfBusiness[] = $mstNatureOfBusiness;
        }

        return $this;
    }

    public function removeMstNatureOfBusiness(MstNatureOfBusiness $mstNatureOfBusiness): self
    {
        if ($this->mstNatureOfBusiness->contains($mstNatureOfBusiness)) {
            $this->mstNatureOfBusiness->removeElement($mstNatureOfBusiness);
        }

        return $this;
    }

    public function getGstNumber(): ?string
    {
        return $this->gstNumber;
    }

    public function setGstNumber(?string $gstNumber): self
    {
        $this->gstNumber = $gstNumber;

        return $this;
    }

    public function getLegalStatusOfFirm(): ?string
    {
        return $this->legalStatusOfFirm;
    }

    public function setLegalStatusOfFirm(?string $legalStatusOfFirm): self
    {
        $this->legalStatusOfFirm = $legalStatusOfFirm;

        return $this;
    }

    public function getEstablishmentYear(): ?\DateTimeInterface
    {
        return $this->establishmentYear;
    }

    public function setEstablishmentYear(?\DateTimeInterface $establishmentYear): self
    {
        $this->establishmentYear = $establishmentYear;

        return $this;
    }

    public function getNoOfEmployees(): ?string
    {
        return $this->noOfEmployees;
    }

    public function setNoOfEmployees(string $noOfEmployees): self
    {
        $this->noOfEmployees = $noOfEmployees;

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

    public function getProjectsCompleted(): ?string
    {
        return $this->projectsCompleted;
    }

    public function setProjectsCompleted(?string $projectsCompleted): self
    {
        $this->projectsCompleted = $projectsCompleted;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getCompanyLogo(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->companyLogo;
    }

    public function setCompanyLogo(?string $companyLogo): self
    {
        $this->companyLogo = $companyLogo;

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

    public function getAppUserCreatedBy(): ?AppUser
    {
        return $this->appUserCreatedBy;
    }

    public function setAppUserCreatedBy(?AppUser $appUserCreatedBy): self
    {
        $this->appUserCreatedBy = $appUserCreatedBy;

        return $this;
    }

    public function getMstVendorType(): ?MstVendorType
    {
        return $this->mstVendorType;
    }

    public function setMstVendorType(?MstVendorType $mstVendorType): self
    {
        $this->mstVendorType = $mstVendorType;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * @return Collection|TrnVendorPartnerOffices[]
     */
    public function getTrnVendorPartnerOffices(): Collection
    {
        return $this->trnVendorPartnerOffices;
    }

    public function addTrnVendorPartnerOffice(TrnVendorPartnerOffices $trnVendorPartnerOffice): self
    {
        if (!$this->trnVendorPartnerOffices->contains($trnVendorPartnerOffice)) {
            $this->trnVendorPartnerOffices[] = $trnVendorPartnerOffice;
            $trnVendorPartnerOffice->setTrnVendorPartnerDetails($this);
        }

        return $this;
    }

    public function removeTrnVendorPartnerOffice(TrnVendorPartnerOffices $trnVendorPartnerOffice): self
    {
        if ($this->trnVendorPartnerOffices->contains($trnVendorPartnerOffice)) {
            $this->trnVendorPartnerOffices->removeElement($trnVendorPartnerOffice);
            // set the owning side to null (unless already changed)
            if ($trnVendorPartnerOffice->getTrnVendorPartnerDetails() === $this) {
                $trnVendorPartnerOffice->setTrnVendorPartnerDetails(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString(){
        return $this->vendorPartnerName;
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
            $trnProject->setTrnVendorPartnerDetails($this);
        }

        return $this;
    }

    public function removeTrnProject(TrnProject $trnProject): self
    {
        if ($this->trnProjects->contains($trnProject)) {
            $this->trnProjects->removeElement($trnProject);
            // set the owning side to null (unless already changed)
            if ($trnProject->getTrnVendorPartnerDetails() === $this) {
                $trnProject->setTrnVendorPartnerDetails(null);
            }
        }

        return $this;
    }

    public function getIsTopPartner(): ?bool
    {
        return $this->isTopPartner;
    }

    public function setIsTopPartner(?bool $isTopPartner): self
    {
        $this->isTopPartner = $isTopPartner;

        return $this;
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
            $trnTopVendorPartner->setTrnVendorPartnerDetails($this);
        }

        return $this;
    }

    public function removeTrnTopVendorPartner(TrnTopVendorPartners $trnTopVendorPartner): self
    {
        if ($this->trnTopVendorPartners->removeElement($trnTopVendorPartner)) {
            // set the owning side to null (unless already changed)
            if ($trnTopVendorPartner->getTrnVendorPartnerDetails() === $this) {
                $trnTopVendorPartner->setTrnVendorPartnerDetails(null);
            }
        }

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

    public function getMstPincode(): ?MstPincode
    {
        return $this->mstPincode;
    }

    public function setMstPincode(?MstPincode $mstPincode): self
    {
        $this->mstPincode = $mstPincode;

        return $this;
    }
}

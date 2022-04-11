<?php

namespace App\Entity\Form;

use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstFurnitureCategory;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Organization\OrgCompany;
use App\Entity\Transaction\TrnFurniture;
use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Repository\Form\FormFurnitureEnquiryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormFurnitureEnquiryRepository::class)
 * @ORM\Table("formfurnitureenquiry")
 */
class FormFurnitureEnquiry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstSalutation::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstSalutation;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $furnitureEnquiryFirstName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $furnitureEnquiryFullName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $furnitureEnquiryMiddleName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $furnitureEnquiryLastName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Email()
     */
    private $furnitureEnquiryEmailAddress;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $furnitureEnquiryMobileNumber;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $furnitureEnquiryPhoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $furnitureEnquiryAddressOne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $furnitureEnquiryAddressTwo;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $furnitureEnquiryPincode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $furnitureEnquiryCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $furnitureEnquiryLocation;

    /**
     * @ORM\ManyToOne(targetEntity=MstCity::class)
     */
    private $mstCity;

    /**
     * @ORM\ManyToOne(targetEntity=MstState::class)
     */
    private $mstState;

    /**
     * @ORM\ManyToOne(targetEntity=MstCountry::class)
     */
    private $mstCountry;

    /**
     * @ORM\Column(type="datetime")
     */
    private $furnitureEnquiryCreateTime;

    /**
     * @ORM\ManyToOne(targetEntity=MstLeadStatus::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstLeadStatus;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $furnitureEnquiryDescription;

    /**
     * @ORM\ManyToOne(targetEntity=OrgCompany::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $orgCompany;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMeetingSchduleWithTredExpert;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $furnitureEnquiryBudget;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $furnitureEnquiryTitle;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class)
     */
    private $mstProductCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductType::class)
     */
    private $mstProductType;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductSubType::class)
     */
    private $mstProductSubType;

    /**
     * @ORM\ManyToOne(targetEntity=MstFurnitureCategory::class)
     */
    private $mstFurnitureCategory;

    /**
     * @ORM\ManyToOne(targetEntity=TrnFurniture::class)
     */
    private $trnFurniture;

    /**
     * @ORM\ManyToOne(targetEntity=TrnFurnitureProductCatalog::class)
     */
    private $trnFurnitureProductCatalog;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getFurnitureEnquiryFirstName(): ?string
    {
        return $this->furnitureEnquiryFirstName;
    }

    public function setFurnitureEnquiryFirstName(?string $furnitureEnquiryFirstName): self
    {
        $this->furnitureEnquiryFirstName = $furnitureEnquiryFirstName;

        return $this;
    }

    public function getFurnitureEnquiryMiddleName(): ?string
    {
        return $this->furnitureEnquiryMiddleName;
    }

    public function setFurnitureEnquiryMiddleName(?string $furnitureEnquiryMiddleName): self
    {
        $this->furnitureEnquiryMiddleName = $furnitureEnquiryMiddleName;

        return $this;
    }

    public function getFurnitureEnquiryLastName(): ?string
    {
        return $this->furnitureEnquiryLastName;
    }

    public function setFurnitureEnquiryLastName(?string $furnitureEnquiryLastName): self
    {
        $this->furnitureEnquiryLastName = $furnitureEnquiryLastName;

        return $this;
    }

    public function getFurnitureEnquiryEmailAddress(): ?string
    {
        return $this->furnitureEnquiryEmailAddress;
    }

    public function setFurnitureEnquiryEmailAddress(?string $furnitureEnquiryEmailAddress): self
    {
        $this->furnitureEnquiryEmailAddress = $furnitureEnquiryEmailAddress;

        return $this;
    }

    public function getFurnitureEnquiryMobileNumber(): ?string
    {
        return $this->furnitureEnquiryMobileNumber;
    }

    public function setFurnitureEnquiryMobileNumber(?string $furnitureEnquiryMobileNumber): self
    {
        $this->furnitureEnquiryMobileNumber = $furnitureEnquiryMobileNumber;

        return $this;
    }

    public function getFurnitureEnquiryPhoneNumber(): ?string
    {
        return $this->furnitureEnquiryPhoneNumber;
    }

    public function setFurnitureEnquiryPhoneNumber(?string $furnitureEnquiryPhoneNumber): self
    {
        $this->furnitureEnquiryPhoneNumber = $furnitureEnquiryPhoneNumber;

        return $this;
    }

    public function getFurnitureEnquiryAddressOne(): ?string
    {
        return $this->furnitureEnquiryAddressOne;
    }

    public function setFurnitureEnquiryAddressOne(?string $furnitureEnquiryAddressOne): self
    {
        $this->furnitureEnquiryAddressOne = $furnitureEnquiryAddressOne;

        return $this;
    }

    public function getFurnitureEnquiryAddressTwo(): ?string
    {
        return $this->furnitureEnquiryAddressTwo;
    }

    public function setFurnitureEnquiryAddressTwo(?string $furnitureEnquiryAddressTwo): self
    {
        $this->furnitureEnquiryAddressTwo = $furnitureEnquiryAddressTwo;

        return $this;
    }

    public function getFurnitureEnquiryPincode(): ?string
    {
        return $this->furnitureEnquiryPincode;
    }

    public function setFurnitureEnquiryPincode(?string $furnitureEnquiryPincode): self
    {
        $this->furnitureEnquiryPincode = $furnitureEnquiryPincode;

        return $this;
    }

    public function getFurnitureEnquiryCity(): ?string
    {
        return $this->furnitureEnquiryCity;
    }

    public function setFurnitureEnquiryCity(?string $furnitureEnquiryCity): self
    {
        $this->furnitureEnquiryCity = $furnitureEnquiryCity;

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

    public function getFurnitureEnquiryCreateTime(): ?\DateTimeInterface
    {
        return $this->furnitureEnquiryCreateTime;
    }

    public function setFurnitureEnquiryCreateTime(\DateTimeInterface $furnitureEnquiryCreateTime): self
    {
        $this->furnitureEnquiryCreateTime = $furnitureEnquiryCreateTime;

        return $this;
    }

    public function getMstLeadStatus(): ?MstLeadStatus
    {
        return $this->mstLeadStatus;
    }

    public function setMstLeadStatus(?MstLeadStatus $mstLeadStatus): self
    {
        $this->mstLeadStatus = $mstLeadStatus;

        return $this;
    }

    public function getFurnitureEnquiryDescription(): ?string
    {
        return $this->furnitureEnquiryDescription;
    }

    public function setFurnitureEnquiryDescription(?string $furnitureEnquiryDescription): self
    {
        $this->furnitureEnquiryDescription = $furnitureEnquiryDescription;

        return $this;
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

    public function getIsMeetingSchduleWithTredExpert(): ?bool
    {
        return $this->isMeetingSchduleWithTredExpert;
    }

    public function setIsMeetingSchduleWithTredExpert(?bool $isMeetingSchduleWithTredExpert): self
    {
        $this->isMeetingSchduleWithTredExpert = $isMeetingSchduleWithTredExpert;

        return $this;
    }

    public function getFurnitureEnquiryBudget(): ?string
    {
        return $this->furnitureEnquiryBudget;
    }

    public function setFurnitureEnquiryBudget(?string $furnitureEnquiryBudget): self
    {
        $this->furnitureEnquiryBudget = $furnitureEnquiryBudget;

        return $this;
    }

    public function getFurnitureEnquiryTitle(): ?string
    {
        return $this->furnitureEnquiryTitle;
    }

    public function setFurnitureEnquiryTitle(?string $furnitureEnquiryTitle): self
    {
        $this->furnitureEnquiryTitle = $furnitureEnquiryTitle;
        return $this;
    }
    public function getFurnitureEnquiryLocation(): ?string
    {
        return $this->furnitureEnquiryLocation;
    }

    public function setFurnitureEnquiryLocation(?string $furnitureEnquiryLocation): self
    {
        $this->furnitureEnquiryLocation = $furnitureEnquiryLocation;

        return $this;
    }

    public function getMstProductCategory(): ?MstProductCategory
    {
        return $this->mstProductCategory;
    }

    public function setMstProductCategory(?MstProductCategory $mstProductCategory): self
    {
        $this->mstProductCategory = $mstProductCategory;

        return $this;
    }

    public function getMstProductType(): ?MstProductType
    {
        return $this->mstProductType;
    }

    public function setMstProductType(?MstProductType $mstProductType): self
    {
        $this->mstProductType = $mstProductType;

        return $this;
    }

    public function getMstProductSubType(): ?MstProductSubType
    {
        return $this->mstProductSubType;
    }

    public function setMstProductSubType(?MstProductSubType $mstProductSubType): self
    {
        $this->mstProductSubType = $mstProductSubType;

        return $this;
    }

    public function getMstFurnitureCategory(): ?MstFurnitureCategory
    {
        return $this->mstFurnitureCategory;
    }

    public function setMstFurnitureCategory(?MstFurnitureCategory $mstFurnitureCategory): self
    {
        $this->mstFurnitureCategory = $mstFurnitureCategory;

        return $this;
    }

    public function getFurnitureEnquiryFullName(): ?string
    {
        return $this->furnitureEnquiryFullName;
    }

    public function setFurnitureEnquiryFullName(?string $furnitureEnquiryFullName): self
    {
        $this->furnitureEnquiryFullName = $furnitureEnquiryFullName;

        return $this;
    }

    public function getTrnFurniture(): ?TrnFurniture
    {
        return $this->trnFurniture;
    }

    public function setTrnFurniture(?TrnFurniture $trnFurniture): self
    {
        $this->trnFurniture = $trnFurniture;

        return $this;
    }

    public function getTrnFurnitureProductCatalog(): ?TrnFurnitureProductCatalog
    {
        return $this->trnFurnitureProductCatalog;
    }

    public function setTrnFurnitureProductCatalog(?TrnFurnitureProductCatalog $trnFurnitureProductCatalog): self
    {
        $this->trnFurnitureProductCatalog = $trnFurnitureProductCatalog;

        return $this;
    }
}

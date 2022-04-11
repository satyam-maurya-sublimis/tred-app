<?php

namespace App\Entity\Form;

use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Organization\OrgCompany;
use App\Repository\Form\FormEnquiryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormEnquiryRepository::class)
 * @ORM\Table("formenquiry")
 */
class FormEnquiry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
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
    private $enquiryFirstName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $enquiryMiddleName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $enquiryLastName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Email()
     */
    private $enquiryEmailAddress;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $enquiryMobileNumber;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $enquiryPhoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enquiryAddressOne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enquiryAddressTwo;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $enquiryPincode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $enquiryCity;

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
    private $enquiryCreateTime;

    /**
     * @ORM\ManyToOne(targetEntity=MstLeadStatus::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstLeadStatus;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $enquiryDescription;

    /**
     * @ORM\ManyToOne(targetEntity=OrgCompany::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $orgCompany;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProductCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductType::class)
     */
    private $mstProductType;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $enquiryHomeLoanAmount;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $enquiryBudget;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $enquiryNeed;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $enquiryMovingFrom;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $enquiryMovingTo;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $enquiryShiftingType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enquiryScopeOfWork;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $enquiryCarpetArea;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $enquiryMoreDetail;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $enquiryComments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $enquiryForm;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isMeetingSchduleWithTredExpert;

    /**
     * @ORM\ManyToOne(targetEntity=MstRoomConfiguration::class)
     */
    private $mstRoomConfiguration;

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

    public function getEnquiryFirstName(): ?string
    {
        return $this->enquiryFirstName;
    }

    public function setEnquiryFirstName(?string $enquiryFirstName): self
    {
        $this->enquiryFirstName = $enquiryFirstName;

        return $this;
    }

    public function getEnquiryMiddleName(): ?string
    {
        return $this->enquiryMiddleName;
    }

    public function setEnquiryMiddleName(?string $enquiryMiddleName): self
    {
        $this->enquiryMiddleName = $enquiryMiddleName;

        return $this;
    }

    public function getEnquiryLastName(): ?string
    {
        return $this->enquiryLastName;
    }

    public function setEnquiryLastName(?string $enquiryLastName): self
    {
        $this->enquiryLastName = $enquiryLastName;

        return $this;
    }

    public function getEnquiryEmailAddress(): ?string
    {
        return $this->enquiryEmailAddress;
    }

    public function setEnquiryEmailAddress(?string $enquiryEmailAddress): self
    {
        $this->enquiryEmailAddress = $enquiryEmailAddress;

        return $this;
    }

    public function getEnquiryMobileNumber(): ?string
    {
        return $this->enquiryMobileNumber;
    }

    public function setEnquiryMobileNumber(?string $enquiryMobileNumber): self
    {
        $this->enquiryMobileNumber = $enquiryMobileNumber;

        return $this;
    }

    public function getEnquiryPhoneNumber(): ?string
    {
        return $this->enquiryPhoneNumber;
    }

    public function setEnquiryPhoneNumber(?string $enquiryPhoneNumber): self
    {
        $this->enquiryPhoneNumber = $enquiryPhoneNumber;

        return $this;
    }

    public function getEnquiryAddressOne(): ?string
    {
        return $this->enquiryAddressOne;
    }

    public function setEnquiryAddressOne(?string $enquiryAddressOne): self
    {
        $this->enquiryAddressOne = $enquiryAddressOne;

        return $this;
    }

    public function getEnquiryAddressTwo(): ?string
    {
        return $this->enquiryAddressTwo;
    }

    public function setEnquiryAddressTwo(?string $enquiryAddressTwo): self
    {
        $this->enquiryAddressTwo = $enquiryAddressTwo;

        return $this;
    }

    public function getEnquiryPincode(): ?string
    {
        return $this->enquiryPincode;
    }

    public function setEnquiryPincode(?string $enquiryPincode): self
    {
        $this->enquiryPincode = $enquiryPincode;

        return $this;
    }

    public function getEnquiryCity(): ?string
    {
        return $this->enquiryCity;
    }

    public function setEnquiryCity(?string $enquiryCity): self
    {
        $this->enquiryCity = $enquiryCity;

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

    public function getEnquiryCreateTime(): ?\DateTimeInterface
    {
        return $this->enquiryCreateTime;
    }

    public function setEnquiryCreateTime(\DateTimeInterface $enquiryCreateTime): self
    {
        $this->enquiryCreateTime = $enquiryCreateTime;

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
    public function getEnquiryDescription(): ?string
    {
        return $this->enquiryDescription;
    }

    public function setEnquiryDescription(?string $enquiryDescription): self
    {
        $this->enquiryDescription = $enquiryDescription;

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

    public function getEnquiryHomeLoanAmount(): ?string
    {
        return $this->enquiryHomeLoanAmount;
    }

    public function setEnquiryHomeLoanAmount(?string $enquiryHomeLoanAmount): self
    {
        $this->enquiryHomeLoanAmount = $enquiryHomeLoanAmount;

        return $this;
    }

    public function getEnquiryBudget(): ?string
    {
        return $this->enquiryBudget;
    }

    public function setEnquiryBudget(?string $enquiryBudget): self
    {
        $this->enquiryBudget = $enquiryBudget;

        return $this;
    }

    public function getEnquiryNeed(): ?string
    {
        return $this->enquiryNeed;
    }

    public function setEnquiryNeed(?string $enquiryNeed): self
    {
        $this->enquiryNeed = $enquiryNeed;

        return $this;
    }

    public function getEnquiryMovingFrom(): ?string
    {
        return $this->enquiryMovingFrom;
    }

    public function setEnquiryMovingFrom(?string $enquiryMovingFrom): self
    {
        $this->enquiryMovingFrom = $enquiryMovingFrom;

        return $this;
    }

    public function getEnquiryMovingTo(): ?string
    {
        return $this->enquiryMovingTo;
    }

    public function setEnquiryMovingTo(?string $enquiryMovingTo): self
    {
        $this->enquiryMovingTo = $enquiryMovingTo;

        return $this;
    }

    public function getEnquiryShiftingType(): ?string
    {
        return $this->enquiryShiftingType;
    }

    public function setEnquiryShiftingType(?string $enquiryShiftingType): self
    {
        $this->enquiryShiftingType = $enquiryShiftingType;

        return $this;
    }

    public function getEnquiryScopeOfWork(): ?string
    {
        return $this->enquiryScopeOfWork;
    }

    public function setEnquiryScopeOfWork(?string $enquiryScopeOfWork): self
    {
        $this->enquiryScopeOfWork = $enquiryScopeOfWork;

        return $this;
    }

    public function getEnquiryCarpetArea(): ?int
    {
        return $this->enquiryCarpetArea;
    }

    public function setEnquiryCarpetArea(?int $enquiryCarpetArea): self
    {
        $this->enquiryCarpetArea = $enquiryCarpetArea;

        return $this;
    }

    public function getEnquiryMoreDetail(): ?string
    {
        return $this->enquiryMoreDetail;
    }

    public function setEnquiryMoreDetail(?string $enquiryMoreDetail): self
    {
        $this->enquiryMoreDetail = $enquiryMoreDetail;

        return $this;
    }

    public function getEnquiryComments(): ?string
    {
        return $this->enquiryComments;
    }

    public function setEnquiryComments(?string $enquiryComments): self
    {
        $this->enquiryComments = $enquiryComments;

        return $this;
    }

    public function getEnquiryForm(): ?string
    {
        return $this->enquiryForm;
    }

    public function setEnquiryForm(?string $enquiryForm): self
    {
        $this->enquiryForm = $enquiryForm;

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

    public function getMstRoomConfiguration(): ?MstRoomConfiguration
    {
        return $this->mstRoomConfiguration;
    }

    public function setMstRoomConfiguration(?MstRoomConfiguration $mstRoomConfiguration): self
    {
        $this->mstRoomConfiguration = $mstRoomConfiguration;

        return $this;
    }

}

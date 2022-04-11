<?php

namespace App\Entity\Form;

use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Organization\OrgCompany;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Repository\Form\FormResidentialEnquiryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FormResidentialEnquiryRepository::class)
 * @ORM\Table("formresidentialenquiry")
 */
class FormResidentialEnquiry
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
    private $residentialEnquiryFirstName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $residentialEnquiryMiddleName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $residentialEnquiryLastName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Email()
     */
    private $residentialEnquiryEmailAddress;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $residentialEnquiryMobileNumber;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $residentialEnquiryPhoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $residentialEnquiryAddressOne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $residentialEnquiryAddressTwo;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $residentialEnquiryPincode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $residentialEnquiryCity;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $residentialEnquiryLocation;

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
    private $residentialEnquiryCreateTime;

    /**
     * @ORM\ManyToOne(targetEntity=MstLeadStatus::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstLeadStatus;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $residentialEnquiryDescription;

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
    private $residentialEnquiryBudget;

    /**
     * @ORM\ManyToOne(targetEntity=MstRoomConfiguration::class)
     */
    private $mstRoomConfiguration;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $residentialEnquiryTitle;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProjectRoomConfiguration::class)
     */
    private $trnProjectRoomConfiguration;

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

    public function getResidentialEnquiryFirstName(): ?string
    {
        return $this->residentialEnquiryFirstName;
    }

    public function setResidentialEnquiryFirstName(?string $residentialEnquiryFirstName): self
    {
        $this->residentialEnquiryFirstName = $residentialEnquiryFirstName;

        return $this;
    }

    public function getResidentialEnquiryMiddleName(): ?string
    {
        return $this->residentialEnquiryMiddleName;
    }

    public function setResidentialEnquiryMiddleName(?string $residentialEnquiryMiddleName): self
    {
        $this->residentialEnquiryMiddleName = $residentialEnquiryMiddleName;

        return $this;
    }

    public function getResidentialEnquiryLastName(): ?string
    {
        return $this->residentialEnquiryLastName;
    }

    public function setResidentialEnquiryLastName(?string $residentialEnquiryLastName): self
    {
        $this->residentialEnquiryLastName = $residentialEnquiryLastName;

        return $this;
    }

    public function getResidentialEnquiryEmailAddress(): ?string
    {
        return $this->residentialEnquiryEmailAddress;
    }

    public function setResidentialEnquiryEmailAddress(?string $residentialEnquiryEmailAddress): self
    {
        $this->residentialEnquiryEmailAddress = $residentialEnquiryEmailAddress;

        return $this;
    }

    public function getResidentialEnquiryMobileNumber(): ?string
    {
        return $this->residentialEnquiryMobileNumber;
    }

    public function setResidentialEnquiryMobileNumber(?string $residentialEnquiryMobileNumber): self
    {
        $this->residentialEnquiryMobileNumber = $residentialEnquiryMobileNumber;

        return $this;
    }

    public function getResidentialEnquiryPhoneNumber(): ?string
    {
        return $this->residentialEnquiryPhoneNumber;
    }

    public function setResidentialEnquiryPhoneNumber(?string $residentialEnquiryPhoneNumber): self
    {
        $this->residentialEnquiryPhoneNumber = $residentialEnquiryPhoneNumber;

        return $this;
    }

    public function getResidentialEnquiryAddressOne(): ?string
    {
        return $this->residentialEnquiryAddressOne;
    }

    public function setResidentialEnquiryAddressOne(?string $residentialEnquiryAddressOne): self
    {
        $this->residentialEnquiryAddressOne = $residentialEnquiryAddressOne;

        return $this;
    }

    public function getResidentialEnquiryAddressTwo(): ?string
    {
        return $this->residentialEnquiryAddressTwo;
    }

    public function setResidentialEnquiryAddressTwo(?string $residentialEnquiryAddressTwo): self
    {
        $this->residentialEnquiryAddressTwo = $residentialEnquiryAddressTwo;

        return $this;
    }

    public function getResidentialEnquiryPincode(): ?string
    {
        return $this->residentialEnquiryPincode;
    }

    public function setResidentialEnquiryPincode(?string $residentialEnquiryPincode): self
    {
        $this->residentialEnquiryPincode = $residentialEnquiryPincode;

        return $this;
    }

    public function getResidentialEnquiryCity(): ?string
    {
        return $this->residentialEnquiryCity;
    }

    public function setResidentialEnquiryCity(?string $residentialEnquiryCity): self
    {
        $this->residentialEnquiryCity = $residentialEnquiryCity;

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

    public function getResidentialEnquiryCreateTime(): ?\DateTimeInterface
    {
        return $this->residentialEnquiryCreateTime;
    }

    public function setResidentialEnquiryCreateTime(\DateTimeInterface $residentialEnquiryCreateTime): self
    {
        $this->residentialEnquiryCreateTime = $residentialEnquiryCreateTime;

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

    public function getResidentialEnquiryDescription(): ?string
    {
        return $this->residentialEnquiryDescription;
    }

    public function setResidentialEnquiryDescription(?string $residentialEnquiryDescription): self
    {
        $this->residentialEnquiryDescription = $residentialEnquiryDescription;

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

    public function getResidentialEnquiryBudget(): ?string
    {
        return $this->residentialEnquiryBudget;
    }

    public function setResidentialEnquiryBudget(?string $residentialEnquiryBudget): self
    {
        $this->residentialEnquiryBudget = $residentialEnquiryBudget;

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

    public function getResidentialEnquiryTitle(): ?string
    {
        return $this->residentialEnquiryTitle;
    }

    public function setResidentialEnquiryTitle(?string $residentialEnquiryTitle): self
    {
        $this->residentialEnquiryTitle = $residentialEnquiryTitle;
        return $this;
    }

    public function getTrnProjectRoomConfiguration(): ?TrnProjectRoomConfiguration
    {
        return $this->trnProjectRoomConfiguration;
    }

    public function setTrnProjectRoomConfiguration(?TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        $this->trnProjectRoomConfiguration = $trnProjectRoomConfiguration;

        return $this;
    }
    public function getResidentialEnquiryLocation(): ?string
    {
        return $this->residentialEnquiryLocation;
    }

    public function setResidentialEnquiryLocation(?string $residentialEnquiryLocation): self
    {
        $this->residentialEnquiryLocation = $residentialEnquiryLocation;

        return $this;
    }
}

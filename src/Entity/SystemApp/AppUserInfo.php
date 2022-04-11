<?php

namespace App\Entity\SystemApp;

use App\Entity\Master\MstDesignation;
use App\Entity\Master\MstSalutation;
use App\Entity\Organization\OrgCompany;
use App\Entity\Organization\OrgCompanyOffice;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Entity\Transaction\TrnVendorPartnerOffices;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemApp\AppUserInfoRepository")
 * @ORM\Table("appuserinfo")
 */
class AppUserInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\SystemApp\AppUser", inversedBy="appUserInfo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $appUser;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank(message="user.userEmail.not_blank")
     * @Assert\Email(message="comment.invalid_email")
     */
    private $userEmail;

    /**
     * @ORM\ManyToOne(targetEntity=MstSalutation::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstSalutation;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     *@Assert\NotBlank(message="user.userFirstName.not_blank")
     */
    private $userFirstName;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $userMiddleName;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\NotBlank(message="user.userLastName.not_blank")
     */
    private $userLastName;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     *
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      minMessage = "Your mobile number must be {{ limit }} characters long",
     *      maxMessage = "Yourmobile number cannot be longer than {{ limit }} characters",
     * )
     */
    private $userMobileNumber;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $userAvatarImage;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $userAvatarImagePath;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization\OrgCompany", inversedBy="appUserInfo")
     */
    private $orgCompany;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization\OrgCompanyOffice", inversedBy="appUserInfo")
     */
    private $orgCompanyOffice;

    /**
     * @ORM\ManyToOne(targetEntity=TrnVendorPartnerDetails::class)
     */
    private $trnVendorPartnerDetails;

    /**
     * @ORM\ManyToOne(targetEntity=TrnVendorPartnerOffices::class)
     */
    private $trnVendorPartnerOffices;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $mobileNoCountryCode;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAccessToVendorPortal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profilePic;

    /**
     * @ORM\ManyToOne(targetEntity=MstDesignation::class)
     */
    private $mstDesignation;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $userIpAddress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(AppUser $appUser): self
    {
        $this->appUser = $appUser;
        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;
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

    public function getUserFirstName(): ?string
    {
        return $this->userFirstName;
    }

    public function setUserFirstName(?string $userFirstName): self
    {
        $this->userFirstName = $userFirstName;

        return $this;
    }

    public function getUserMiddleName(): ?string
    {
        return $this->userMiddleName;
    }

    public function setUserMiddleName(?string $userMiddleName): self
    {
        $this->userMiddleName = $userMiddleName;
        return $this;
    }

    public function getUserLastName(): ?string
    {
        return $this->userLastName;
    }

    public function setUserLastName(?string $userLastName): self
    {
        $this->userLastName = $userLastName;
        return $this;
    }

    public function getUserMobileNumber(): ?int
    {
        return $this->userMobileNumber;
    }

    public function setUserMobileNumber(int $userMobileNumber): self
    {
        $this->userMobileNumber = $userMobileNumber;
        return $this;
    }

    public function getUserAvatarImage(): ?string
    {
        return $this->userAvatarImage;
    }

    public function setUserAvatarImage(?string $userAvatarImage): self
    {
        $this->userAvatarImage = $userAvatarImage;
        return $this;
    }

    public function getUserAvatarImagePath(): ?string
    {
        return FileUploaderHelper::PRIVATE_FILE.'/'.$this->getUserAvatarImage();
    }

    public function setUserAvatarImagePath(?string $userAvatarImagePath): self
    {
        $this->userAvatarImagePath = $userAvatarImagePath;
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

    public function getOrgCompanyOffice(): ?OrgCompanyOffice
    {
        return $this->orgCompanyOffice;
    }

    public function setOrgCompanyOffice(?OrgCompanyOffice $orgCompanyOffice): self
    {
        $this->orgCompanyOffice = $orgCompanyOffice;
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

    public function getTrnVendorPartnerOffices(): ?TrnVendorPartnerOffices
    {
        return $this->trnVendorPartnerOffices;
    }

    public function setTrnVendorPartnerOffices(?TrnVendorPartnerOffices $trnVendorPartnerOffices): self
    {
        $this->trnVendorPartnerOffices = $trnVendorPartnerOffices;

        return $this;
    }

    public function getMobileNoCountryCode(): ?string
    {
        return $this->mobileNoCountryCode;
    }

    public function setMobileNoCountryCode(?string $mobileNoCountryCode): self
    {
        $this->mobileNoCountryCode = $mobileNoCountryCode;

        return $this;
    }

    public function getIsAccessToVendorPortal(): ?bool
    {
        return $this->isAccessToVendorPortal;
    }

    public function setIsAccessToVendorPortal(?bool $isAccessToVendorPortal): self
    {
        $this->isAccessToVendorPortal = $isAccessToVendorPortal;

        return $this;
    }

    public function getProfilePic(): ?string
    {
        return $this->profilePic;
    }

    public function setProfilePic(?string $profilePic): self
    {
        $this->profilePic = $profilePic;

        return $this;
    }

    public function getMstDesignation(): ?MstDesignation
    {
        return $this->mstDesignation;
    }

    public function setMstDesignation(?MstDesignation $mstDesignation): self
    {
        $this->mstDesignation = $mstDesignation;

        return $this;
    }

    public function getUserIpAddress(): ?string
    {
        return $this->userIpAddress;
    }

    public function setUserIpAddress(?string $userIpAddress): self
    {
        $this->userIpAddress = $userIpAddress;

        return $this;
    }
}

<?php

namespace App\Entity\SystemApp;

use App\Entity\Organization\OrgCompany;
use App\Entity\Organization\OrgCompanyOffice;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemApp\AppUserRepository")
 * @ORM\Table("appuser",
 *     indexes={@ORM\Index(name="Name_Active_idx", columns={"userName","isActive"})}
 * )
 * @UniqueEntity(fields={"userName"}, message="user.userName.unique")
 *
 */
class AppUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank(message="user.userName.not_blank")
     * @Groups({"read", "write"})
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $userPassword;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read", "write"})
     */
    private $userRole = [];

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $userSessionId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $userLastLogin;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $userResetPasswordToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $userResetPasswordTokenExpiry;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $userCreationToken;

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
     * @ORM\OneToOne(targetEntity="App\Entity\SystemApp\AppUserInfo", mappedBy="appUser", cascade={"persist", "remove"})
     */
    public $appUserInfo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SystemApp\AppUserCategory", inversedBy="appUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $appUserCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setUserPassword(string $userPassword): self
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    public function getUserRole(): ?array
    {
        return $this->userRole;
    }

    public function setUserRole(array $userRole): self
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getUserSessionId(): ?string
    {
        return $this->userSessionId;
    }

    public function setUserSessionId(?string $userSessionId): self
    {
        $this->userSessionId = $userSessionId;

        return $this;
    }

    public function getUserLastLogin(): ?DateTimeInterface
    {
        return $this->userLastLogin;
    }

    public function setUserLastLogin(?DateTimeInterface $userLastLogin): self
    {
        $this->userLastLogin = $userLastLogin;

        return $this;
    }

    public function getUserResetPasswordToken(): ?string
    {
        return $this->userResetPasswordToken;
    }

    public function setUserResetPasswordToken(?string $userResetPasswordToken): self
    {
        $this->userResetPasswordToken = $userResetPasswordToken;

        return $this;
    }

    public function getUserResetPasswordTokenExpiry(): ?DateTimeInterface
    {
        return $this->userResetPasswordTokenExpiry;
    }

    public function setUserResetPasswordTokenExpiry(?DateTimeInterface $userResetPasswordTokenExpiry): self
    {
        $this->userResetPasswordTokenExpiry = $userResetPasswordTokenExpiry;

        return $this;
    }

    public function getUserCreationToken(): ?string
    {
        return $this->userCreationToken;
    }

    public function setUserCreationToken(?string $userCreationToken): self
    {
        $this->userCreationToken = $userCreationToken;

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

    public function __toString()
    {
        return $this->getAppUserInfo()->getMstSalutation().' '.$this->getAppUserInfo()->getUserFirstName().' '.$this->getAppUserInfo()->getUserMiddleName().' '.$this->getAppUserInfo()->getUserLastName();
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->userName;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security-bkp
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        $userRole =  $this->userRole;
        // guarantee every user at least has ROLE_USER
        $userRole[] = 'ROLE_APP_USER';
        return array_unique($userRole);
    }

    public function setRoles(array $roles): self
    {
        $this->userRole = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->userPassword;
    }

    public function setPassword(string $password): self
    {
        $this->userPassword = $password;

        return $this;
    }

    public function getAppUserInfo(): ?AppUserInfo
    {
        return $this->appUserInfo;
    }

    public function setAppUserInfo(AppUserInfo $appUserInfo): self
    {
        $this->appUserInfo = $appUserInfo;

        // set the owning side of the relation if necessary
        if ($appUserInfo->getAppUser() !== $this) {
            $appUserInfo->setAppUser($this);
        }

        return $this;
    }

    public function getAppUserCategory(): ?AppUserCategory
    {
        return $this->appUserCategory;
    }

    public function setAppUserCategory(?AppUserCategory $appUserCategory): self
    {
        $this->appUserCategory = $appUserCategory;

        return $this;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

}

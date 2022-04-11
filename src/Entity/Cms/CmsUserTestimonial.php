<?php

namespace App\Entity\Cms;

use App\Entity\SystemApp\AppUser;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cms\CmsUserTestimonialRepository")
 * @ORM\Table("cmsusertestimonial")
 */
class CmsUserTestimonial
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $appUser;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $testimonialFor;

    /**
     * @ORM\Column(type="text")
     */
    private $testimonialDetail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDateTime;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userImagePath;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $userDesignation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userFullName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(?AppUser $appUser): self
    {
        $this->appUser = $appUser;

        return $this;
    }

    public function getTestimonialFor(): ?string
    {
        return $this->testimonialFor;
    }

    public function setTestimonialFor(string $testimonialFor): self
    {
        $this->testimonialFor = $testimonialFor;

        return $this;
    }

    public function getTestimonialDetail(): ?string
    {
        return $this->testimonialDetail;
    }

    public function setTestimonialDetail(string $testimonialDetail): self
    {
        $this->testimonialDetail = $testimonialDetail;

        return $this;
    }

    public function getCreateDateTime(): ?\DateTimeInterface
    {
        return $this->createDateTime;
    }

    public function setCreateDateTime(\DateTimeInterface $createDateTime): self
    {
        $this->createDateTime = $createDateTime;

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

    public function getUserImage(): ?string
    {
        return $this->userImage;
    }

    public function setUserImage(?string $userImage): self
    {
        $this->userImage = $userImage;

        return $this;
    }

    public function getUserImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getUserImage();
    }

    public function setUserImagePath(?string $userImagePath): self
    {
        $this->userImagePath = $userImagePath;

        return $this;
    }

    public function getUserDesignation(): ?string
    {
        return $this->userDesignation;
    }

    public function setUserDesignation(?string $userDesignation): self
    {
        $this->userDesignation = $userDesignation;

        return $this;
    }

    public function getUserFullName(): ?string
    {
        return $this->userFullName;
    }

    public function setUserFullName(?string $userFullName): self
    {
        $this->userFullName = $userFullName;

        return $this;
    }




}

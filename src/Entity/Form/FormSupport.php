<?php

namespace App\Entity\Form;

use App\Repository\Form\FormSupportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormSupportRepository::class)
 * @ORM\Table("formsupport")
 */
class FormSupport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $supportForm;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $emailAddress;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $mobileNumber;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $cityName;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $interestType;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $currentAssigment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $futureAssigment;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $helpType = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $messageDetail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDateTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupportForm(): ?string
    {
        return $this->supportForm;
    }

    public function setSupportForm(string $supportForm): self
    {
        $this->supportForm = $supportForm;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber(?string $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(?string $cityName): self
    {
        $this->cityName = $cityName;

        return $this;
    }

    public function getInterestType(): ?string
    {
        return $this->interestType;
    }

    public function setInterestType(?string $interestType): self
    {
        $this->interestType = $interestType;

        return $this;
    }

    public function getCurrentAssigment(): ?string
    {
        return $this->currentAssigment;
    }

    public function setCurrentAssigment(?string $currentAssigment): self
    {
        $this->currentAssigment = $currentAssigment;

        return $this;
    }

    public function getFutureAssigment(): ?string
    {
        return $this->futureAssigment;
    }

    public function setFutureAssigment(?string $futureAssigment): self
    {
        $this->futureAssigment = $futureAssigment;

        return $this;
    }

    public function getHelpType(): ?array
    {
        return $this->helpType;
    }

    public function setHelpType(?array $helpType): self
    {
        $this->helpType = $helpType;

        return $this;
    }

    public function getMessageDetail(): ?string
    {
        return $this->messageDetail;
    }

    public function setMessageDetail(?string $messageDetail): self
    {
        $this->messageDetail = $messageDetail;

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


}

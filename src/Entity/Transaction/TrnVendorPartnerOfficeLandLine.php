<?php

namespace App\Entity\Transaction;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Master\MstDepartment;
use App\Repository\Transaction\TrnVendorPartnerOfficeLandLineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TrnVendorPartnerOfficeLandLineRepository::class)
 * @ORM\Table("trnvendorpartnerofficelandline")
 */
class TrnVendorPartnerOfficeLandLine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TrnVendorPartnerOffices::class, inversedBy="trnVendorPartnerOfficeLandLines")
     */
    private $trnVendorPartnerOffices;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $contactNoCountryCode;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $contactNoCityCode;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $contactNo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=MstDepartment::class)
     */
    private $mstDepartment;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContactNoCountryCode(): ?string
    {
        return $this->contactNoCountryCode;
    }

    public function setContactNoCountryCode(?string $contactNoCountryCode): self
    {
        $this->contactNoCountryCode = $contactNoCountryCode;

        return $this;
    }

    public function getContactNoCityCode(): ?string
    {
        return $this->contactNoCityCode;
    }

    public function setContactNoCityCode(?string $contactNoCityCode): self
    {
        $this->contactNoCityCode = $contactNoCityCode;

        return $this;
    }

    public function getContactNo(): ?string
    {
        return $this->contactNo;
    }

    public function setContactNo(?string $contactNo): self
    {
        $this->contactNo = $contactNo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMstDepartment(): ?MstDepartment
    {
        return $this->mstDepartment;
    }

    public function setMstDepartment(?MstDepartment $mstDepartment): self
    {
        $this->mstDepartment = $mstDepartment;

        return $this;
    }
}

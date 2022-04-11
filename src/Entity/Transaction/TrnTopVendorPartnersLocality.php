<?php

namespace App\Entity\Transaction;

use App\Entity\Master\MstPincode;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstState;
use App\Entity\SystemApp\AppUser;
use App\Repository\Transaction\TrnTopVendorPartnersLocalityRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnTopVendorPartnersLocalityRepository::class)
 * @ORM\Table("trntopvendorpartnerslocality")
 */
class TrnTopVendorPartnersLocality
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=TrnTopVendorPartners::class, inversedBy="trnTopVendorPartnersLocalities")
     */
    private $trnTopVendorPartners;

    /**
     * @ORM\ManyToMany(targetEntity=MstPincode::class, inversedBy="trnTopVendorPartnersLocalities")
     */
    private $mstPincode;

    /**
     * @ORM\ManyToOne(targetEntity=MstState::class)
     */
    private $mstState;

    /**
     * @ORM\ManyToOne(targetEntity=MstCity::class, inversedBy="trnTopVendorPartnersLocalities")
     */
    private $mstCity;

    public function __construct()
    {
        $this->mstPincode = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->createdBy;
    }

    public function setCreatedBy(?AppUser $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getTrnTopVendorPartners(): ?TrnTopVendorPartners
    {
        return $this->trnTopVendorPartners;
    }

    public function setTrnTopVendorPartners(?TrnTopVendorPartners $trnTopVendorPartners): self
    {
        $this->trnTopVendorPartners = $trnTopVendorPartners;

        return $this;
    }

    /**
     * @return Collection|MstPincode[]
     */
    public function getMstPincode(): Collection
    {
        return $this->mstPincode;
    }

    public function addMstPincode(MstPincode $mstPincode): self
    {
        if (!$this->mstPincode->contains($mstPincode)) {
            $this->mstPincode[] = $mstPincode;
        }

        return $this;
    }

    public function removeMstPincode(MstPincode $mstPincode): self
    {
        $this->mstPincode->removeElement($mstPincode);

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
}

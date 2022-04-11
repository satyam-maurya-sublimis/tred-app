<?php

namespace App\Entity\Transaction;

use App\Repository\Transaction\TrnProjectTowerAdditionalDetailRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnProjectTowerAdditionalDetailRepository::class)
 * @ORM\Table ("trnprojecttoweradditionaldetail")
 */
 
class TrnProjectTowerAdditionalDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $additionalDetail;

    /**
     * @ORM\ManyToOne(targetEntity=TrnProjectTowerDetails::class, inversedBy="trnProjectTowerAdditionalDetails")
     */
    private $trnProjectTowerDetails;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdditionalDetail(): ?string
    {
        return $this->additionalDetail;
    }

    public function setAdditionalDetail(?string $additionalDetail): self
    {
        $this->additionalDetail = $additionalDetail;

        return $this;
    }

    public function getTrnProjectTowerDetails(): ?TrnProjectTowerDetails
    {
        return $this->trnProjectTowerDetails;
    }

    public function setTrnProjectTowerDetails(?TrnProjectTowerDetails $trnProjectTowerDetails): self
    {
        $this->trnProjectTowerDetails = $trnProjectTowerDetails;

        return $this;
    }
}

<?php

namespace App\Entity\Master;

use App\Repository\Master\MstSubscriptionCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MstSubscriptionCategoryRepository::class)
 * @ORM\Table ("mstsubscriptioncategory")
 */
class MstSubscriptionCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subscriptionCategory;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriptionCategory(): ?string
    {
        return $this->subscriptionCategory;
    }

    public function setSubscriptionCategory(string $subscriptionCategory): self
    {
        $this->subscriptionCategory = $subscriptionCategory;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

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

    public function __toString()
    {
        return $this->subscriptionCategory;
    }
}

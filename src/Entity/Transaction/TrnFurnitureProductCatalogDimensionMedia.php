<?php

namespace App\Entity\Transaction;

use App\Repository\Transaction\TrnFurnitureProductCatalogDimensionMediaRepository;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnFurnitureProductCatalogDimensionMediaRepository::class)
 * @ORM\Table("trnfurnitureproductcatalogdimensionmedia")
 */
class TrnFurnitureProductCatalogDimensionMedia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $dimension;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaName;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $mediaType;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $mediaPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaAlText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaFileName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mediaFilePath;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity=TrnFurnitureProductCatalog::class, inversedBy="trnFurnitureProductCatalogDimensionMedia")
     */
    private $trnFurnitureProductCatalog;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDimension(): ?string
    {
        return $this->dimension;
    }

    public function setDimension(string $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
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

    public function getTrnFurnitureProductCatalog(): ?TrnFurnitureProductCatalog
    {
        return $this->trnFurnitureProductCatalog;
    }

    public function setTrnFurnitureProductCatalog(?TrnFurnitureProductCatalog $trnFurnitureProductCatalog): self
    {
        $this->trnFurnitureProductCatalog = $trnFurnitureProductCatalog;

        return $this;
    }

    public function getMediaName(): ?string
    {
        return $this->mediaName;
    }

    public function setMediaName(?string $mediaName): self
    {
        $this->mediaName = $mediaName;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getMediaPath(): ?string
    {
        return $this->mediaPath;
    }

    public function setMediaPath(?string $mediaPath): self
    {
        $this->mediaPath = $mediaPath;

        return $this;
    }

    public function getMediaAlText(): ?string
    {
        return $this->mediaAlText;
    }

    public function setMediaAlText(?string $mediaAlText): self
    {
        $this->mediaAlText = $mediaAlText;

        return $this;
    }

    public function getMediaTitle(): ?string
    {
        return $this->mediaAlText;
    }

    public function setMediaTitle(?string $mediaTitle): self
    {
        $this->mediaTitle = $mediaTitle;
        return $this;
    }

    public function getMediaFileName(): ?string
    {
        return $this->mediaFileName;
    }

    public function setMediaFileName(string $mediaFileName): self
    {
        $this->mediaFileName = $mediaFileName;

        return $this;
    }

    public function getMediaFilePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getMediaFileName();
    }

    public function setMediaFilePath(string $mediaFilePath): self
    {
        $this->mediaFilePath = $mediaFilePath;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

}

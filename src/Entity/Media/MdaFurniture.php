<?php

namespace App\Entity\Media;

use App\Entity\Transaction\TrnFurniture;
use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Entity\Transaction\TrnFurnitureProductCatalogDimensionMedia;
use App\Repository\Media\MdaFurnitureRepository;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MdaFurnitureRepository::class)
 * @ORM\Table("mdafurniture")
 */

class MdaFurniture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isDimensionMedia;

    /**
     * @ORM\ManyToOne(targetEntity=TrnFurnitureProductCatalog::class)
     */
    private $trnFurnitureProductCatalog;

    /**
     * @ORM\ManyToOne(targetEntity=TrnFurniture::class, inversedBy="mdaFurniture")
     */
    private $trnFurniture;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getIsDimensionMedia(): ?bool
    {
        return $this->isDimensionMedia;
    }

    public function setIsDimensionMedia(?bool $isDimensionMedia): self
    {
        $this->isDimensionMedia = $isDimensionMedia;

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

    public function getTrnFurniture(): ?TrnFurniture
    {
        return $this->trnFurniture;
    }

    public function setTrnFurniture(?TrnFurniture $trnFurniture): self
    {
        $this->trnFurniture = $trnFurniture;

        return $this;
    }
}

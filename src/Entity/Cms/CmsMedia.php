<?php

namespace App\Entity\Cms;

use App\Entity\Master\MstMediaCategory;
use App\Repository\Cms\CmsMediaRepository;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CmsMediaRepository::class)
 * @ORM\Table("cmsmedia", indexes={@ORM\Index(name="Name_Active_idx", columns={"mediaName", "isActive"})})
 */
class CmsMedia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstMediaCategory::class)
     */
    private $mstMediaCategory;

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
    private $sequenceNo;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMstMediaCategory(): ?MstMediaCategory
    {
        return $this->mstMediaCategory;
    }

    public function setMstMediaCategory(?MstMediaCategory $mstMediaCategory): self
    {
        $this->mstMediaCategory = $mstMediaCategory;

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

    public function getSequenceNo(): ?int
    {
        return $this->sequenceNo;
    }

    public function setSequenceNo(?int $sequenceNo): self
    {
        $this->sequenceNo = $sequenceNo;

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
        return $this->mediaName;
    }


}

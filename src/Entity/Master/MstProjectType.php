<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Master\MstProjectTypeRepository;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}},
 *     collectionOperations={
 *          "get"={},
 *     },
 *     itemOperations={
 *          "get"={},
 *     }
 * )
 * @ORM\Entity(repositoryClass=MstProjectTypeRepository::class)
 * @ORM\Table("mstprojecttype")
 * @UniqueEntity(fields={"projectType"}, message="The value is already in the system")
 */
class MstProjectType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"read"})
     */
    private $projectType;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $projectTypeDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $projectTypeImage;

    /**
     * @ORM\Column(type="string", length=24, nullable=true)
     */
    private $projectTypeMediaType;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $projectTypeImagePath;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $projectTypeVideo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $projectTypeVideoPath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $projectTypeImageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slugName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectType(): ?string
    {
        return $this->projectType;
    }

    public function setProjectType(string $projectType): self
    {
        $this->projectType = $projectType;

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

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->projectType;
    }

    public function getProjectTypeDescription(): ?string
    {
        return $this->projectTypeDescription;
    }

    public function setProjectTypeDescription(?string $projectTypeDescription): self
    {
        $this->projectTypeDescription = $projectTypeDescription;

        return $this;
    }

    public function getProjectTypeImage(): ?string
    {
        return $this->projectTypeImage;
    }

    public function setProjectTypeImage(?string $projectTypeImage): self
    {
        $this->projectTypeImage = $projectTypeImage;

        return $this;
    }

    public function getProjectTypeMediaType(): ?string
    {
        return $this->projectTypeMediaType;
    }

    public function setProjectTypeMediaType(?string $projectTypeMediaType): self
    {
        $this->projectTypeMediaType = $projectTypeMediaType;

        return $this;
    }

    public function getProjectTypeImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getProjectTypeImage();
    }

    public function setProjectTypeImagePath(?string $projectTypeImagePath): self
    {
        $this->projectTypeImagePath = $projectTypeImagePath;

        return $this;
    }

    public function getProjectTypeVideo(): ?string
    {
        return $this->projectTypeVideo;
    }

    public function setProjectTypeVideo(?string $projectTypeVideo): self
    {
        $this->projectTypeVideo = $projectTypeVideo;

        return $this;
    }

    public function getProjectTypeVideoPath(): ?string
    {
        return $this->projectTypeVideoPath;
    }

    public function setProjectTypeVideoPath(?string $projectTypeVideoPath): self
    {
        $this->projectTypeVideoPath = $projectTypeVideoPath;

        return $this;
    }

    public function getProjectTypeImageName(): ?string
    {
        return $this->projectTypeImageName;
    }

    public function setProjectTypeImageName(?string $projectTypeImageName): self
    {
        $this->projectTypeImageName = $projectTypeImageName;

        return $this;
    }

    public function getSlugName(): ?string
    {
        return $this->slugName;
    }

    public function setSlugName(?string $slugName): self
    {
        $this->slugName = $slugName;

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

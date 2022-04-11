<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\Master\MstUploadDocumentTypeRepository;
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
 * @ORM\Entity(repositoryClass=MstUploadDocumentTypeRepository::class)
 * @ORM\Table("mstuploaddocumentype")
 * @UniqueEntity(fields={"uploadDocumentType"}, message="The value is already in the system")
 */
class MstUploadDocumentType
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
    private $uploadDocumentType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $documentTypeFor;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read"})
     */
    private $isActive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUploadDocumentType(): ?string
    {
        return $this->uploadDocumentType;
    }

    public function setUploadDocumentType(string $uploadDocumentType): self
    {
        $this->uploadDocumentType = $uploadDocumentType;

        return $this;
    }

    public function getDocumentTypeFor(): ?string
    {
        return $this->documentTypeFor;
    }

    public function setDocumentTypeFor(string $documentTypeFor): self
    {
        $this->documentTypeFor = $documentTypeFor;

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
        return $this->uploadDocumentType;
    }
}

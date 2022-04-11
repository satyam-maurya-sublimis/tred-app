<?php

namespace App\Entity\Cms;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cms\CmsPressRoomRepository")
 * @ORM\Table("cmspressroom")
 */
class CmsPressRoom
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $articleDateTime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $articleHeading;

    /**
     * @ORM\Column(type="text")
     */
    private $articleContent;

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

    public function getArticleDateTime(): ?\DateTimeInterface
    {
        return $this->articleDateTime;
    }

    public function setArticleDateTime(\DateTimeInterface $articleDateTime): self
    {
        $this->articleDateTime = $articleDateTime;

        return $this;
    }

    public function getArticleHeading(): ?string
    {
        return $this->articleHeading;
    }

    public function setArticleHeading(string $articleHeading): self
    {
        $this->articleHeading = $articleHeading;

        return $this;
    }

    public function getArticleContent(): ?string
    {
        return $this->articleContent;
    }

    public function setArticleContent(string $articleContent): self
    {
        $this->articleContent = $articleContent;

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
}

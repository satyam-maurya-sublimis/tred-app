<?php

namespace App\Entity\Cms;

use App\Repository\Cms\CmsLandingPageContentRepository;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CmsLandingPageContentRepository::class)
 * @ORM\Table("cmslandingpagecontent")
 */
class CmsLandingPageContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pageContent;

    /**
     * @ORM\ManyToOne(targetEntity=CmsLandingPage::class, inversedBy="cmsLandingPageContents")
     */
    private $cmsLandingPage;

    /**
     * @ORM\Column(type="string", length=24, nullable=true)
     */
    private $pageMediaType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pageImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pageImageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pageImageTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pageImageAlt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pageImagePath;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $pageVideo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pageVideoPath;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $pageMediaPosition;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageContent(): ?string
    {
        return $this->pageContent;
    }

    public function setPageContent(?string $pageContent): self
    {
        $this->pageContent = $pageContent;

        return $this;
    }

    public function getCmsLandingPage(): ?CmsLandingPage
    {
        return $this->cmsLandingPage;
    }

    public function setCmsLandingPage(?CmsLandingPage $cmsLandingPage): self
    {
        $this->cmsLandingPage = $cmsLandingPage;

        return $this;
    }
    public function getPageMediaType(): ?string
    {
        return $this->pageMediaType;
    }

    public function setPageMediaType(?string $pageMediaType): self
    {
        $this->pageMediaType = $pageMediaType;

        return $this;
    }

    public function getPageImage(): ?string
    {
        return $this->pageImage;
    }

    public function setPageImage(?string $pageImage): self
    {
        $this->pageImage = $pageImage;

        return $this;
    }

    public function getPageImageName(): ?string
    {
        return $this->pageImageName;
    }

    public function setPageImageName(?string $pageImageName): self
    {
        $this->pageImageName = $pageImageName;

        return $this;
    }

    public function getPageImageTitle(): ?string
    {
        return $this->pageImageTitle;
    }

    public function setPageImageTitle(?string $pageImageTitle): self
    {
        $this->pageImageTitle = $pageImageTitle;

        return $this;
    }

    public function getPageImageAlt(): ?string
    {
        return $this->pageImageAlt;
    }

    public function setPageImageAlt(?string $pageImageAlt): self
    {
        $this->pageImageAlt = $pageImageAlt;

        return $this;
    }

    public function getPageImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->pageImage;
    }

    public function setPageImagePath(?string $pageImagePath): self
    {
        $this->pageImagePath = $pageImagePath;

        return $this;
    }

    public function getPageVideo(): ?string
    {
        return $this->pageVideo;
    }

    public function setPageVideo(?string $pageVideo): self
    {
        $this->pageVideo = $pageVideo;

        return $this;
    }

    public function getPageVideoPath(): ?string
    {
        return $this->pageVideoPath;
    }

    public function setPageVideoPath(string $pageVideoPath): self
    {
        $this->pageVideoPath = $pageVideoPath;

        return $this;
    }

    public function getPageMediaPosition(): ?string
    {
        return $this->pageMediaPosition;
    }

    public function setPageMediaPosition(?string $pageMediaPosition): self
    {
        $this->pageMediaPosition = $pageMediaPosition;

        return $this;
    }
}

<?php

namespace App\Entity\Cms;

use App\Entity\Master\MstProductCategory;
use App\Repository\Cms\CmsPageContentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CmsPageContentRepository::class)
 * @ORM\Table("cmspagecontent")
 */
class CmsPageContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CmsPage::class, inversedBy="cmsPageContent")
     * @ORM\JoinColumn(nullable=true)
     */
    private $cmsPage;

    /**
     * @ORM\Column(type="text")
     */
    private $pageContent;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $cmsPageContentPosition;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class, inversedBy="cmsPageContent")
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstProductCategory;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCmsPage(): ?CmsPage
    {
        return $this->cmsPage;
    }

    public function setCmsPage(?CmsPage $cmsPage): self
    {
        $this->cmsPage = $cmsPage;

        return $this;
    }

    public function getPageContent(): ?string
    {
        return $this->pageContent;
    }

    public function setPageContent(string $pageContent): self
    {
        $this->pageContent = $pageContent;

        return $this;
    }

    public function __toString()
    {
        return $this->pageContent();
    }

    public function getCmsPageContentPosition(): ?string
    {
        return $this->cmsPageContentPosition;
    }

    public function setCmsPageContentPosition(?string $cmsPageContentPosition): self
    {
        $this->cmsPageContentPosition = $cmsPageContentPosition;

        return $this;
    }

    public function getMstProductCategory(): ?MstProductCategory
    {
        return $this->mstProductCategory;
    }

    public function setMstProductCategory(?MstProductCategory $mstProductCategory): self
    {
        $this->mstProductCategory = $mstProductCategory;

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

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

}

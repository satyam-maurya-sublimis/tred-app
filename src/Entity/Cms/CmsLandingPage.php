<?php

namespace App\Entity\Cms;

use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductType;
use App\Repository\Cms\CmsLandingPageRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CmsLandingPageRepository::class)
 * @ORM\Table("cmslandingpage")
 */
class CmsLandingPage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProductCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductType::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstProductType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cmsLandingPageSlugName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cmsLandingPageBannerTitle;

    /**
     * @ORM\Column(type="string", length=24, nullable=true)
     */
    private $cmsLandingPageMediaType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cmsLandingPageImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cmsLandingPageImageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cmsLandingPageImageTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cmsLandingPageImageAlt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $cmsLandingPageImagePath;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $cmsLandingPageVideo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $cmsLandingPageVideoPath;

    /**
     * @ORM\Column(type="guid", nullable=true)
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=CmsLandingPageContent::class, mappedBy="cmsLandingPage", cascade={"persist","remove"})
     */
    private $cmsLandingPageContents;

    /**
     * @ORM\OneToMany(targetEntity=CmsBanner::class, mappedBy="cmsLandingPage")
     */
    private $cmsBanners;

    public function __construct()
    {
        $this->cmsLandingPageContents = new ArrayCollection();
        $this->cmsBanners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMstProductType(): ?MstProductType
    {
        return $this->mstProductType;
    }

    public function setMstProductType(?MstProductType $mstProductType): self
    {
        $this->mstProductType = $mstProductType;

        return $this;
    }

    public function getCmsLandingPageSlugName(): ?string
    {
        return $this->cmsLandingPageSlugName;
    }

    public function setCmsLandingPageSlugName(?string $cmsLandingPageSlugName): self
    {
        $this->cmsLandingPageSlugName = $cmsLandingPageSlugName;

        return $this;
    }

    public function getCmsLandingPageBannerTitle(): ?string
    {
        return $this->cmsLandingPageBannerTitle;
    }

    public function setCmsLandingPageBannerTitle(?string $cmsLandingPageBannerTitle): self
    {
        $this->cmsLandingPageBannerTitle = $cmsLandingPageBannerTitle;

        return $this;
    }

    public function getCmsLandingPageMediaType(): ?string
    {
        return $this->cmsLandingPageMediaType;
    }

    public function setCmsLandingPageMediaType(?string $cmsLandingPageMediaType): self
    {
        $this->cmsLandingPageMediaType = $cmsLandingPageMediaType;

        return $this;
    }

    public function getCmsLandingPageImage(): ?string
    {
        return $this->cmsLandingPageImage;
    }

    public function setCmsLandingPageImage(?string $cmsLandingPageImage): self
    {
        $this->cmsLandingPageImage = $cmsLandingPageImage;

        return $this;
    }

    public function getCmsLandingPageImageName(): ?string
    {
        return $this->cmsLandingPageImageName;
    }

    public function setCmsLandingPageImageName(?string $cmsLandingPageImageName): self
    {
        $this->cmsLandingPageImageName = $cmsLandingPageImageName;

        return $this;
    }

    public function getCmsLandingPageImageTitle(): ?string
    {
        return $this->cmsLandingPageImageTitle;
    }

    public function setCmsLandingPageImageTitle(?string $cmsLandingPageImageTitle): self
    {
        $this->cmsLandingPageImageTitle = $cmsLandingPageImageTitle;

        return $this;
    }

    public function getCmsLandingPageImageAlt(): ?string
    {
        return $this->cmsLandingPageImageAlt;
    }

    public function setCmsLandingPageImageAlt(?string $cmsLandingPageImageAlt): self
    {
        $this->cmsLandingPageImageAlt = $cmsLandingPageImageAlt;

        return $this;
    }

    public function getCmsLandingPageImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->cmsLandingPageImage;
    }

    public function setCmsLandingPageImagePath(?string $cmsLandingPageImagePath): self
    {
        $this->cmsLandingPageImagePath = $cmsLandingPageImagePath;

        return $this;
    }

    public function getCmsLandingPageVideo(): ?string
    {
        return $this->cmsLandingPageVideo;
    }

    public function setCmsLandingPageVideo(?string $cmsLandingPageVideo): self
    {
        $this->cmsLandingPageVideo = $cmsLandingPageVideo;

        return $this;
    }

    public function getCmsLandingPageVideoPath(): ?string
    {
        return $this->cmsLandingPageVideoPath;
    }

    public function setCmsLandingPageVideoPath(string $cmsLandingPageVideoPath): self
    {
        $this->cmsLandingPageVideoPath = $cmsLandingPageVideoPath;

        return $this;
    }

    public function getRowId(): ?string
    {
        return $this->rowId;
    }

    public function setRowId(?string $rowId): self
    {
        $this->rowId = $rowId;

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

    /**
     * @return Collection|CmsLandingPageContent[]
     */
    public function getCmsLandingPageContents(): Collection
    {
        return $this->cmsLandingPageContents;
    }

    public function addCmsLandingPageContent(CmsLandingPageContent $cmsLandingPageContent): self
    {
        if (!$this->cmsLandingPageContents->contains($cmsLandingPageContent)) {
            $this->cmsLandingPageContents[] = $cmsLandingPageContent;
            $cmsLandingPageContent->setCmsLandingPage($this);
        }

        return $this;
    }

    public function removeCmsLandingPageContent(CmsLandingPageContent $cmsLandingPageContent): self
    {
        if ($this->cmsLandingPageContents->removeElement($cmsLandingPageContent)) {
            // set the owning side to null (unless already changed)
            if ($cmsLandingPageContent->getCmsLandingPage() === $this) {
                $cmsLandingPageContent->setCmsLandingPage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CmsBanner[]
     */
    public function getCmsBanners(): Collection
    {
        return $this->cmsBanners;
    }

    public function addCmsBanner(CmsBanner $cmsBanner): self
    {
        if (!$this->cmsBanners->contains($cmsBanner)) {
            $this->cmsBanners[] = $cmsBanner;
            $cmsBanner->setCmsLandingPage($this);
        }

        return $this;
    }

    public function removeCmsBanner(CmsBanner $cmsBanner): self
    {
        if ($this->cmsBanners->removeElement($cmsBanner)) {
            // set the owning side to null (unless already changed)
            if ($cmsBanner->getCmsLandingPage() === $this) {
                $cmsBanner->setCmsLandingPage(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->mstProductCategory.'/'.$this->mstProductType;
    }
}

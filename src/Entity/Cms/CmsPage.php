<?php

namespace App\Entity\Cms;

use App\Entity\Product\PrdProduct;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cms\CmsPageRepository")
 * @ORM\Table("cmspage")
 */
class CmsPage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pageName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pageTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pageSlugName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pageCanonicalUrl;

    /**
     * @ORM\Column(type="integer")
     */
    private $parentId;

    /**
     * @ORM\ManyToOne(targetEntity=PrdProduct::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $prdProduct;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $metaKeyword;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $focusKeyPhrase;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $keyPhraseSynonyms;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $seoSchema;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ogDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ogImagePath;

    /**
     * @ORM\Column(type="string", length=48, nullable=true)
     */
    private $pageRoute;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=CmsPageContent::class, cascade={"persist"}, mappedBy="cmsPage")
     */
    private $cmsPageContent;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $ogImageWidth;

    public function __construct()
    {
        $this->cmsPageContent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageName(): ?string
    {
        return $this->pageName;
    }

    public function setPageName(string $pageName): self
    {
        $this->pageName = $pageName;

        return $this;
    }

    public function getPageTitle(): ?string
    {
        return $this->pageTitle;
    }

    public function setPageTitle(string $pageTitle): self
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    public function getPageSlugName(): ?string
    {
        return $this->pageSlugName;
    }

    public function setPageSlugName(string $pageSlugName): self
    {
        $this->pageSlugName = $pageSlugName;
        return $this;
    }

    public function getPageCanonicalUrl(): ?string
    {
        return $this->pageCanonicalUrl;
    }

    public function setPageCanonicalUrl(string $pageCanonicalUrl): self
    {
        $this->pageCanonicalUrl = $pageCanonicalUrl;
        return $this;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): self
    {
        $this->parentId = $parentId;

        return $this;
    }

    public function getPrdProduct(): ?PrdProduct
    {
        return $this->prdProduct;
    }

    public function setPrdProduct(?PrdProduct $prdProduct): self
    {
        $this->prdProduct = $prdProduct;
        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(?string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;
        return $this;
    }

    public function getMetaKeyword(): ?string
    {
        return $this->metaKeyword;
    }

    public function setMetaKeyword(?string $metaKeyword): self
    {
        $this->metaKeyword = $metaKeyword;
        return $this;
    }

    public function getFocusKeyPhrase(): ?string
    {
        return $this->focusKeyPhrase;
    }

    public function setFocusKeyPhrase(?string $focusKeyPhrase): self
    {
        $this->focusKeyPhrase = $focusKeyPhrase;
        return $this;
    }

    public function getKeyPhraseSynonyms(): ?string
    {
        return $this->keyPhraseSynonyms;
    }

    public function setKeyPhraseSynonyms(?string $keyPhraseSynonyms): self
    {
        $this->keyPhraseSynonyms = $keyPhraseSynonyms;
        return $this;
    }

    public function getSeoSchema(): ?string
    {
        return $this->seoSchema;
    }

    public function setSeoSchema(?string $secSchema): self
    {
        $this->seoSchema = $secSchema;

        return $this;
    }

    public function getOgTitle(): ?string
    {
        return $this->ogTitle;
    }

    public function setOgTitle(?string $ogTitle): self
    {
        $this->ogTitle = $ogTitle;
        return $this;
    }

    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }

    public function setOgDescription(?string $ogDescription): self
    {
        $this->ogDescription = $ogDescription;
        return $this;
    }

    public function getOgType(): ?string
    {
        return $this->ogType;
    }

    public function setOgType(?string $ogType): self
    {
        $this->ogType = $ogType;
        return $this;
    }

    public function getOgImage(): ?string
    {
        return $this->ogImage;
    }

    public function setOgImage(string $ogImage): self
    {
        $this->ogImage = $ogImage;
        return $this;
    }

    public function getOgImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getOgImage();
    }

    public function setOgImagePath(string $ogImagePath): self
    {
        $this->ogImagePath = $ogImagePath;
        return $this;
    }

    public function getPageRoute(): ?string
    {
        return $this->pageRoute;
    }

    public function setPageRoute(?string $pageRoute): self
    {
        $this->pageRoute = $pageRoute;
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
        return $this->pageName;
    }

    /**
     * @return Collection|CmsPageContent[]
     */
    public function getCmsPageContent(): Collection
    {
        return $this->cmsPageContent;
    }

    public function addCmsPageContent(CmsPageContent $cmsPageContent): self
    {
        if (!$this->cmsPageContent->contains($cmsPageContent)) {
            $this->cmsPageContent[] = $cmsPageContent;
            $cmsPageContent->setCmsPage($this);
        }

        return $this;
    }

    public function removeCmsPageContent(CmsPageContent $cmsPageContent): self
    {
        if ($this->cmsPageContent->contains($cmsPageContent)) {
            $this->cmsPageContent->removeElement($cmsPageContent);
            // set the owning side to null (unless already changed)
            if ($cmsPageContent->getCmsPage() === $this) {
                $cmsPageContent->setCmsPage(null);
            }
        }

        return $this;
    }

    public function getOgImageWidth(): ?string
    {
        return $this->ogImageWidth;
    }

    public function setOgImageWidth(?string $ogImageWidth): self
    {
        $this->ogImageWidth = $ogImageWidth;
        return $this;
    }

}

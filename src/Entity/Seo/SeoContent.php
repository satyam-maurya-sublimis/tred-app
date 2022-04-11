<?php

namespace App\Entity\Seo;

use App\Entity\Cms\CmsArticle;
use App\Entity\Cms\CmsPage;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubCategory;
use App\Entity\Product\PrdLiner;
use App\Entity\Product\PrdTour;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Repository\Seo\SeoContentRepository;
use App\Service\FileUploaderHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeoContentRepository::class)
 * @ORM\Table("seocontent")
 */
class SeoContent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $canonicalUrl;

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
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $ogImageWidth;

    /**
     * @ORM\OneToOne(targetEntity=TrnProjectRoomConfiguration::class, mappedBy="seoContent", cascade={"persist", "remove"})
     */
    private $trnProjectRoomConfiguration;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function setCanonicalUrl(string $canonicalUrl): self
    {
        $this->canonicalUrl = $canonicalUrl;
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

    public function getOgImageWidth(): ?string
    {
        return $this->ogImageWidth;
    }

    public function setOgImageWidth(?string $ogImageWidth): self
    {
        $this->ogImageWidth = $ogImageWidth;

        return $this;
    }

    public function getTrnProjectRoomConfiguration(): ?TrnProjectRoomConfiguration
    {
        return $this->trnProjectRoomConfiguration;
    }

    public function setTrnProjectRoomConfiguration(?TrnProjectRoomConfiguration $trnProjectRoomConfiguration): self
    {
        // unset the owning side of the relation if necessary
        if ($trnProjectRoomConfiguration === null && $this->trnProjectRoomConfiguration !== null) {
            $this->trnProjectRoomConfiguration->setSeoContent(null);
        }

        // set the owning side of the relation if necessary
        if ($trnProjectRoomConfiguration !== null && $trnProjectRoomConfiguration->getSeoContent() !== $this) {
            $trnProjectRoomConfiguration->setSeoContent($this);
        }

        $this->trnProjectRoomConfiguration = $trnProjectRoomConfiguration;

        return $this;
    }

}

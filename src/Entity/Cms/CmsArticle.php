<?php

namespace App\Entity\Cms;

use App\Entity\SystemApp\AppUser;
use App\Service\FileUploaderHelper;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Cms\CmsArticleRepository")
 * @ORM\Table("cmsarticle")
 * @UniqueEntity(fields={"articleIntroImageSetName"}, message="The value is already in the system")
 */
class CmsArticle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $appUser;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articleFor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articleTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $articleSlugName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articleCanonicalUrl;

    /**
     * @ORM\Column(type="text")
     */
    private $articleIntro;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $introMediaType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articleIntroImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articleIntroImageSetName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articleIntroImageAlt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $articleIntroImageTitle;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $articleIntroImagePath;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $articleIntroVideo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $articleIntroVideoPath;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $articleLikeCount;

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
     * @ORM\Column(type="datetime")
     */
    private $articleCreateDateTime;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $articleCreatedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $articleUpdateDateTime;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class)
     */
    private $articleUpdatedBy;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private $locationLatitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=8, nullable=true)
     */
    private $locationLongitude;

     /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=CmsArticleComment::class, mappedBy="cmsArticle")
     */
    private $cmsArticleComment;

    /**
     * @ORM\OneToMany(targetEntity=CmsArticleContent::class, mappedBy="cmsArticle", cascade={"persist","remove"})
     */
    private $cmsArticleContent;

    public function __construct()
    {
        $this->cmsArticleComment = new ArrayCollection();
        $this->cmsArticleContent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(?AppUser $appUser): self
    {
        $this->appUser = $appUser;
        return $this;
    }

    public function getArticleFor(): ?string
    {
        return $this->articleFor;
    }

    public function setArticleFor(string $articleFor): self
    {
        $this->articleFor = $articleFor;

        return $this;
    }

    public function getArticleTitle(): ?string
    {
        return $this->articleTitle;
    }

    public function setArticleTitle(string $articleTitle): self
    {
        $this->articleTitle = $articleTitle;
        return $this;
    }

    public function getArticleSlugName(): ?string
    {
        return $this->articleSlugName;
    }

    public function setArticleSlugName(string $articleSlugName): self
    {
        $this->articleSlugName = $articleSlugName;
        return $this;
    }

    public function getArticleCanonicalUrl(): ?string
    {
        return $this->articleCanonicalUrl;
    }

    public function setArticleCanonicalUrl(string $articleCanonicalUrl): self
    {
        $this->articleCanonicalUrl = $articleCanonicalUrl;
        return $this;
    }

    public function getArticleIntro(): ?string
    {
        return $this->articleIntro;
    }

    public function setArticleIntro(?string $articleIntro): self
    {
        $this->articleIntro = $articleIntro;
        return $this;
    }

    public function getIntroMediaType(): ?string
    {
        return $this->introMediaType;
    }

    public function setIntroMediaType(string $introMediaType): self
    {
        $this->introMediaType = $introMediaType;

        return $this;
    }

    public function getArticleIntroImage(): ?string
    {
        return $this->articleIntroImage;
    }

    public function setArticleIntroImage(string $articleIntroImage): self
    {
        $this->articleIntroImage = $articleIntroImage;

        return $this;
    }

    public function getArticleIntroImageSetName(): ?string
    {
        return $this->articleIntroImageSetName;
    }

    public function setArticleIntroImageSetName(string $articleIntroImageSetName): self
    {
        $this->articleIntroImageSetName = $articleIntroImageSetName;

        return $this;
    }

    public function getArticleIntroImageAlt(): ?string
    {
        return $this->articleIntroImageAlt;
    }

    public function setArticleIntroImageAlt(string $articleIntroImageAlt): self
    {
        $this->articleIntroImageAlt = $articleIntroImageAlt;

        return $this;
    }

    public function getArticleIntroImageTitle(): ?string
    {
        return $this->articleIntroImageTitle;
    }

    public function setArticleIntroImageTitle(string $articleIntroImageTitle): self
    {
        $this->articleIntroImageTitle = $articleIntroImageTitle;

        return $this;
    }

    public function getArticleIntroImagePath(): ?string
    {
        return FileUploaderHelper::PUBLIC_FILE.'/'.$this->getArticleIntroImage();
    }

    public function setArticleIntroImagePath(string $articleIntroImagePath): self
    {
        $this->articleIntroImagePath = $articleIntroImagePath;

        return $this;
    }

    public function getArticleIntroVideo(): ?string
    {
        return $this->articleIntroVideo;
    }

    public function setArticleIntroVideo(?string $articleIntroVideo): self
    {
        $this->articleIntroVideo = $articleIntroVideo;
        return $this;
    }

    public function getArticleIntroVideoPath(): ?string
    {
        return $this->articleIntroVideoPath;
    }

    public function setArticleIntroVideoPath(?string $articleIntroVideoPath): self
    {
        $this->articleIntroVideoPath = $articleIntroVideoPath;
        return $this;
    }

    public function getArticleLikeCount(): ?int
    {
        return $this->articleLikeCount;
    }

    public function setArticleLikeCount(?int $articleLikeCount): self
    {
        $this->articleLikeCount = $articleLikeCount;
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


    public function getArticleCreateDateTime(): ?DateTimeInterface
    {
        return $this->articleCreateDateTime;
    }

    public function setArticleCreateDateTime(DateTimeInterface $articleCreateDateTime): self
    {
        $this->articleCreateDateTime = $articleCreateDateTime;

        return $this;
    }

    public function getArticleCreatedBy(): ?AppUser
    {
        return $this->articleCreatedBy;
    }

    public function setArticleCreatedBy(?AppUser $articleCreatedBy): self
    {
        $this->articleCreatedBy = $articleCreatedBy;

        return $this;
    }

    public function getArticleUpdateDateTime(): ?DateTimeInterface
    {
        return $this->articleUpdateDateTime;
    }

    public function setArticleUpdateDateTime(?DateTimeInterface $articleUpdateDateTime): self
    {
        $this->articleUpdateDateTime = $articleUpdateDateTime;

        return $this;
    }

    public function getArticleUpdatedBy(): ?AppUser
    {
        return $this->articleUpdatedBy;
    }

    public function setArticleUpdatedBy(?AppUser $articleUpdatedBy): self
    {
        $this->articleUpdatedBy = $articleUpdatedBy;

        return $this;
    }

    public function getLocationLatitude(): ?string
    {
        return $this->locationLatitude;
    }

    public function setLocationLatitude(?string $locationLatitude): self
    {
        $this->locationLatitude = $locationLatitude;

        return $this;
    }

    public function getLocationLongitude(): ?string
    {
        return $this->locationLongitude;
    }

    public function setLocationLongitude(?string $locationLongitude): self
    {
        $this->locationLongitude = $locationLongitude;

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
        return $this->articleTitle;
    }

    /**
     * @return Collection|CmsArticleComment[]
     */
    public function getCmsArticleComment(): Collection
    {
        return $this->cmsArticleComment;
    }

    public function addCmsArticleComment(CmsArticleComment $cmsArticleComment): self
    {
        if (!$this->cmsArticleComment->contains($cmsArticleComment)) {
            $this->cmsArticleComment[] = $cmsArticleComment;
            $cmsArticleComment->setCmsArticle($this);
        }

        return $this;
    }

    public function removeCmsArticleComment(CmsArticleComment $cmsArticleComment): self
    {
        if ($this->cmsArticleComment->contains($cmsArticleComment)) {
            $this->cmsArticleComment->removeElement($cmsArticleComment);
            // set the owning side to null (unless already changed)
            if ($cmsArticleComment->getCmsArticle() === $this) {
                $cmsArticleComment->setCmsArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CmsArticleContent[]
     */
    public function getCmsArticleContent(): Collection
    {
        return $this->cmsArticleContent;
    }

    public function addCmsArticleContent(CmsArticleContent $cmsArticleContent): self
    {
        if (!$this->cmsArticleContent->contains($cmsArticleContent)) {
            $this->cmsArticleContent[] = $cmsArticleContent;
            $cmsArticleContent->setCmsArticle($this);
        }

        return $this;
    }

    public function removeCmsArticleContent(CmsArticleContent $cmsArticleContent): self
    {
        if ($this->cmsArticleContent->contains($cmsArticleContent)) {
            $this->cmsArticleContent->removeElement($cmsArticleContent);
            // set the owning side to null (unless already changed)
            if ($cmsArticleContent->getCmsArticle() === $this) {
                $cmsArticleContent->setCmsArticle(null);
            }
        }

        return $this;
    }

}

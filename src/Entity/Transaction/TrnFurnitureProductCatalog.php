<?php

namespace App\Entity\Transaction;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstFurnitureFinish;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Product\PrdBrand;
use App\Entity\Product\PrdColor;
use App\Repository\Transaction\TrnFurnitureProductCatalogRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnFurnitureProductCatalogRepository::class)
 * @ORM\Table("trnfurnitureproductcatalog")
 */
class TrnFurnitureProductCatalog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class, inversedBy="trnFurnitureProductCatalog")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProductCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductType::class, inversedBy="trnFurnitureProductCatalog")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProductType;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductSubType::class, inversedBy="trnFurnitureProductCatalog")
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstProductSubType;

    /**
     * @ORM\ManyToOne(targetEntity=PrdBrand::class, inversedBy="trnFurnitureProductCatalog")
     */
    private $prdBrand;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $catalogName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $canonicalUrl;

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
     * @ORM\ManyToOne(targetEntity=MstCurrency::class)
     */
    private $mstCurrency;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $furniturePrice;

    /**
     * @ORM\ManyToMany(targetEntity=PrdColor::class, inversedBy="trnFurnitureProductCatalogs")
     */
    private $prdColor;

    /**
     * @ORM\ManyToOne(targetEntity=MstFurnitureFinish::class, inversedBy="trnFurnitureProductCatalogs")
     */
    private $mstFurnitureFinish;

    /**
     * @ORM\OneToMany(targetEntity=TrnFurnitureProductCatalogDimensionMedia::class, mappedBy="trnFurnitureProductCatalog", cascade={"persist","remove"})
     */
    private $trnFurnitureProductCatalogDimensionMedia;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFreeHomeDelivery;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFreeInstallation;

    public function __construct()
    {
        $this->prdColor = new ArrayCollection();
        $this->trnFurnitureProductCatalogDimensionMedia = new ArrayCollection();
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

    public function getMstProductSubType(): ?MstProductSubType
    {
        return $this->mstProductSubType;
    }

    public function setMstProductSubType(?MstProductSubType $mstProductSubType): self
    {
        $this->mstProductSubType = $mstProductSubType;

        return $this;
    }

    public function getPrdBrand(): ?PrdBrand
    {
        return $this->prdBrand;
    }

    public function setPrdBrand(?PrdBrand $prdBrand): self
    {
        $this->prdBrand = $prdBrand;

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

    public function getCatalogName(): ?string
    {
        return $this->catalogName;
    }

    public function setCatalogName(string $catalogName): self
    {
        $this->catalogName = $catalogName;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

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

    public function getMstCurrency(): ?MstCurrency
    {
        return $this->mstCurrency;
    }

    public function setMstCurrency(?MstCurrency $mstCurrency): self
    {
        $this->mstCurrency = $mstCurrency;

        return $this;
    }

    public function getFurniturePrice(): ?string
    {
        return $this->furniturePrice;
    }

    public function setFurniturePrice(string $furniturePrice): self
    {
        $this->furniturePrice = $furniturePrice;

        return $this;
    }

    /**
     * @return Collection|PrdColor[]
     */
    public function getPrdColor(): Collection
    {
        return $this->prdColor;
    }

    public function addPrdColor(PrdColor $prdColor): self
    {
        if (!$this->prdColor->contains($prdColor)) {
            $this->prdColor[] = $prdColor;
        }

        return $this;
    }

    public function removePrdColor(PrdColor $prdColor): self
    {
        $this->prdColor->removeElement($prdColor);

        return $this;
    }

    public function getMstFurnitureFinish(): ?MstFurnitureFinish
    {
        return $this->mstFurnitureFinish;
    }

    public function setMstFurnitureFinish(?MstFurnitureFinish $mstFurnitureFinish): self
    {
        $this->mstFurnitureFinish = $mstFurnitureFinish;

        return $this;
    }

    /**
     * @return Collection|TrnFurnitureProductCatalogDimensionMedia[]
     */
    public function getTrnFurnitureProductCatalogDimensionMedia(): Collection
    {
        return $this->trnFurnitureProductCatalogDimensionMedia;
    }

    public function addTrnFurnitureProductCatalogDimensionMedium(TrnFurnitureProductCatalogDimensionMedia $trnFurnitureProductCatalogDimensionMedium): self
    {
        if (!$this->trnFurnitureProductCatalogDimensionMedia->contains($trnFurnitureProductCatalogDimensionMedium)) {
            $this->trnFurnitureProductCatalogDimensionMedia[] = $trnFurnitureProductCatalogDimensionMedium;
            $trnFurnitureProductCatalogDimensionMedium->setTrnFurnitureProductCatalog($this);
        }

        return $this;
    }

    public function removeTrnFurnitureProductCatalogDimensionMedium(TrnFurnitureProductCatalogDimensionMedia $trnFurnitureProductCatalogDimensionMedium): self
    {
        if ($this->trnFurnitureProductCatalogDimensionMedia->removeElement($trnFurnitureProductCatalogDimensionMedium)) {
            // set the owning side to null (unless already changed)
            if ($trnFurnitureProductCatalogDimensionMedium->getTrnFurnitureProductCatalog() === $this) {
                $trnFurnitureProductCatalogDimensionMedium->setTrnFurnitureProductCatalog(null);
            }
        }

        return $this;
    }

    public function getIsFreeHomeDelivery(): ?bool
    {
        return $this->isFreeHomeDelivery;
    }

    public function setIsFreeHomeDelivery(bool $isFreeHomeDelivery): self
    {
        $this->isFreeHomeDelivery = $isFreeHomeDelivery;

        return $this;
    }


    public function getIsFreeInstallation(): ?bool
    {
        return $this->isFreeInstallation;
    }

    public function setIsFreeInstallation(bool $isFreeInstallation): self
    {
        $this->isFreeInstallation = $isFreeInstallation;

        return $this;
    }

}

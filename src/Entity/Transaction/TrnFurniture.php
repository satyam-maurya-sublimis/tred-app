<?php

namespace App\Entity\Transaction;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstFurnitureCategory;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Media\MdaFurniture;
use App\Entity\Product\PrdBrand;
use App\Entity\Product\PrdOption;
use App\Repository\Transaction\TrnFurnitureRepository;
use App\Service\FileUploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrnFurnitureRepository::class)
 * @ORM\Table("trnfurniture")
 */
class TrnFurniture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductCategory::class, inversedBy="trnFurniture")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProductCategory;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductType::class, inversedBy="trnFurniture")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mstProductType;

    /**
     * @ORM\ManyToOne(targetEntity=MstProductSubType::class, inversedBy="trnFurniture")
     * @ORM\JoinColumn(nullable=true)
     */
    private $mstProductSubType;

    /**
     * @ORM\ManyToOne(targetEntity=PrdBrand::class, inversedBy="trnFurniture")
     */
    private $prdBrand;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $furnitureName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\ManyToOne(targetEntity=MstFurnitureCategory::class)
     */
    private $mstFurnitureCategory;

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
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $furniturePrice;

    /**
     * @ORM\OneToMany(targetEntity=MdaFurniture::class, mappedBy="trnFurniture")
     */
    private $mdaFurniture;


    public function __construct()
    {
        $this->mdaFurniture = new ArrayCollection();
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

    public function getFurnitureName(): ?string
    {
        return $this->furnitureName;
    }

    public function setFurnitureName(?string $furnitureName): self
    {
        $this->furnitureName = $furnitureName;

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

    public function getMstFurnitureCategory(): ?MstFurnitureCategory
    {
        return $this->mstFurnitureCategory;
    }

    public function setMstFurnitureCategory(?MstFurnitureCategory $mstFurnitureCategory): self
    {
        $this->mstFurnitureCategory = $mstFurnitureCategory;

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

    public function setFurniturePrice(?string $furniturePrice): self
    {
        $this->furniturePrice = $furniturePrice;

        return $this;
    }

    /**
     * @return Collection|MdaFurniture[]
     */
    public function getMdaFurniture(): Collection
    {
        return $this->mdaFurniture;
    }

    public function addMdaFurniture(MdaFurniture $mdaFurniture): self
    {
        if (!$this->mdaFurniture->contains($mdaFurniture)) {
            $this->mdaFurniture[] = $mdaFurniture;
            $mdaFurniture->setTrnFurniture($this);
        }

        return $this;
    }

    public function removeMdaFurniture(MdaFurniture $mdaFurniture): self
    {
        if ($this->mdaFurniture->removeElement($mdaFurniture)) {
            // set the owning side to null (unless already changed)
            if ($mdaFurniture->getTrnFurniture() === $this) {
                $mdaFurniture->setTrnFurniture(null);
            }
        }

        return $this;
    }
}

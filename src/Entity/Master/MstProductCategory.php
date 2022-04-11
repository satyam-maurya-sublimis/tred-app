<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Cms\CmsBanner;
use App\Entity\Cms\CmsPageContent;
use App\Entity\Transaction\TrnFurniture;
use App\Repository\Master\MstProductCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 * @ORM\Entity(repositoryClass=MstProductCategoryRepository::class)
 * @ORM\Table("mstproductcategory")
 * @UniqueEntity(fields={"productCategory"}, message="The value is already in the system")
 */
class MstProductCategory
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
    private $productCategory;

    /**
     * @ORM\Column(type="guid")
     */
    private $rowId;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read"})
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity=MstProductType::class, mappedBy="mstProductCategory")
     */
    private $mstProductTypes;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPortal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $navClass;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $navRouting;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productCategorySlugName;

    /**
     * @ORM\OneToMany(targetEntity=CmsPageContent::class, mappedBy="mstProductCategory", cascade={"persist","remove"})
     */
    private $cmsPageContent;

    private $cmsBanner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productCategoryFormType;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $productCategoryDescription;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isService;

    /**
     * @ORM\OneToMany(targetEntity=TrnFurniture::class, mappedBy="mstProductCategory")
     */
    private $trnFurniture;


    public function __construct()
    {
        $this->mstProductTypes = new ArrayCollection();
        $this->cmsPageContent = new ArrayCollection();
        $this->trnFurniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductCategory(): ?string
    {
        return $this->productCategory;
    }

    public function setProductCategory(string $productCategory): self
    {
        $this->productCategory = $productCategory;

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
        return $this->productCategory;
    }

    /**
     * @return Collection|MstProductType[]
     */
    public function getMstProductTypes(): Collection
    {
        return $this->mstProductTypes;
    }

    public function addMstProductType(MstProductType $mstProductType): self
    {
        if (!$this->mstProductTypes->contains($mstProductType)) {
            $this->mstProductTypes[] = $mstProductType;
            $mstProductType->setMstProductCategory($this);
        }

        return $this;
    }

    public function removeMstProductType(MstProductType $mstProductType): self
    {
        if ($this->mstProductTypes->removeElement($mstProductType)) {
            // set the owning side to null (unless already changed)
            if ($mstProductType->getMstProductCategory() === $this) {
                $mstProductType->setMstProductCategory(null);
            }
        }

        return $this;
    }

    public function getIsPortal(): ?bool
    {
        return $this->isPortal;
    }

    public function setIsPortal(?bool $isPortal): self
    {
        $this->isPortal = $isPortal;

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

    public function getNavClass(): ?string
    {
        return $this->navClass;
    }

    public function setNavClass(?string $navClass): self
    {
        $this->navClass = $navClass;

        return $this;
    }

    public function getNavRouting(): ?string
    {
        return $this->navRouting;
    }

    public function setNavRouting(?string $navRouting): self
    {
        $this->navRouting = $navRouting;

        return $this;
    }

    public function getProductCategorySlugName(): ?string
    {
        return $this->productCategorySlugName;
    }

    public function setProductCategorySlugName(string $productCategorySlugName): self
    {
        $this->productCategorySlugName = $productCategorySlugName;
        return $this;
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
            $cmsPageContent->setMstProductCategory($this);
        }

        return $this;
    }

    public function removeCmsPageContent(CmsPageContent $cmsPageContent): self
    {
        if ($this->cmsPageContent->removeElement($cmsPageContent)) {
            // set the owning side to null (unless already changed)
            if ($cmsPageContent->getMstProductCategory() === $this) {
                $cmsPageContent->setMstProductCategory(null);
            }
        }

        return $this;
    }

    public function getProductCategoryFormType(): ?string
    {
        return $this->productCategoryFormType;
    }

    public function setProductCategoryFormType(?string $productCategoryFormType): self
    {
        $this->productCategoryFormType = $productCategoryFormType;

        return $this;
    }

    public function getProductCategoryDescription(): ?string
    {
        return $this->productCategoryDescription;
    }

    public function setProductCategoryDescription(?string $productCategoryDescription): self
    {
        $this->productCategoryDescription = $productCategoryDescription;

        return $this;
    }

    public function getIsService(): ?bool
    {
        return $this->isService;
    }

    public function setIsService(?bool $isService): self
    {
        $this->isService = $isService;

        return $this;
    }

    /**
     * @return Collection|TrnFurniture[]
     */
    public function getTrnFurniture(): Collection
    {
        return $this->trnFurniture;
    }

    public function addTrnFurniture(TrnFurniture $trnFurniture): self
    {
        if (!$this->trnFurniture->contains($trnFurniture)) {
            $this->trnFurniture[] = $trnFurniture;
            $trnFurniture->setMstProductCategory($this);
        }

        return $this;
    }

    public function removeTrnFurniture(TrnFurniture $trnFurniture): self
    {
        if ($this->trnFurniture->removeElement($trnFurniture)) {
            // set the owning side to null (unless already changed)
            if ($trnFurniture->getMstProductCategory() === $this) {
                $trnFurniture->setMstProductCategory(null);
            }
        }

        return $this;
    }

}

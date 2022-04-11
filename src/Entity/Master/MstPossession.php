<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Transaction\TrnProject;
use App\Repository\Master\MstPossessionRepository;
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
 * @ORM\Entity(repositoryClass=MstPossessionRepository::class)
 * @ORM\Table("mstpossession")
 * @UniqueEntity(fields={"possession"}, message="The value is already in the system")
 */
class MstPossession
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Groups({"read"})
     */
    private $possession;

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
     * @ORM\OneToMany(targetEntity=TrnProject::class, mappedBy="mstPossession")
     */
    private $trnProjects;

    public function __construct()
    {
        $this->trnProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPossession(): ?string
    {
        return $this->possession;
    }

    public function setPossession(string $possession): self
    {
        $this->possession = $possession;

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
     * @return Collection|TrnProject[]
     */
    public function getTrnProjects(): Collection
    {
        return $this->trnProjects;
    }

    public function addTrnProject(TrnProject $trnProject): self
    {
        if (!$this->trnProjects->contains($trnProject)) {
            $this->trnProjects[] = $trnProject;
            $trnProject->setMstPossession($this);
        }

        return $this;
    }

    public function removeTrnProject(TrnProject $trnProject): self
    {
        if ($this->trnProjects->removeElement($trnProject)) {
            // set the owning side to null (unless already changed)
            if ($trnProject->getMstPossession() === $this) {
                $trnProject->setMstPossession(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->possession;
    }
}

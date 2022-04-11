<?php

namespace App\Entity\Master;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
 * @ORM\Entity(repositoryClass="App\Repository\Master\MstStateRepository")
 * @ORM\Table("mststate")
 */
class MstState
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"read"})
     */
    private $fipsCode;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"read"})
     */
    private $iso2;

    /**
     * @ORM\ManyToOne(targetEntity=MstCountry::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $mstCountry;

    public function __construct()
    {
        $this->mstcity = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFipsCode():string
    {
        return $this->fipsCode;
    }

    /**
     * @param mixed $fipsCode
     */
    public function setFipsCode(?string $fipsCode): self
    {
        $this->fipsCode = $fipsCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIso2():string
    {
        return $this->iso2;
    }

    /**
     * @param mixed $iso2
     */
    public function setIso2(?string $iso2): string
    {
        $this->iso2 = $iso2;
        return $this;
    }


    public function getMstCountry(): ?MstCountry
    {
        return $this->mstCountry;
    }

    public function setMstCountry(?MstCountry $mstCountry): self
    {
        $this->mstCountry = $mstCountry;
        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->state;
    }
}

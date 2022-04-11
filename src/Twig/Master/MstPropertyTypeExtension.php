<?php

namespace App\Twig\Master;

use App\Entity\Master\MstPropertyType;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MstPropertyTypeExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_property_type', [$this, 'getPropertyType']),
        ];
    }

    public function getPropertyType()
    {
        return $this->em->getRepository(MstPropertyType::class)->findBy(['isActive'=> true]);
    }
}

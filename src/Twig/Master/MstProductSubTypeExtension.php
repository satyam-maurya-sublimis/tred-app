<?php

namespace App\Twig\Master;

use App\Entity\Master\MstProductSubType;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MstProductSubTypeExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_product_sub_type', [$this, 'getProductSubType']),
        ];
    }

    public function getProductSubType($productTypeId)
    {
        return $this->em->getRepository(MstProductSubType::class)->findBy(['mstProductType'=> $productTypeId]);
    }
}

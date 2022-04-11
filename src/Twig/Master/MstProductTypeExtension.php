<?php

namespace App\Twig\Master;

use App\Entity\Master\MstProductType;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MstProductTypeExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_product_type', [$this, 'getProductType']),
            new TwigFunction('get_product_type_by_slugname', [$this, 'getProductTypeBySlugname']),
            new TwigFunction('get_furniture_product_type', [$this, 'getFurnitureProductType']),
        ];
    }

    public function getProductType($customerType = null)
    {
        return $this->em->getRepository(MstProductType::class)->getProductType($customerType);
    }
    public function getProductTypeBySlugname($slugName)
    {
        return $this->em->getRepository(MstProductType::class)->findOneBy(['productTypeSlugName'=>$slugName,'isActive'=>1]);
    }
    public function getFurnitureProductType($slugName)
    {
        return $this->em->getRepository(MstProductType::class)->getFurnitureProductType($slugName);
    }
}

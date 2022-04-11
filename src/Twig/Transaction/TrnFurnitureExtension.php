<?php

namespace App\Twig\Transaction;

use App\Entity\Media\MdaFurniture;
use App\Entity\Transaction\TrnFurniture;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TrnFurnitureExtension extends AbstractExtension
{
    private $em;
    private $commonHelper;
    private $session;
    private $params;

    public function __construct(EntityManagerInterface $em, CommonHelper $commonHelper, RequestStack $session, ParameterBagInterface $params)
    {
        $this->em = $em;
        $this->commonHelper = $commonHelper;
        $this->session = $session;
        $this->params = $params;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_furniture_by_product_type_slugName', [$this, 'getFurnitureProductTypeSlugName']),
            new TwigFunction('get_furniture_product_category', [$this, 'getFurnitureProductCategory']),
            new TwigFunction('get_product_furniture_by_category_id', [$this, 'getProductFurnitureByCategoryId']),
            new TwigFunction('get_furniture_by_id', [$this, 'getFurnitureById']),
        ];
    }

    public function getFurnitureProductSubType($slugName)
    {
        return $this->em->getRepository(TrnFurniture::class)->getFurnitureProductSubType($slugName);
    }

    public function getFurnitureProductCategory($id)
    {
        return $this->em->getRepository(MdaFurniture::class)->findOneBy(['isActive'=>1,'trnFurniture'=>$id]);
    }

    public function getProductFurnitureByCategoryId($id)
    {
        return $this->em->getRepository(TrnFurniture::class)->findBy(['isActive'=>1,'mstFurnitureCategory'=>$id]);
    }
    public function getFurnitureById($id)
    {
        return $this->em->getRepository(TrnFurniture::class)->findOneBy(['isActive'=>1,'id'=>$id]);
    }

}

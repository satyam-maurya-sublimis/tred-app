<?php

namespace App\Twig\Media;

use App\Entity\Media\MdaFurniture;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MdaFurnitureExtension extends AbstractExtension
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
            new TwigFunction('get_media_by_catalog_id', [$this, 'getMediaByCatalogId']),
            new TwigFunction('get_media_by_furniture_id', [$this, 'getMediaByFurnitureId']),
        ];
    }

    public function getMediaByCatalogId($catalogId)
    {
        return $this->em->getRepository(MdaFurniture::class)->findBy(['isActive'=>1,'trnFurnitureProductCatalog'=>$catalogId],['position'=>'ASC']);
    }
    public function getMediaByFurnitureId($furnitureId)
    {
        return $this->em->getRepository(MdaFurniture::class)->findBy(['isActive'=>1,'trnFurniture'=>$furnitureId]);
    }
}

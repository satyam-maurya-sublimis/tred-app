<?php

namespace App\Twig\Master;

use App\Entity\Master\MstVendorType;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MstVendorTypeExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_vendor_type_by_id', [$this, 'getVendorTypeById']),
        ];
    }

    public function getVendorTypeById($id)
    {
        return $this->em->getRepository(MstVendorType::class)->find($id);
    }

}

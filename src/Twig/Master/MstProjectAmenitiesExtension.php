<?php

namespace App\Twig\Master;

use App\Entity\Master\MstProjectAmenities;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MstProjectAmenitiesExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_amenities_by_id', [$this, 'getAmenitiesById']),
        ];
    }

    public function getAmenitiesById($id)
    {
        return $this->em->getRepository(MstProjectAmenities::class)->find($id);
    }

}

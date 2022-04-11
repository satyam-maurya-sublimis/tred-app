<?php

namespace App\Twig\Master;

use App\Entity\Master\MstPossession;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MstPossessionExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_possession', [$this, 'getPossession']),
        ];
    }

    public function getPossession($id)
    {
        return $this->em->getRepository(MstPossession::class)->findOneBy(['isActive'=> true,'id'=>$id]);
    }
}

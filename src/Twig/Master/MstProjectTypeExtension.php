<?php

namespace App\Twig\Master;

use App\Entity\Master\MstProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MstProjectTypeExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_project_type_by_id', [$this, 'getProjectTypeById']),
            new TwigFunction('get_project_type', [$this, 'getProjectType']),
        ];
    }

    public function getProjectTypeById($id)
    {
        return $this->em->getRepository(MstProjectType::class)->find($id);
    }
    public function getProjectType()
    {
        return $this->em->getRepository(MstProjectType::class)->getProjectType();
    }

}

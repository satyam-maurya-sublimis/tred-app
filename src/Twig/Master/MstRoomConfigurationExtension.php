<?php

namespace App\Twig\Master;

use App\Entity\Master\MstRoomConfiguration;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MstRoomConfigurationExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_room_configuration_by_id', [$this, 'getRoomConfigurationById']),
            new TwigFunction('get_room_configuration', [$this, 'getRoomConfiguration']),
        ];
    }

    public function getRoomConfigurationById($id)
    {
        return $this->em->getRepository(MstRoomConfiguration::class)->find($id);
    }
    public function getRoomConfiguration()
    {
        return $this->em->getRepository(MstRoomConfiguration::class)->findBy(['isActive'=>1]);
    }

}

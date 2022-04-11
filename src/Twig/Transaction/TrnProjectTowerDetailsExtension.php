<?php

namespace App\Twig\Transaction;

use App\Entity\Transaction\TrnProjectTowerDetails;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TrnProjectTowerDetailsExtension extends AbstractExtension
{
    private $em;
    private $commonHelper;
    public function __construct(EntityManagerInterface $em, CommonHelper $commonHelper)
    {
        $this->em = $em;
        $this->commonHelper = $commonHelper;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_tower_details', [$this, 'getTowerDetails']),
        ];
    }

    public function getTowerDetails($id = null)
    {
        $trnProjectTowerDetails = $this->em->getRepository(TrnProjectTowerDetails::class)->getTowerDetails($id);
        return $this->commonHelper->getProjectTowerData($trnProjectTowerDetails);
    }
}

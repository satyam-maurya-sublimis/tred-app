<?php

namespace App\Twig\Transaction;

use App\Entity\Transaction\TrnTopVendorPartners;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TrnTopVendorPartnersExtension extends AbstractExtension
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
            new TwigFunction('get_top_agents_cities', [$this, 'getTopAgentCities']),
        ];
    }

    public function getTopAgentCities()
    {
        return $this->em->getRepository(TrnTopVendorPartners::class)->getTopAgentCities();
    }

}

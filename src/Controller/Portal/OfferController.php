<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsLandingPage;
use App\Service\CommonHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/offers", name="portal_offer_")
 */
class OfferController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $cmsLandingPage = $this->managerRegistry->getRepository(CmsLandingPage::class)->findOneBy(['isActive'=>1,"cmsLandingPageSlugName"=>'offers']);
        return $this->render('portal/page/offer/index.html.twig', [
            'cmsLandingPage'=> $cmsLandingPage
        ]);
    }
}

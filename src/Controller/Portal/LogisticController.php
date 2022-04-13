<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsLandingPage;
use App\Entity\Cms\CmsPage;
use App\Entity\Master\MstProductCategory;
use App\Service\CommonHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/logistics", name="portal_logistic_")
 */
class LogisticController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/", name="index", priority="10")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $mstProductCategory = $this->managerRegistry->getRepository(MstProductCategory::class)->findOneBy(['isActive'=>1,"productCategorySlugName"=>'logistics']);
        $slugName = $mstProductCategory->getProductCategorySlugName();
//        $cmsLandingPage = $this->managerRegistry->getRepository(CmsLandingPage::class)->findOneBy(['isActive'=>1,"cmsLandingPageSlugName"=>$slugName]);
        $cmsPage = $this->managerRegistry->getRepository(CmsPage::class)->findOneBy(['isActive'=>1,"pageSlugName"=>$slugName]);
        return $this->render('portal/page/logistic/logistic.html.twig', [
//            'cmsLandingPage'=> $cmsLandingPage,
            'cmsPage'=> $cmsPage,
            'slugName'=>$slugName,
        ]);
    }
}

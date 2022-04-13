<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsLandingPage;
use App\Entity\Master\MstProductCategory;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/home-loans", name="portal_homeloan_")
 */
class HomeLoanController extends AbstractController
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
        $mstProductCategory = $this->managerRegistry->getRepository(MstProductCategory::class)->findOneBy(['isActive'=>1,"productCategorySlugName"=>'home-loans']);
        $slugName = $mstProductCategory->getProductCategorySlugName();
        $cmsLandingPage = $this->managerRegistry->getRepository(CmsLandingPage::class)->findOneBy(['isActive'=>1,"cmsLandingPageSlugName"=>$slugName]);
        return $this->render('portal/page/homeloan/index.html.twig', [
            'cmsLandingPage'=> $cmsLandingPage,
            'slugName'=>$slugName,
        ]);
    }
}

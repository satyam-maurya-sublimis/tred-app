<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsLandingPage;
use App\Entity\Cms\CmsPage;
use App\Entity\Master\MstProductCategory;
use App\Service\CommonHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/logistics", name="portal_logistic_")
 */
class LogisticController extends AbstractController
{
    /**
     * @Route("/", name="index", priority="10")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->findOneBy(['isActive'=>1,"productCategorySlugName"=>'logistics']);
        $slugName = $mstProductCategory->getProductCategorySlugName();
//        $cmsLandingPage = $this->getDoctrine()->getRepository(CmsLandingPage::class)->findOneBy(['isActive'=>1,"cmsLandingPageSlugName"=>$slugName]);
        $cmsPage = $this->getDoctrine()->getRepository(CmsPage::class)->findOneBy(['isActive'=>1,"pageSlugName"=>$slugName]);
        return $this->render('portal/page/logistic/logistic.html.twig', [
//            'cmsLandingPage'=> $cmsLandingPage,
            'cmsPage'=> $cmsPage,
            'slugName'=>$slugName,
        ]);
    }
}

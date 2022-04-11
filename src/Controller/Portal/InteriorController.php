<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsLandingPage;
use App\Entity\Cms\CmsPage;
use App\Entity\Form\FormEnquiry;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductType;
use App\Entity\Organization\OrgCompany;
use App\Form\Portal\FormEnquiryOneType;
use App\Form\Portal\FormEnquiryTwoType;
use App\Service\CommonHelper;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class InteriorController extends AbstractController
{
//    /**
//     * @Route("/", name="index")
//     * @param Request $request
//     * @return Response
//     */
//    public function index(Request $request): Response
//    {
//        return $this->render('portal/page/interior/index.html.twig', [
//        ]);
//    }
//    /**
//     * @Route("/{slugName}", name="internal")
//     * @param Request $request
//     * @return Response
//     */
//    public function internalPages(Request $request, CommonHelper $helper): Response
//    {
//        $slugName  = $request->get('slugName');
//        $cmsLandingPage = $this->getDoctrine()->getRepository(CmsLandingPage::class)->findOneBy(['isActive'=>1,"cmsLandingPageSlugName"=>$slugName]);
//        return $this->render('portal/page/interior/internal.html.twig', [
//            'cmsLandingPage'=> $cmsLandingPage
//        ]);
//    }

    /**
     * @Route("/interiors", name="portal_interior_designer")
     * @param Request $request
     * @return Response
     */
    public function designer(Request $request, Mailer $mailer): Response
    {
        $slugName  = 'interiors';
        $formEnquiry = new FormEnquiry();
        $formEnquiry->setEnquiryForm($slugName);
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
        $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->findOneBy(['productCategorySlugName'=>"interiors","isActive"=>1]);
        $cmsPage = $this->getDoctrine()->getRepository(CmsPage::class)->findOneBy(['isActive'=>1,"pageSlugName"=>$slugName]);
        $formEnquiry->setMstProductCategory($mstProductCategory);
        $mstProductType = $this->getDoctrine()->getRepository(MstProductType::class)->findOneBy(['productTypeSlugName'=>$slugName,"isActive"=>1]);
        $formEnquiry->setMstProductType($mstProductType);
        $form = $this->createForm(FormEnquiryTwoType::class, $formEnquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = explode(" ",$form->get("firstName")->getData());
            $formEnquiry->setMstLeadStatus($mstLeadStatus);
            $formEnquiry->setEnquiryFirstName($name[0]);
            $formEnquiry->setOrgCompany($orgCompany);
            $formEnquiry->setEnquiryCreateTime(new \DateTime());
            if (isset($name[1])){
                $formEnquiry->setEnquiryLastName($name[1]);
            }else{
                $formEnquiry->setEnquiryLastName("-");
            }
//            $formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formEnquiry);
            $entityManager->flush();
            $mailer->mailerFormEnquiry($formEnquiry);
            return $this->redirectToRoute('portal_interior_form_enquiry_success');
        }

        return $this->render('portal/page/interior/designers.html.twig', [
            'cmsPage'=> $cmsPage,
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/furnishing", name="portal_interior_furnishing")
     * @param Request $request
     * @return Response
     */
    public function furnishing(Request $request, Mailer $mailer): Response
    {
        $slugName  = 'furnishing';
        $cmsPage = $this->getDoctrine()->getRepository(CmsPage::class)->findOneBy(['isActive'=>1,"pageSlugName"=>$slugName]);
        $formEnquiry = new FormEnquiry();
        $formEnquiry->setEnquiryForm($slugName);
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
        $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->findOneBy(['productCategorySlugName'=>"interiors","isActive"=>1]);
        $cmsPage = $this->getDoctrine()->getRepository(CmsPage::class)->findOneBy(['isActive'=>1,"pageSlugName"=>$slugName]);
        $formEnquiry->setMstProductCategory($mstProductCategory);
        $mstProductType = $this->getDoctrine()->getRepository(MstProductType::class)->findOneBy(['productTypeSlugName'=>$slugName,"isActive"=>1]);
        $formEnquiry->setMstProductType($mstProductType);
        $form = $this->createForm(FormEnquiryOneType::class, $formEnquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = explode(" ",$form->get("firstName")->getData());
            $formEnquiry->setMstLeadStatus($mstLeadStatus);
            $formEnquiry->setEnquiryFirstName($name[0]);
            $formEnquiry->setOrgCompany($orgCompany);
            $formEnquiry->setEnquiryCreateTime(new \DateTime());
            if (isset($name[1])){
                $formEnquiry->setEnquiryLastName($name[1]);
            }else{
                $formEnquiry->setEnquiryLastName("-");
            }
//            $formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formEnquiry);
            $entityManager->flush();
            $mailer->mailerFormEnquiry($formEnquiry);
            return $this->redirectToRoute('portal_interior_form_enquiry_success');
        }

        return $this->render('portal/page/interior/furnishing.html.twig', [
            'cmsPage'=> $cmsPage,
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/decor", name="portal_interior_decor")
     * @param Request $request
     * @return Response
     */
    public function decor(Request $request, Mailer $mailer): Response
    {
        $slugName  = 'decor';
        $cmsPage = $this->getDoctrine()->getRepository(CmsPage::class)->findOneBy(['isActive'=>1,"pageSlugName"=>$slugName]);
        $formEnquiry = new FormEnquiry();
        $formEnquiry->setEnquiryForm($slugName);
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
        $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->findOneBy(['productCategorySlugName'=>"interiors","isActive"=>1]);
        $cmsPage = $this->getDoctrine()->getRepository(CmsPage::class)->findOneBy(['isActive'=>1,"pageSlugName"=>$slugName]);
        $formEnquiry->setMstProductCategory($mstProductCategory);
        $mstProductType = $this->getDoctrine()->getRepository(MstProductType::class)->findOneBy(['productTypeSlugName'=>$slugName,"isActive"=>1]);
        $formEnquiry->setMstProductType($mstProductType);
        $form = $this->createForm(FormEnquiryOneType::class, $formEnquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = explode(" ",$form->get("firstName")->getData());
            $formEnquiry->setMstLeadStatus($mstLeadStatus);
            $formEnquiry->setEnquiryFirstName($name[0]);
            $formEnquiry->setOrgCompany($orgCompany);
            $formEnquiry->setEnquiryCreateTime(new \DateTime());
            if (isset($name[1])){
                $formEnquiry->setEnquiryLastName($name[1]);
            }else{
                $formEnquiry->setEnquiryLastName("-");
            }
//            $formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formEnquiry);
            $entityManager->flush();
            $mailer->mailerFormEnquiry($formEnquiry);
            return $this->redirectToRoute('portal_interior_form_enquiry_success');
        }

        return $this->render('portal/page/interior/decor.html.twig', [
            'cmsPage'=> $cmsPage,
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/form-enquiry-success", name="form_enquiry_success", methods={"GET"}, priority="10")
     * @return Response
     */
    public function formEnquirySuccess(Request $request): Response
    {
        return $this->render('portal/page/interior/thank-you.html.twig', [
        ]);
    }

}

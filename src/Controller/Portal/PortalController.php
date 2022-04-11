<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsLandingPage;
use App\Entity\Cms\CmsPage;
use App\Entity\Cms\CmsUserSubscription;
use App\Entity\Form\FormEnquiry;
use App\Entity\Form\FormEnquiryContactUs;
use App\Entity\Form\FormEnquiryVastuTips;
use App\Entity\Form\FormResidentialEnquiry;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductType;
use App\Entity\Organization\OrgCompany;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Form\Form\FormResidentialEnquiryType;
use App\Form\Portal\ContactTredExperts2Type;
use App\Form\Portal\ContactTredExpertsType;
use App\Form\Portal\FormEnquiryFiveType;
use App\Form\Portal\FormEnquiryFourType;
use App\Form\Portal\FormEnquiryOneType;
use App\Form\Portal\FormEnquirySevenType;
use App\Form\Portal\FormEnquirySixType;
use App\Form\Portal\FormEnquiryThreeType;
use App\Form\Portal\FormEnquiryTwoType;
use App\Service\CommonHelper;
use App\Service\Mailer;
use App\Service\Portal\PortalCommonHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PortalController extends AbstractController
{
    /**
     * @Route("/", name="portal_index", methods={"GET"}, priority="10")
     * @return Response
     */
    public function index(Request $request, SessionInterface $session, PortalCommonHelper $portalCommonHelper): Response
    {
        $city = $portalCommonHelper->getCity($session->get('city'));
        return $this->render('portal/page/homepage/index.html.twig',[
            'selectedCity'=>$city,
        ]);
    }

    /**
     * @Route("/about-us", name="portal_about_us", methods={"GET"}, priority="10")
     * @return Response
     */
    public function aboutUs(): Response
    {
        return $this->render('portal/page/about_us.html.twig', [
        ]);
    }

    /**
     * @Route("/contact-us", name="portal_contact_us", methods={"GET","POST"}, priority="10")
     * @Route("/sales-enquiry", name="portal_sales_enquiry", methods={"GET","POST"}, priority="10")
     * @return Response
     */
    public function contactUs(Request $request,Mailer $mailer): Response
    {
        $formEnquiry = new FormEnquiryContactUs();
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
        $form = $this->createForm(FormEnquirySixType::class, $formEnquiry);
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
            //$formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formEnquiry);
            $entityManager->flush();
            $mailer->mailerFormEnquiryContact($formEnquiry);
            return $this->redirectToRoute('portal_form_contact_us_success');
        }
        return $this->render('portal/page/contact_us.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/vastu-tips", name="portal_vastu_tips", methods={"GET","POST"}, priority="10")
     * @return Response
     */
    public function vastuTips(Request $request, Mailer $mailer): Response
    {
        $formEnquiry = new FormEnquiryVastuTips();
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
        $form = $this->createForm(FormEnquirySevenType::class, $formEnquiry);
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
            $mailer->mailerFormEnquiryVastu($formEnquiry);
            return $this->redirectToRoute('portal_form_enquiry_success');
        }
        return $this->render('portal/page/vastu-tips.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/newslettersubscription", name="newsletter-subscription", methods={"GET","POST"}, priority="10")
     * @param Request $request
     * @return Response
     */
    public function setNewsletterSubscription(Request $request): Response
    {
        $email =  $request->request->get("email");
        if ($email) {
            $checkEmailSubscription = $this->getDoctrine()->getRepository(CmsUserSubscription::class)->findOneBy(['userSubscriptionEmail' => $email]);
            if (empty($checkEmailSubscription)) {

                $cmsUserSubscription = new CmsUserSubscription();
                $entityManager = $this->getDoctrine()->getManager();
                if (!empty($this->getUser())) {
                    $cmsUserSubscription->setAppuser($this->getUser());
                }
                $cmsUserSubscription->setUserSubscriptionEmail(trim($email));
                //$cmsUserSubscription->setOrgCompany($this->getDoctrine()->getRepository(OrgCompany::class)->find($this->getParameter('company_id')));
                $cmsUserSubscription->setIsSubscriptionActive(1);
                $cmsUserSubscription->setSubscriptionDateTime(new \DateTime('now', new \DateTimeZone('Asia/Kolkata')));
                $entityManager->persist($cmsUserSubscription);
                $entityManager->flush();
                return $this->json(['message' => 'Thank you for subscribing to our newsletters']);
            } else {
                return $this->json(['message' => 'You have already subscribed for newsletters']);
            }
        } else {
            return $this->json(['message' => 'Email id missing']);
        }

    }

    /**
     * @Route("/tred-experts/{mstPropertyTransactionCategory}", name="portal_tred_experts", methods={"GET","POST"}, priority="10")
     * @Route("/tred-experts/{mstPropertyTransactionCategory}/{trnProjectRoomConfiguration}", name="portal_tred_experts_room", methods={"GET","POST"}, priority="10")
     * @return Response
     */
    public function tredExperts(Request $request, Mailer $mailer): Response
    {
        $trnProjectRoomConfigurationId  = $request->get('trnProjectRoomConfiguration');
        $mstPropertyTransactionCategory  = $request->get('mstPropertyTransactionCategory');
        $formResidentialEnquiry = new FormResidentialEnquiry();
        $formResidentialEnquiry->setResidentialEnquiryTitle("Property Enquiry : ".$mstPropertyTransactionCategory);
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
        if ($trnProjectRoomConfigurationId){
            $trnProjectRoomConfiguration = $this->getDoctrine()->getRepository(TrnProjectRoomConfiguration::class)->find($trnProjectRoomConfigurationId);
            $formResidentialEnquiry->setTrnProjectRoomConfiguration($trnProjectRoomConfiguration);
            $form = $this->createForm(ContactTredExperts2Type::class, $formResidentialEnquiry);
        }else{
            $form = $this->createForm(ContactTredExpertsType::class, $formResidentialEnquiry);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = explode(" ",$form->get("firstName")->getData());
            $formResidentialEnquiry->setResidentialEnquiryFirstName($name[0]);
            $formResidentialEnquiry->setOrgCompany($orgCompany);
            $formResidentialEnquiry->setMstLeadStatus($mstLeadStatus);
            $formResidentialEnquiry->setResidentialEnquiryCreateTime(new \DateTime());
            if ($form->get("mstCity")->getData()){
                $formResidentialEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            }
            if (isset($name[1])){
                $formResidentialEnquiry->setResidentialEnquiryLastName($name[1]);
            }else{
                $formResidentialEnquiry->setResidentialEnquiryLastName("-");
            }
            //$formResidentialEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formResidentialEnquiry);
            $entityManager->flush();
            $mailer->mailerPropertiesFormEnquiry($formResidentialEnquiry);
            //$this->addFlash('success', 'form.created_successfully');
            return $this->redirectToRoute('portal_form_enquiry_success');
        }
        return $this->render('portal/page/product/tred-expert.html.twig', [
            "form"=>$form->createView(),
            "type"=>$trnProjectRoomConfigurationId,
        ]);
    }

    /**
     * @Route("/tred-experts-success", name="portal_tred_experts_success", methods={"GET"}, priority="10")
     * @return Response
     */
    public function tredExpertSuccess(Request $request): Response
    {
        return $this->render('portal/page/product/tred-expert-success.html.twig', [
        ]);
    }
    /**
     * @Route("/project-like", name="portal_project_like", methods={"POST"}, priority="10")
     * @return Response
     */
    public function portalLike(Request $request): Response
    {
        $id  = $request->get('id');
        $trnProject = $this->getDoctrine()->getManager()->getRepository(TrnProject::class)->findOneBy(['isActive'=>1,"id"=>$id]);
        if ($request->isXMLHttpRequest()) {
            $trnProject->setProjectLikes($trnProject->getProjectLikes()+1);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(array('data' => $trnProject->getProjectLikes()));
        }
        return new Response('This is not ajax!', 400);
    }

    /**
     * @Route("/form-enquiry-success", name="portal_form_enquiry_success", methods={"GET"}, priority="10")
     * @return Response
     */
    public function formEnquirySuccess(Request $request): Response
    {
        return $this->render('portal/page/common/form-enquiry-success.html.twig', [
        ]);
    }


    /**
     * @Route("/form-enquiry/{slugName}", name="portal_form_enquiry", methods={"GET","POST"}, priority="10")
     * @return Response
     */
    public function formEnquiry(Request $request, Mailer $mailer): Response
    {
        $slugName  = $request->get('slugName');
        $formEnquiry = new FormEnquiry();
        $formEnquiry->setEnquiryForm($slugName);
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
//        $cmsLandingPage = $this->getDoctrine()->getRepository(CmsLandingPage::class)->findOneBy(['isActive'=>1,"cmsLandingPageSlugName"=>$slugName]);

        if ($slugName == 'interior-designers' || $slugName == 'home-decor' || $slugName == 'home-furnishing'){
            $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->findOneBy(['productCategorySlugName'=>"interiors","isActive"=>1]);
            $formEnquiry->setMstProductCategory($mstProductCategory);
            $mstProductType = $this->getDoctrine()->getRepository(MstProductType::class)->findOneBy(['productTypeSlugName'=>$slugName,"isActive"=>1]);
            $formEnquiry->setMstProductType($mstProductType);
            if ($mstProductType->getProductTypeFormType() == "FormEnquiryOneType"){
                $form = $this->createForm(FormEnquiryOneType::class, $formEnquiry);
            }
            if ($mstProductType->getProductTypeFormType() == "FormEnquiryTwoType"){
                $form = $this->createForm(FormEnquiryTwoType::class, $formEnquiry);
            }
        }
        if ($slugName == 'logistics'){
            $mstProductCategory = $this->getDoctrine()->getRepository(MstProductCategory::class)->findOneBy(['productCategorySlugName'=>"logistics","isActive"=>1]);
            $form = $this->createForm(FormEnquiryFiveType::class, $formEnquiry);
        }




//        if ($cmsLandingPage->getMstProductType()){
//            $formEnquiry->setMstProductType($cmsLandingPage->getMstProductType());
//            $formEnquiry->setMstProductCategory($cmsLandingPage->getMstProductCategory());
//        }else{
//            $formEnquiry->setMstProductCategory($cmsLandingPage->getMstProductCategory());
//        }
//
//        if ($cmsLandingPage->getMstProductType() != null){
//            dd($cmsLandingPage->getMstProductType()->getProductTypeFormType());
//            /** home decor & home furnishing **/
//            if ($cmsLandingPage->getMstProductType()->getProductTypeFormType() == "FormEnquiryOneType"){
//                $form = $this->createForm(FormEnquiryOneType::class, $formEnquiry);
//            }
//
//            /** interior designer **/
//            if ($cmsLandingPage->getMstProductType()->getProductTypeFormType() =="FormEnquiryTwoType"){
//                $form = $this->createForm(FormEnquiryTwoType::class, $formEnquiry);
//            }
//            if ($cmsLandingPage->getMstProductType()->getProductTypeFormType() =="FormEnquiryFourType"){
//                $form = $this->createForm(FormEnquiryFourType::class, $formEnquiry);
//            }
//        }else{
//            if ($cmsLandingPage->getMstProductCategory()->getProductCategoryFormType() =="FormEnquiryThreeType"){
//                $form = $this->createForm(FormEnquiryThreeType::class, $formEnquiry);
//            }
//            if ($cmsLandingPage->getMstProductCategory()->getProductCategoryFormType() =="FormEnquiryFiveType"){
//                $form = $this->createForm(FormEnquiryFiveType::class, $formEnquiry);
//            }
//        }

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
//            $this->addFlash('success', 'Thank you for your Enquiry');
//            return $this->redirectToRoute('portal_form_enquiry',['slugName'=>$slugName]);
        }
        if ($slugName == 'interior-designers' || $slugName == 'home-decor' || $slugName == 'home-furnishing') {
            if ($mstProductType->getProductTypeFormType() == "FormEnquiryOneType") {
                return $this->render('portal/page/common/frm-query.html.twig', [
                    "form" => $form->createView()
                ]);
            }
            if ($mstProductType->getProductTypeFormType() == "FormEnquiryTwoType") {
                return $this->render('portal/page/common/frm-query-two.html.twig', [
                    "form" => $form->createView()
                ]);
            }
        }
        if ($slugName == 'logistics'){
            if ($mstProductCategory->getProductCategoryFormType() == "FormEnquiryFiveType"){
                return $this->render('portal/page/common/frm-query-five.html.twig', [
                    "form"=>$form->createView()
                ]);
            }

        }

//        if ($cmsLandingPage->getMstProductType()){
//            if ($cmsLandingPage->getMstProductType()->getProductTypeFormType() == "FormEnquiryOneType"){
//                return $this->render('portal/page/common/frm-query.html.twig', [
//                    "form"=>$form->createView()
//                ]);
//            }
//            if ($cmsLandingPage->getMstProductType()->getProductTypeFormType() =="FormEnquiryTwoType"){
//                return $this->render('portal/page/common/frm-query-two.html.twig', [
//                    "form"=>$form->createView()
//                ]);
//            }
//            if ($cmsLandingPage->getMstProductType()->getProductTypeFormType() =="FormEnquiryFourType"){
//                return $this->render('portal/page/common/frm-query-four.html.twig', [
//                    "form"=>$form->createView()
//                ]);
//            }
//        }else{
//            if ($cmsLandingPage->getMstProductCategory()->getProductCategoryFormType() =="FormEnquiryThreeType"){
//                return $this->render('portal/page/common/frm-query-three.html.twig', [
//                    "form"=>$form->createView()
//                ]);
//            }
//            if ($cmsLandingPage->getMstProductCategory()->getProductCategoryFormType() =="FormEnquiryFiveType"){
//                return $this->render('portal/page/common/frm-query-five.html.twig', [
//                    "form"=>$form->createView()
//                ]);
//            }
//        }
    }

    /**
     * @Route("/faqs", name="portal_faqs", methods={"GET"}, priority="10")
     * @return Response
     */
    public function faqs(): Response
    {
        return $this->render('portal/page/faqs.html.twig');
    }

//    /**
//     * @Route("/form-contact-us", name="portal_form_contact_us", methods={"GET","POST"}, priority="10")
//     * @return Response
//     */
//    public function formEnquiryContactUs(Request $request, Mailer $mailer): Response
//    {
//        $formEnquiry = new FormEnquiryContactUs();
//        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
//        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
//        $form = $this->createForm(FormEnquirySixType::class, $formEnquiry);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $name = explode(" ",$form->get("firstName")->getData());
//            $formEnquiry->setMstLeadStatus($mstLeadStatus);
//            $formEnquiry->setEnquiryFirstName($name[0]);
//            $formEnquiry->setOrgCompany($orgCompany);
//            $formEnquiry->setEnquiryCreateTime(new \DateTime());
//            if (isset($name[1])){
//                $formEnquiry->setEnquiryLastName($name[1]);
//            }else{
//                $formEnquiry->setEnquiryLastName("-");
//            }
//            //$formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($formEnquiry);
//            $entityManager->flush();
//            $mailer->mailerFormEnquiryContact($formEnquiry);
////            $this->addFlash('success', 'Thank you for your Enquiry');
//            return $this->redirectToRoute('portal_form_contact_us_success');
//        }
//        return $this->render('portal/page/common/frm-query-contact-us.html.twig', [
//            "form"=>$form->createView()
//        ]);
//
//    }

    /**
     * @Route("/form-contact-us-success", name="portal_form_contact_us_success", methods={"GET"}, priority="10")
     * @return Response
     */
    public function formContactUsSuccess(Request $request): Response
    {
        return $this->render('portal/page/common/form-enquiry-contact-us-success.html.twig', [
        ]);
    }

    /**
     * @Route("/form-vastu-tips", name="portal_form_vastu_tips", methods={"GET","POST"}, priority="10")
     * @return Response
     */
    public function formVastuTips(Request $request,Mailer $mailer): Response
    {
        $formEnquiry = new FormEnquiryVastuTips();
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
        $form = $this->createForm(FormEnquirySevenType::class, $formEnquiry);
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
            $formEnquiry->setMstState($form->get("mstCity")->getData()->getMstState());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formEnquiry);
            $entityManager->flush();
            $mailer->mailerFormEnquiryVastu($formEnquiry);
//            $this->addFlash('success', 'Thank you for your Enquiry');
//            return $this->redirectToRoute('portal_vastu_tips');
        }
        return $this->render('portal/page/common/frm-query-vastu-tips.html.twig',[
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/advertise", name="portal_advertise", methods={"GET"})
     * @return Response
     */
    public function advertise(): Response
    {
        return $this->render('portal/page/advertise.html.twig', [
        ]);
    }

    /**
     * @Route("/terms-and-conditions", name="portal_tnc", methods={"GET"}, priority="10")
     * @return Response
     */
    public function termsAndConditions(): Response
    {
        return $this->render('portal/page/terms_and_condition.html.twig', [
        ]);
    }

    /**
     * @Route("/user-agreement", name="portal_user_agreement", methods={"GET"}, priority="10")
     * @return Response
     */
    public function userAgreement(): Response
    {
        return $this->render('portal/page/user_agreement.html.twig', [
        ]);
    }

    /**
     * @Route("/privacy-policy", name="portal_privacy_policy", methods={"GET"}, priority="10")
     * @return Response
     */
    public function privacyPolicy(): Response
    {
        return $this->render('portal/page/privacy_policy.html.twig', [
        ]);
    }

    /**
     * @Route("/search", name="search", methods={"GET"}, priority="10")
     * @param Request $request
     * @return Response
     */

    public function gobalSearch(Request $request,CommonHelper $commonHelper): Response
    {
        $search = $request->get("search");
        $propertyType = $request->get("propertyType");
        $data = $commonHelper->getGlobalData();
        $response = [];
        if($search) {
            foreach ($data as $val) {
                if (strstr(strtolower($val['search']), strtolower($search))) {
                    if ($propertyType == 0 ){
                        $response[] = $val;
                    }elseif($val['propertyType'] == $propertyType ){
                        $response[] = $val;
                    }
                }
            }
        }
        return $this->json($response);
    }

    /**
     * @Route("/city/{city}", name="portal_project_city", methods={"GET"}, priority="9")
     * @return Response
     */
    public function citysearch(Request $request, SessionInterface $session): Response
    {
        $session->set('city',$request->attributes->get('city'));
        $city = $session->get('city');
        return $this->render('portal/page/homepage/index.html.twig',[
            'selectedCity'=>$city,
        ]);
    }

    /**
     * @Route("/room-configuration-like", name="portal_room_configuration_like", methods={"POST"}, priority="10")
     * @return Response
     */
    public function roomConfigurationLike(Request $request): Response
    {
        $id  = $request->get('id');
        $trnProjectRoomConfiguration = $this->getDoctrine()->getManager()->getRepository(TrnProjectRoomConfiguration::class)->findOneBy(['isActive'=>1,"id"=>$id]);
        if ($request->isXMLHttpRequest()) {
            $trnProjectRoomConfiguration->setRoomConfigurationLikes($trnProjectRoomConfiguration->getRoomConfigurationLikes()+1);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(array('data' => $trnProjectRoomConfiguration->getRoomConfigurationLikes()));
        }
        return new Response('This is not ajax!', 400);
    }
}

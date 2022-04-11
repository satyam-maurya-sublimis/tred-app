<?php

namespace App\Controller\Portal;

use App\Entity\Form\FormEnquiryTopAgents;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Organization\OrgCompany;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Entity\Transaction\TrnTopVendorPartners;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Form\Portal\PortalFormEnquiryTopAgentsType;
use App\Service\Mailer;
use App\Service\Portal\PortalCommonHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/top-agents", name="portal_top_agents_")
 */
class TopAgentsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $topAgents = $this->getDoctrine()->getRepository(TrnTopVendorPartners::class)->findBy(["isActive"=> 1]);
        return $this->render('portal/page/top_agents/index.html.twig', [
            'topAgents' => $topAgents
        ]);
    }

    /**
     * @Route("/searchcity/", name="search_city", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function searchCity(Request $request): Response
    {
        $request->isXmlHttpRequest();
        $city = $request->query->get('city');
        $mstCity = $this->getDoctrine()->getRepository(MstCity::class)->getTopAgentsCities($city);
        return $this->render('portal/page/top_agents/_cities.html.twig',[
            'cities' => $mstCity
        ]);
    }

    /**
     * @Route("/filter", name="filter", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function propertyFilter(Request $request, PortalCommonHelper $portalCommonHelper): Response
    {
        $filters = $portalCommonHelper->propertycustomFilter($request);
        $trnProjectRoomConfigurations = $this->getDoctrine()->getManager()->getRepository(TrnProjectRoomConfiguration::class)->getRoomConfiguration($filters);
        if ($filters['propertyTransactionCategoryId'] == 1){
//            return $this->render('portal/page/top_agents/sale.html.twig', [
//                'trnProjectRoomConfigurations' => $trnProjectRoomConfigurations,
//                'filters' => $filters,
//                'transactionCategoryId'=> $filters['propertyTransactionCategoryId'],
//            ]);
            return $this->render('portal/page/top_agents/slisting.html.twig', [
                'trnProjectRoomConfigurations' => $trnProjectRoomConfigurations,
                'filters' => $filters,
                'transactionCategoryId'=> $filters['propertyTransactionCategoryId'],
            ]);
        }else{
//            return $this->render('portal/page/top_agents/rent.html.twig', [
//                'trnProjectRoomConfigurations' => $trnProjectRoomConfigurations,
//                'filters' => $filters,
//                'transactionCategoryId'=> $filters['propertyTransactionCategoryId'],
//            ]);
            return $this->render('portal/page/top_agents/rlisting.html.twig', [
                'trnProjectRoomConfigurations' => $trnProjectRoomConfigurations,
                'filters' => $filters,
                'transactionCategoryId'=> $filters['propertyTransactionCategoryId'],
            ]);
        }

    }
    /**
     * @Route("/enquiry/{trnVendorPartnerDetailsId}/",defaults={"frm" = "1"},name="enquiry", methods={"GET","POST"}, priority="10")
     * @Route("/enquiry/{trnVendorPartnerDetailsId}/{frm}", name="enquiry_frm", methods={"GET","POST"}, priority="10")
     * @return Response
     */
    public function formEnquiry(Request $request,Mailer $mailer,$trnVendorPartnerDetailsId,$frm): Response
    {
        $formEnquiry = new FormEnquiryTopAgents();
        $orgCompany = $this->getDoctrine()->getRepository(OrgCompany::class)->find(1);
        $trnVendorPartnerDetails = $this->getDoctrine()->getRepository(TrnVendorPartnerDetails::class)->find($trnVendorPartnerDetailsId);
        $mstLeadStatus = $this->getDoctrine()->getRepository(MstLeadStatus::class)->find(1);
        $form = $this->createForm(PortalFormEnquiryTopAgentsType::class, $formEnquiry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $name = explode(" ",$form->get("firstName")->getData());
            $formEnquiry->setTrnVendorPartnerDetails($trnVendorPartnerDetails);
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
            $mailer->mailerFormEnquiryTopAgent($formEnquiry);
            return $this->redirectToRoute('portal_form_enquiry_success');
        }
        return $this->render('portal/page/common/form-equiry-top-agents.html.twig',[
            "form"=>$form->createView(),
            'frm'=>$frm
        ]);
    }

    /**
     * @Route("/{city}", name="city")
     * @param Request $request
     * @return Response
     */
    public function city(Request $request): Response
    {
        $city = ucfirst($request->attributes->get('city'));
        $mstCity = $this->getDoctrine()->getRepository(MstCity::class)->getIndiaCity($city);
        return $this->render('portal/page/top_agents/city.html.twig', [
            'city' => $mstCity
        ]);
    }

    /**
     * @Route("/{title}/{id}", name="detail")
     * @param Request $request
     * @param TrnTopVendorPartners $trnTopVendorPartners
     * @return Response
     */
    public function detail(Request $request, TrnTopVendorPartners $trnTopVendorPartners, PortalCommonHelper $portalCommonHelper): Response
    {
        $filter = [];
        $trnVendorPartnerDetails = $trnTopVendorPartners->getTrnVendorPartnerDetails();
        $filter['trnVendorPartnerDetailsId'] = $trnVendorPartnerDetails->getId();
        $trnProjectRoomConfigurations = $this->getDoctrine()->getRepository(TrnProjectRoomConfiguration::class)->getRoomConfigurationByTopVendorPartners($filter);
        $menuFilters = $portalCommonHelper->getTrnProjectRoomConfigurationFilters($trnProjectRoomConfigurations);
        return $this->render('portal/page/top_agents/detail.html.twig', [
            'trnTopVendorPartners' => $trnTopVendorPartners,
            'trnVendorPartnerDetails' => $trnVendorPartnerDetails,
            'trnProjectRoomConfigurations' => $trnProjectRoomConfigurations,
            'menuFilters' => $menuFilters,
        ]);
    }

}

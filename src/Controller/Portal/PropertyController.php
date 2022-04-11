<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsPage;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Service\Portal\PortalCommonHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{

//    /**
//     * @Route("/", name="portal_properties_index", methods={"GET","POST"})
//     * @return Response
//     */
//    public function properties(Request $request): Response
//    {
//        return $this->render('portal/page/properties/index.html.twig', [
//        ]);
//    }
    private $portalCommonHelper;
    public function __construct(PortalCommonHelper $portalCommonHelper)
    {
        $this->portalCommonHelper = $portalCommonHelper;
    }

    /**
     * @Route("/filter", name="portal_properties_filter", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function propertyFilter(Request $request): Response
    {
        $filters = $this->customFilters($request);
//        $trnProject = $this->getDoctrine()->getManager()->getRepository(TrnProject::class)->getProject(null,$filters);
        $trnProjectRoomConfiguration = $this->getDoctrine()->getManager()->getRepository(TrnProjectRoomConfiguration::class)->getRoomConfiguration($filters);
//        if ($request->get('sort') == "High") {
//                return $this->render('portal/page/product/property-listings.html.twig', [
//                    'projects'=> array_reverse($trnProject),
//                    'filters' => $filters,
//                    'transactionCategoryId'=> $filters['propertyTransactionCategoryId']
//                ]);
//        }else {
            return $this->render('portal/page/product/property-listing.html.twig', [
                'projectData' => $trnProjectRoomConfiguration,
                'filters' => $filters,
                'transactionCategoryId'=> $filters['propertyTransactionCategoryId']
            ]);
//        }

    }

    /**
     * @Route("/selected-filter", name="portal_properties_selected_filter", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function propertySelectedFilters(Request $request): Response
    {
        $filters = $this->customFilters($request);
        return $this->render('portal/page/product/selected-filters.html.twig', [
            'filters' => $filters
        ]);
    }

    /**
     * @Route("/property/{id}/detail/{projectId}", name="portal_properties_detail", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function propertyDetail(Request $request): Response
    {
        $projectId  = $request->get('projectId');
        $roomId  = $request->get('id');
//        $trnProject = $this->getDoctrine()->getManager()->getRepository(TrnProject::class)->findOneBy(['isActive'=>1,"id"=>$id]);
//        $trnProject->setProjectViews($trnProject->getProjectViews()+1);
//        $this->getDoctrine()->getManager()->flush();
//        return $this->render('portal/page/product/property-detail.html.twig', [
//            'project'=> $trnProject
//        ]);
        $trnProjectRoomConfiguration = $this->getDoctrine()->getManager()->getRepository(TrnProjectRoomConfiguration::class)->findOneBy(['isActive'=>1,"id"=>$roomId,'trnProject'=>$projectId]);
        $cnt = $trnProjectRoomConfiguration->getRoomConfigurationViews()?$trnProjectRoomConfiguration->getRoomConfigurationViews():0;
        $trnProjectRoomConfiguration->setRoomConfigurationViews($cnt+1);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('portal/page/product/room-configuration-detail.html.twig', [
            'roomConfigurations'=> $trnProjectRoomConfiguration
        ]);

    }

    /**
     * @Route("/buy/{slugName}", name="portal_properties_buy_list", methods={"GET","POST"})
     * @Route("/buy/{slugName}/{city}", name="portal_properties_buy_city_list", methods={"GET","POST"})
     * @return Response
     */
    public function propertiesBuy(Request $request, SessionInterface $session, PortalCommonHelper $portalCommonHelper): Response
    {

        $slugName  = $request->get('slugName');
        $city = $request->get('city');
        if ($city != "") {
            $session->set('city',$city);
        }
        $city = $session->get('city');
        $filters = $portalCommonHelper->filters($_POST);
        if(!isset($filters['mstCity'])){
            $filters['mstCity'] = ucfirst($city);
        }
        $filters['slugName'] = $slugName;
        $filters['productCategory'] = 'properties';
        if(!isset($filters['propertyTransactionCategoryId'])){
            $filters['propertyTransactionCategoryId'] = 1;
        }

//        $trnProject = $this->getDoctrine()->getManager()->getRepository(TrnProject::class)->getProject(null,$filters);
        $trnProjectRoomConfiguration = $this->getDoctrine()->getManager()->getRepository(TrnProjectRoomConfiguration::class)->getRoomConfiguration($filters);
        return $this->render('portal/page/product/property-buy-list.html.twig', [
            'projectData'=> $trnProjectRoomConfiguration,
            'filters'=> $filters,
            'transactionCategoryId'=> $filters['propertyTransactionCategoryId'],
            'page'=>'Buy',
            'city'=>$city,
            'slugName'=>$slugName
        ]);
    }

    /**
     * @Route("/rent/{slugName}", name="portal_properties_rent_list", methods={"GET","POST"})
     * @Route("/rent/{slugName}/{city}", name="portal_properties_rent_city_list", methods={"GET","POST"})
     * @return Response
     */
    public function propertiesRent(Request $request, SessionInterface $session, PortalCommonHelper $portalCommonHelper): Response
    {
        $slugName  = $request->get('slugName');
        $city = $request->get('city');
        if ($city != "") {
            $session->set('city',$city);
        }
        $city = $session->get('city');
        $filters = $portalCommonHelper->filters($_POST);
        if(!isset($filters['mstCity'])){
            $filters['mstCity'] = ucfirst($city);
        }
        $filters['slugName'] = $slugName;
        $filters['productCategory'] = 'properties';
        if(!isset($filters['propertyTransactionCategoryId'])){
            $filters['propertyTransactionCategoryId'] = 2;
        }
//        $trnProject = $this->getDoctrine()->getManager()->getRepository(TrnProject::class)->getProject(null,$filters);
        $trnProjectRoomConfiguration = $this->getDoctrine()->getManager()->getRepository(TrnProjectRoomConfiguration::class)->getRoomConfiguration($filters);
        return $this->render('portal/page/product/property-rent-list.html.twig', [
            'projectData'=> $trnProjectRoomConfiguration,
            'filters'=> $filters,
            'transactionCategoryId'=> $filters['propertyTransactionCategoryId'],
            'page'=>'Rent',
            'city'=>$city,
            'slugName'=>$slugName
        ]);
    }

    private function customFilters(Request $request) {
        return $this->portalCommonHelper->propertycustomFilter($request);
    }

    /**
     * @Route("/buy/{slugName}/{city}/{title}/{id}", name="portal_properties_buy_detail", methods={"GET","POST"})
     * @Route("/rent/{slugName}/{city}/{title}/{id}", name="portal_properties_rent_detail", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newPropertyDetail(Request $request): Response
    {
        $slugName  = $request->get('slugName');
        $city = $request->get('city');
        $roomId  = $request->get('id');
        $trnProjectRoomConfiguration = $this->getDoctrine()->getManager()->getRepository(TrnProjectRoomConfiguration::class)->findOneBy(['isActive'=>1,"id"=>$roomId]);
        $cnt = $trnProjectRoomConfiguration->getRoomConfigurationViews()?$trnProjectRoomConfiguration->getRoomConfigurationViews():0;
        $trnProjectRoomConfiguration->setRoomConfigurationViews($cnt+1);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('portal/page/product/room-configuration-detail.html.twig', [
            'roomConfigurations'=> $trnProjectRoomConfiguration,
            'slugName'=>$slugName,
            'city'=>$city,
        ]);

    }
}

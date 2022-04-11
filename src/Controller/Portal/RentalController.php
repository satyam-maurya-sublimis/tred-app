<?php

namespace App\Controller\Portal;

use App\Entity\Cms\CmsPage;
use App\Entity\Transaction\TrnProject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rentals", name="portal_rentals_")
 */
class RentalController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET","POST"})
     * @return Response
     */
    public function properties(Request $request): Response
    {
        return $this->render('portal/page/properties/index.html.twig', [
        ]);
    }

    /**
     * @Route("/filter", name="filter", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function propertyFilter(Request $request): Response
    {
        $filters = $this->customFilters($request);
        $trnProject = $this->getDoctrine()->getManager()->getRepository(TrnProject::class)->getProject(null,$filters);
        if ($request->get('sort') == "High"){
            return $this->render('portal/page/product/property-listings.html.twig', [
                'projects'=> array_reverse($trnProject),
                'filters' => $filters
            ]);
        }else {
            return $this->render('portal/page/product/property-listings.html.twig', [
                'projects' => $trnProject,
                'filters' => $filters
            ]);
        }

    }

    /**
     * @Route("/selected-filter", name="selected_filter", methods={"GET","POST"})
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
     * @Route("/{id}/detail", name="detail", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function propertyDetail(Request $request): Response
    {
        $id  = $request->get('id');
        $trnProject = $this->getDoctrine()->getManager()->getRepository(TrnProject::class)->findOneBy(['isActive'=>1,"id"=>$id]);
        $trnProject->setProjectViews($trnProject->getProjectViews()+1);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('portal/page/product/property-detail.html.twig', [
            'project'=> $trnProject
        ]);
    }

    /**
     * @Route("/{slugName}", name="list", methods={"GET","POST"})
     * @Route("/{slugName}/{status}", name="list-status", methods={"GET","POST"})
     * @return Response
     */
    public function propertiesListing(Request $request): Response
    {
        $slugName  = $request->get('slugName');
        $status  = $request->get('status');
        $filters['slugName'] = $slugName;
        $filters['productCategory'] = 'rentals';
//        if ($status){
//            $filters['projectStatus'] = $status;
//        }
        $trnProject = $this->getDoctrine()->getManager()->getRepository(TrnProject::class)->getProject(null,$filters);
        return $this->render('portal/page/product/property-list.html.twig', [
            'projects'=> $trnProject
        ]);
    }

    private function customFilters(Request $request){
        $filters = [];
        $filters['productCategory']= 'rentals';
        if ($request->get('propertyTypeId')) $filters['propertyTypeId']  = $request->get('propertyTypeId');
        if ($request->get('productSubType')) $filters['productSubType']  = $request->get('productSubType');
        if ($request->get('projectRoomConfigurations')) $filters['projectRoomConfigurations']  = $request->get('projectRoomConfigurations');
        if ($request->get('projectPossessions')) $filters['projectPossessions']  = $request->get('projectPossessions');
        if ($request->get('projectPostedBy')) $filters['projectPostedBy']  = $request->get('projectPostedBy');
        if ($request->get('projectAmenities')) $filters['projectAmenities']  = $request->get('projectAmenities');
        if ($request->get('noOfBathRooms')) $filters['noOfBathRooms']  = $request->get('noOfBathRooms');
        if ($request->get('propertyAreaValue')) $filters['propertyAreaValue']  = $request->get('propertyAreaValue');
        if ($request->get('propertyAreaRange')) $filters['propertyAreaRange']  = $request->get('propertyAreaRange');
        if ($request->get('priceRangeMin')) $filters['priceRangeMin']  = $request->get('priceRangeMin');
        if ($request->get('priceRangeMax')) $filters['priceRangeMax']  = $request->get('priceRangeMax');
        if ($request->get('priceRangeMinDenomination')) $filters['priceRangeMinDenomination']  = $request->get('priceRangeMinDenomination');
        if ($request->get('priceRangeMaxDenomination')) $filters['priceRangeMaxDenomination']  = $request->get('priceRangeMaxDenomination');
        if ($request->get('sort')) $filters['sort']  = $request->get('sort');
        if ($request->get('projectStatus')) $filters['projectStatus'] = $request->get('projectStatus');
        if ($request->get('slugName')) $filters['slugName'] = $request->get('slugName');
        return $filters;
    }
}

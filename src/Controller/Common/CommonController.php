<?php

namespace App\Controller\Common;

use App\Entity\Cms\CmsUserSubscription;
use App\Entity\Master\MstAreaInCity;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstFurnitureCategory;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstState;
use App\Entity\Master\MstSubCategory;
use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Entity\Transaction\TrnVendorPartnerOffices;
use App\Entity\Transaction\TrnProject;
use App\Service\CommonHelper;
use Doctrine\Persistence\ManagerRegistry;
use Proxies\__CG__\App\Entity\Master\MstPropertyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommonController
 * @package App\Controller\Common
 * @IsGranted("ROLE_APP_USER")
 */
class CommonController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    /**
     * @Route("/core/location/state_list", name="location_state_list", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function statesList(Request $request): Response
    {
        $country_id = $request->query->get('q');
        $stateList = $this->managerRegistry->getRepository(MstState::class)->getStateListByCountryId($country_id);
        return $this->json($stateList);
    }

    /**
     * @Route("/core/location/city_list", name="location_city_list", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function citiesList(Request $request): Response
    {
        $state_id = $request->query->get('q');
        $cityList = $this->managerRegistry->getRepository(MstCity::class)->getCityListByStateId($state_id);
        return $this->json($cityList);
    }

    /**
     * @Route("/core/location/area_in_city_list", name="location_area_in_city_list", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function areacitiesList(Request $request): Response
    {
        $city_id = $request->query->get('q');
        $areaInCityList = $this->managerRegistry->getRepository(MstAreaInCity::class)->getAreaInCityListByCityId($city_id);
        return $this->json($areaInCityList);
    }

    /**
     * @Route("/core/vendor_partner/get_offices", name="vendor_partner_get_offices", methods={"GET",
     *     "POST"})
     * @param Request $request
     * @return Response
     */
    public function getVendorPartnerOfficesList(Request $request): Response
    {
        $vendor_partner_id = $request->query->get('q');
        $vendorOffices = $this->managerRegistry->getRepository(TrnVendorPartnerOffices::class)
            ->getVendorPartnerOfficesList($vendor_partner_id);
        return $this->json($vendorOffices);
    }

    /**
     * @Route("/core/vendor_partner/get_office_category", name="vendor_partner_get_office_category", methods={"GET",
     *     "POST"})
     * @param Request $request
     * @return Response
     */
    public function getVendorPartnerOfficeCategoriesList(Request $request): Response
    {
        $vendor_partner_office_id = $request->query->get('q');
        $vendorOffices = $this->managerRegistry->getRepository(TrnVendorPartnerOffices::class)
            ->getVendorPartnerOfficeCategoriesList($vendor_partner_office_id);
        return $this->json($vendorOffices);
    }


    /**
     * @Route("/core/project/get_details", name="project_get_details", methods={"GET",
     *     "POST"})
     * @param Request $request
     * @return Response
     */
    public function getProjectDetails(Request $request): Response
    {
        $projectId = $request->query->get('q');
        $vendorOffices = $this->managerRegistry->getRepository(TrnProject::class)
            ->getProjectDetails($projectId);
        return $this->json($vendorOffices);
    }

    /**
     * @Route("/core/product-type", name="location_productType", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function productType(Request $request): Response
    {
        $productCategoryId = $request->query->get('q');
        $mstProductType = $this->managerRegistry->getRepository(MstProductType::class)->getProductTypeByCategoryId($productCategoryId);
        $productType = [];
        foreach($mstProductType as $val){
            $productType[] = ["id"=>$val->getId(),"name"=>$val->getProductType()];
        }
        return $this->json($productType);
    }

    /**
     * @Route("/core/location/amentities", name="location_amentities", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function amenitiesCategory(Request $request): Response
    {
        $subCategoryId = $request->query->get('q');
        $mstProjectAmenties = $this->managerRegistry->getRepository(MstProjectAmenities::class)->getAmenitiesBySubCategoryId($subCategoryId);
        return $this->json($mstProjectAmenties);
    }

    /**
     * @Route("/core/product-sub-type", name="location_productSubType", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function productSubType(Request $request): Response
    {
        $mstProductTypeId = $request->query->get('q');
        $mstProductSubType = $this->managerRegistry->getRepository(MstProductSubType::class)->getProductSubTypeByProductTypeId($mstProductTypeId);
        $productSubType = [];
        foreach($mstProductSubType as $val){
            $productSubType[] = ["id"=>$val->getId(),"name"=>$val->getProductSubType()];
        }
        return $this->json($productSubType);
    }

    /**
     * @Route("/core/project-status", name="location_projectStatus", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function productStatus(Request $request): Response
    {
        $mstProductTypeId = $request->query->get('q');
        $mstProductType = $this->managerRegistry->getRepository(MstProductType::class)->findOneBy(['isActive'=>1,"id"=>$mstProductTypeId]);
        if ($mstProductType){
            if ($mstProductType->getProductTypeSlugName() == "resale"){
                $mstProjectStatus = $this->managerRegistry->getRepository(MstPropertyType::class)->findBy(['isActive'=>1,"rowId"=>"402032f7-0d7f-456a-8f58-03cdecfb5c27"]);
            }else{
                $mstProjectStatus = $this->managerRegistry->getRepository(MstPropertyType::class)->findBy(['isActive'=>1]);
            }
        }else{
            $mstProjectStatus = $this->managerRegistry->getRepository(MstPropertyType::class)->findBy(['isActive'=>1]);
        }
        $productStatus = [];
        foreach($mstProjectStatus as $val){
            $productStatus[] = ["id"=>$val->getId(),"name"=>$val->getPropertyType()];
        }
        return $this->json($productStatus);
    }

    /**
     * @Route("/core/vendor-partner-list", name="vendor_partner_list", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function getVendorPartnerList(Request $request): Response
    {
        $vendor_type_id = $request->query->get('q');
        $userVendorCompanyId = null;
        if ($this->getUser()->getAppUserInfo()->getTrnVendorPartnerDetails()){
            $userVendorCompanyId = $this->getUser()->getAppUserInfo()->getTrnVendorPartnerDetails()->getId();
        }
        $vendor = $this->managerRegistry->getRepository(TrnVendorPartnerDetails::class)->getVendorPartnerlist($vendor_type_id,$userVendorCompanyId);
        return $this->json($vendor);
    }

    /**
     * @Route("/core/pincode-list", name="pincode_list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getPincodeList(Request $request): Response
    {
        $pincode = $request->query->get('pincode');
        $mstPincode = $this->managerRegistry->getRepository(MstPincode::class)->getPincodelist($pincode);
        return $this->json($mstPincode);
    }
    /**
     * @Route("/core/update/trnproject/date", name="date_list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function getUpdateDate(Request $request, CommonHelper $commonHelper): Response
    {
        $trnProjects = $this->managerRegistry->getRepository(TrnProject::class)->findBy(["isActive"=>1]);
        $posssionCollectionDate = [];
        foreach($trnProjects as $trnProject){
            $possessionDate = $trnProject->getPossessionDate();
            $possessionYear = $trnProject->getPossessionYear();
            $possessionMonth = $trnProject->getPossessionMonth();
            $posssionCollectionDate1 = $commonHelper->possessionDate($possessionDate,$possessionYear,$possessionMonth);
            $posssionCollectionDate[] = $posssionCollectionDate1;
            if ($posssionCollectionDate1){
                $date = new \DateTime($posssionCollectionDate1);
                $trnProject->setActualPossessionDate($date);
                $this->managerRegistry->getManager()->persist($trnProject);
            }
        }
        $this->managerRegistry->getManager()->flush();
        dd($posssionCollectionDate);
    }

    /**
     * @Route("/core/furniture-category", name="location_furniture_category", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function furnitureCategory(Request $request): Response
    {
        $mstProductSubTypeId = $request->query->get('q');
        $mstFurnitureCategory = $this->managerRegistry->getRepository(MstFurnitureCategory::class)->findBy(['isActive'=>1,'mstProductSubType'=>$mstProductSubTypeId]);
        $furnitureCategory = [];
        foreach($mstFurnitureCategory as $val){
            $furnitureCategory[] = ["id"=>$val->getId(),"name"=>$val->getFurnitureCategory()];
        }
        return $this->json($furnitureCategory);
    }
    /**
     * @Route("/core/furniture-product-catalogue", name="location_trnFurnitureProductCatalog", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function furnitureProductCatalog(Request $request): Response
    {
        $mstProductTypeId = $request->query->get('q');
        $trnFurnitureProductCatalog = $this->managerRegistry->getRepository(TrnFurnitureProductCatalog::class)->findBy(['isActive'=>1,'mstProductType'=>$mstProductTypeId]);
        $furnitureCategory = [];
        foreach($trnFurnitureProductCatalog as $val){
            $furnitureCategory[] = ["id"=>$val->getId(),"name"=>$val->getCatalogName()];
        }
        return $this->json($furnitureCategory);
    }

    /**
     * @Route("/core/location/locality_in_city", name="locality_in_city", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function localityList(Request $request): Response
    {
        $city_id = $request->query->get('q');
        $mstCity = $this->managerRegistry->getRepository(MstCity::class)->find($city_id);
        $mstPincode = $this->managerRegistry->getRepository(MstPincode::class)->getPincodelistByCityName($mstCity->getCity());
        return $this->json($mstPincode);
    }
}

<?php


namespace App\Service\Portal;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\SystemApp\AppUser;
class PortalCommonHelper {
    private $em;
    private $session;
    private $parameterBag;

    public function __construct(EntityManagerInterface $entityManager,RequestStack $session, ParameterBagInterface $parameterBag)
    {
        $this->em = $entityManager;
        $this->session = $session;
        $this->parameterBag = $parameterBag;
    }

    public function filters($postData){
        //$appUser = $this->em->getRepository(AppUser::class)->find($postData['userId']);
        $filters = [];
        if($postData){
            if (isset($postData["roomConfiguration"]) && $postData["roomConfiguration"] !=""){
                if (is_array($postData["roomConfiguration"])){
                    $filters["projectRoomConfigurations"]=$postData["roomConfiguration"];
                }else{
                    $filters["projectRoomConfigurations"]=explode(",",$postData["roomConfiguration"]);
                }

            }
            if (isset($postData["projectStatus"]) && $postData["projectStatus"] !=""){
                $filters["projectStatus"]=explode(",",$postData["projectStatus"]);
            }
            if (isset($postData["mstPincode"]) && $postData["mstPincode"] !=""){
                $filters["mstPincode"] = $postData["mstPincode"];
            }
            if (isset($postData["minrange"]) && $postData["minrange"] !=""){
                $minprice = explode(" ",trim($postData["minrange"]));
                $filters["priceRangeMin"] = $minprice[0];
                if (isset($minprice[1])){
                    $filters["priceRangeMinDenomination"] = $minprice[1];
                }else{
                    $filters["priceRangeMinDenomination"] = '';
                }
            }
            if (isset($postData["maxrange"]) && $postData["maxrange"] !=""){
                $maxprice = explode(" ",trim($postData["maxrange"]));
                $filters["priceRangeMax"] = $maxprice[0];
                if (isset($maxprice[1])){
                    $filters["priceRangeMaxDenomination"] = $maxprice[1];
                }else{
                    $filters["priceRangeMaxDenomination"] = '';
                }
            }
            if (isset($postData["propertyTransactionCategoryId"]) && $postData["propertyTransactionCategoryId"] !=""){
                $filters["propertyTransactionCategoryId"] = $postData["propertyTransactionCategoryId"];
            }

        }
        return $filters;

    }

    function getCity($city){

        if ($city != "") {
            if ($city == "favicon.ico"){
                $city = $this->parameterBag->get('city');
                $this->session->set('city',$city);
            }
        }else{
            $city = $this->parameterBag->get('city');
            $this->session->set('city',$city);
        }
        return $city;
    }

    function propertycustomFilter($request)
    {
        $filters = [];
        $filters['productCategory']= 'properties';
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
        if ($request->get('propertyTransactionCategoryId')) $filters['propertyTransactionCategoryId'] = $request->get('propertyTransactionCategoryId');
        if ($request->get('mstCity')) $filters['mstCity'] = ucfirst($request->get('mstCity'));
        if ($request->get('projectAvailabilityFromDate')) $filters['projectAvailabilityFromDate']  = $request->get('projectAvailabilityFromDate');
        if ($request->get('mstPincode')) $filters['mstPincode']  = $request->get('mstPincode');
        if ($request->get('isTopVendorPartners')) $filters['isTopVendorPartners']  = $request->get('isTopVendorPartners');
        if ($request->get('trnVendorPartnersId')) $filters['trnVendorPartnersId']  = $request->get('trnVendorPartnersId');
        return $filters;
    }

    function getTrnProjectRoomConfigurationFilters($trnProjectRoomConfigurations){
        $filters =[];
        $noOfBathRooms = [];
        $projectArea = [];
        $projectAreaValue = [];
        foreach($trnProjectRoomConfigurations as $trnProjectRoomConfiguration)
        {
            $ptc = $trnProjectRoomConfiguration->getMstPropertyTransactionCategory()->getId();
            $filters[$ptc]['projectStatuses'][$trnProjectRoomConfiguration->getTrnProject()->getMstProjectStatus()->getId()] = $trnProjectRoomConfiguration->getTrnProject()->getMstProjectStatus()->getPropertyType();
            $filters[$ptc]['projectRoomConfigurations'][$trnProjectRoomConfiguration->getMstRoomConfiguration()->getId()] = $trnProjectRoomConfiguration->getMstRoomConfiguration()->getRoomConfiguration();
            $filters[$ptc]['projectPostedBy'][$trnProjectRoomConfiguration->getTrnProject()->getTrnVendorPartnerDetails()->getMstVendorType()->getId()] = $trnProjectRoomConfiguration->getTrnProject()->getTrnVendorPartnerDetails()->getMstVendorType()->getVendorType();
            foreach($trnProjectRoomConfiguration->getTrnProject()->getTrnProjectAmenities() as $amenities){
                $filters[$ptc]['projectAmenitiesCategories'][$amenities->getMstSubCategory()->getId()] = $amenities->getMstSubCategory()->getSubCategory();
                $filters[$ptc]['projectAmenities'][$amenities->getMstProjectAmenities()->getId()] =[
                    "subCategoryId"=>$amenities->getMstSubCategory()->getId(),
                    "subCategory"=>$amenities->getMstSubCategory()->getSubCategory(),
                    "projectAmenityId"=>$amenities->getMstProjectAmenities()->getId(),
                    'projectAmenity'=> $amenities->getMstProjectAmenities()->getProjectAmenities(),
                    "Id"=>$amenities->getId(),
                    'Amentity'=> $amenities->getTrnAmenitiesDescription(),
                ];
                $filters[$ptc]['projectAmenitiesIcons'][$amenities->getId()] = $amenities->getMstProjectAmenities()->getMediaIcon()->getIconImage();
            }
            if (isset($noOfBathRooms[$ptc])){
                if (!in_array($trnProjectRoomConfiguration->getNoOfBathRooms(),$noOfBathRooms[$ptc])){
                    $noOfBathRooms[$ptc][] = $trnProjectRoomConfiguration->getNoOfBathRooms();
                }
            }else{
                $noOfBathRooms[$ptc][] = $trnProjectRoomConfiguration->getNoOfBathRooms();
            }
            if (isset($projectArea[$ptc])){
                if (!in_array($trnProjectRoomConfiguration->getMstProjectArea()->getProjectArea(),$projectArea[$ptc])){
                    $projectArea[$ptc][] = $trnProjectRoomConfiguration->getMstProjectArea()->getProjectArea();
                }
            }else{
                $projectArea[$ptc][] = $trnProjectRoomConfiguration->getMstProjectArea()->getProjectArea();
            }
            if (isset($projectAreaValue[$ptc])){
                if (!in_array($trnProjectRoomConfiguration->getAreaValue(),$projectAreaValue[$ptc])){
                    $projectAreaValue[$ptc][] = $trnProjectRoomConfiguration->getAreaValue();
                }
            }else{
                $projectAreaValue[$ptc][] = $trnProjectRoomConfiguration->getAreaValue();
            }

            if ($ptc == 1 ){
                $filters[$ptc]['priceGroup'][$trnProjectRoomConfiguration->getMstDenomination()->getDenomination()][] = $trnProjectRoomConfiguration->getAgreementAmount();
            }
            if ($ptc == 2 ){
                $filters[$ptc]['priceGroup'][''][]= $trnProjectRoomConfiguration->getRentPerMonth();
                foreach ($trnProjectRoomConfiguration->getMstPreferredTenant() as $mstPreferredTenant){
                    $filters[$ptc]['projectTenants'][$mstPreferredTenant->getId()]= $mstPreferredTenant->getPreferredTenant();
                }
                $filters[$ptc]['projectProductSubType'][$trnProjectRoomConfiguration->getTrnProject()->getMstProductSubType()->getId()]= $trnProjectRoomConfiguration->getTrnProject()->getMstProductSubType()->getProductSubType();
            }
            $filters[$ptc]['projectMoreFilter']['Bathroom'] = $noOfBathRooms[$ptc];
            $filters[$ptc]['projectMoreFilter']['Property_Area'] = ["Area"=>$projectArea[$ptc],"AreaValue"=>$projectAreaValue[$ptc]];
        }
        return $filters;
    }
}

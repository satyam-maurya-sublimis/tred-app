<?php


namespace App\Service;

use App\Entity\Master\MstProjectType;
use App\Entity\Transaction\TrnProject;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class CommonHelper
{
    private $cacheManager;
    private $em;
    public function __construct(CacheManager $cacheManager, EntityManagerInterface $entityManager)
    {
        $this->cacheManager = $cacheManager;
        $this->em = $entityManager;
    }
    public function slugify(string $string): string
    {
        return preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
    }

    public function tokenTime()
    {
        $tokenTime = date("yhmisd");
        return $tokenTime;
    }

    // Periodic Values
    /**
     * @return string[]
     */
    public function periodic()
    {
        $periodic = array(
            'Monthly' => 'monthly',
            'Quarterly' => 'quarterly',
            'Half-Yearly' => 'half-yearly',
            'Yearly' => 'yearly'
        );
        return $periodic;
    }

    // Number Values

    /**
     * @return string[]
     */
    public function numberType()
    {
        $number = array(
            'Percent' => 'percent',
            'Amount' => 'amount',
        );
        return $number;
    }

    /**
     * @return string[]
     */
    public function mediaType()
    {
        $mediaType = array(
            'Image' => 'image',
            'Video' => 'video',
        );
        return $mediaType;
    }

    /**
     * @return string[]
     */
    public function weightType()
    {
        $weightType = array(
            'KG' => 'kg',
            'GM' => 'gm',
        );
        return $weightType;
    }

    /**
     * @return string[]
     */
    public function testimonialFor()
    {
        $testimonialFor = array(
            'Product' => 'product',
            'Website' => 'website',
            'Feedback' => 'feedback'
        );
        return $testimonialFor;
    }

    /**
     * @return string[]
     */
    public function environmentType()
    {
        $environmentType = array(
            'Test' => 'test',
            'Prod' => 'prod',
        );
        return $environmentType;
    }

    /**
     * @return string[]
     */
    public function reviewStatusType()
    {
        $reviewStatusType = array(
            'Accepted' => 'accepted',
            'Rejected' => 'rejected',
        );
        return $reviewStatusType;
    }

    /**
     * @param $string
     * @return string|string[]|null
     */
    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public function getProjectData($trn_projects){

        $data = [];
        $i=0;
        foreach($trn_projects as $pkey=>$pval){
            $data[$i]["id"] = $pval->getId();
            $data[$i]["projectName"] = $pval->getProjectName();
            $data[$i]["company"] = $pval->getOrgCompany()->getCompany();
            $data[$i]["productCategory"][$pval->getMstProductCategory()->getId()] = $pval->getMstProductCategory()->getProductCategory();
            $data[$i]["productType"][$pval->getMstProductType()->getId()] = $pval->getMstProductType()->getProductType();
            $data[$i]["productSubType"][$pval->getMstProductSubType()->getId()] = $pval->getMstProductSubType()->getProductSubType();
            $data[$i]["vendor"][$pval->getTrnVendorPartnerDetails()->getId()]['vendorPartnerName'] = $pval->getTrnVendorPartnerDetails()->getVendorPartnerName();
            $data[$i]["vendor"][$pval->getTrnVendorPartnerDetails()->getId()]['vendorType'][$pval->getTrnVendorPartnerDetails()->getMstVendorType()->getId()] = $pval->getTrnVendorPartnerDetails()->getMstVendorType()->getVendorType();
            $data[$i]["country"] = $pval->getMstCountry()->getCountry();
            $data[$i]["state"] = $pval->getMstState()->getState();
            $data[$i]["city"] = $pval->getMstCity()->getCity();
            $data[$i]["area"] = $pval->getMstPincode()->getOfficeName();
            $data[$i]["propertyType"][$pval->getMstPropertyType()->getId()] = $pval->getMstPropertyType()->getProjectType();
            $data[$i]["projectStatus"][$pval->getMstProjectStatus()->getId()] = $pval->getMstProjectStatus()->getPropertyType();
            $data[$i]["possessionNote"] = $pval->getPossessionNote();
            $data[$i]["possessionOn"] = [];
            if($pval->getMstPossession()){
                $data[$i]["possessionOn"][$pval->getMstPossession()->getId()]= $pval->getMstPossession()->getPossession();
            }

            foreach($pval->getMstProjectFeature() as $feature){
                $data[$i]["feature"][] = $feature->getProductFeature();
            }

            foreach($pval->getMstAmenities() as $kk=>$amenities){
                $subCategoryId = $amenities->getmstSubCategory()->getId();
                $subCategoryName = $amenities->getmstSubCategory()->getSubCategory();
                $data[$i]["amenities"][$subCategoryId][$kk]["id"] = $amenities->getId();
                $data[$i]["amenities"][$subCategoryId][$kk]["name"] = $amenities->getProjectAmenities();
                $data[$i]["amenities"][$subCategoryId][$kk]["categoryId"] = $subCategoryId;
                $data[$i]["amenities"][$subCategoryId][$kk]["category"] = $subCategoryName;
                $mediaIcon = $amenities->getMediaIcon();
                if ($mediaIcon){
                    $iconFilePathdata = $this->cacheManager->resolve($amenities->getMediaIcon()->getIconImage(), 'resize_image_200');
                    $data[$i]["amenities"][$subCategoryId][$kk]['src'] = $iconFilePathdata;
                }
            }
            foreach($pval->getTrnUploadDocument() as $upload){
                $data[$i]["imagePath"][] = $this->cacheManager->resolve($upload->getMediaFilePath(), 'resize_image_200');
            }
            foreach($pval->getMstRoomConfiguration() as $room){
                $data[$i]["roomConfiguration"][$room->getId()] = $room->getRoomConfiguration();
            }
            $j = 0;
            $dataArray = [];
            $price = [];
//            foreach($pval->getTrnProjectTowerFloorPlans() as $floorPlan){
//                if ($floorPlan->getMstDenomination())  $dataArray[$j] = $floorPlan->getMstDenomination()->getDenominationNumericValue()*$floorPlan->getAgreementAmount();
//                $data[$i]["price"][$j]['floorPlanId'] = $floorPlan->getId();
//                if ($floorPlan->getMstCurrencyAgreementPrice()) $price[$j]['currency'] = $floorPlan->getMstCurrencyAgreementPrice()->getCurrency();
//                if ($floorPlan->getMstRoomConfiguration()) $price[$j]['roomConfiguration'] = $floorPlan->getMstRoomConfiguration()->getRoomConfiguration();
//                if ($floorPlan->getMstDenomination()) $price[$j]['Amount'] = str_replace('.00','',$floorPlan->getAgreementAmount()).' '.$floorPlan->getMstDenomination()->getDenomination();
//                if ($floorPlan->getMstDenomination()) $price[$j]['numericAmount'] = $floorPlan->getMstDenomination()->getDenominationNumericValue()*$floorPlan->getAgreementAmount();
//                $j++;
//            }
            foreach($pval->getTrnProjectRoomConfigurations() as $floorPlan){
                if ($floorPlan->getMstDenomination())  $dataArray[$j] = $floorPlan->getMstDenomination()->getDenominationNumericValue()*$floorPlan->getAgreementAmount();
                $data[$i]["price"][$j]['floorPlanId'] = $floorPlan->getId();
                if ($floorPlan->getMstCurrency()) $price[$j]['currency'] = $floorPlan->getMstCurrency()->getCurrency();
                if ($floorPlan->getMstRoomConfiguration()) $price[$j]['roomConfiguration'] = $floorPlan->getMstRoomConfiguration()->getRoomConfiguration();
                if ($floorPlan->getMstDenomination()) $price[$j]['Amount'] = str_replace('.00','',$floorPlan->getAgreementAmount()).' '.$floorPlan->getMstDenomination()->getDenomination();
                if ($floorPlan->getMstDenomination()) $price[$j]['numericAmount'] = $floorPlan->getMstDenomination()->getDenominationNumericValue()*$floorPlan->getAgreementAmount();
                $j++;
            }

            if (count($dataArray)> 0 ){
                $min  = min($dataArray);
                foreach($price as $vall){
                    if (array_key_exists('numericAmount',$vall)){
                        if ($vall['numericAmount']==$min){
                            $data[$i]["price"] = $vall;
                        }
                    }
                }
            }
            $i++;
        }
        return $data;
    }

    public function getProjectTowerData($trnProjectTowerDetails)
    {
        $data = [];
        $i=0;
        foreach($trnProjectTowerDetails as $pkey=>$pval){
            $data[$i]["id"] = $pval->getId();
            $data[$i]["towerName"] = $pval->getTowerName();
            $data[$i]["noOfFloors"] = $pval->getNoOfFloors();


            foreach($pval->getTrnProjectTowerFloorPlans() as $tkey=>$tval)
            {
                if ($tval->getMstCurrencyAgreementPrice()) $data[$i]["towerFloorPlan"][$tkey]['currency'] = $tval->getMstCurrencyAgreementPrice()->getIso3();
                if ($tval->getMstRoomConfiguration()) $data[$i]["towerFloorPlan"][$tkey]['roomConfiguration'] = $tval->getMstRoomConfiguration()->getRoomConfiguration();
                if ($tval->getMstDenomination()) $data[$i]["towerFloorPlan"][$tkey]['denomination'] = $tval->getMstDenomination()->getDenomination();
                $data[$i]["towerFloorPlan"][$tkey]['amount'] = str_replace('.00','',$tval->getAgreementAmount());
                $data[$i]["towerFloorPlan"][$tkey]["roomConfiguration"] = $tval->getMstRoomConfiguration()->getRoomConfiguration();
                $data[$i]["towerFloorPlan"][$tkey]["noOfBedRoom"] = $tval->getNoOfBedRoom();
                $data[$i]["towerFloorPlan"][$tkey]["noOfBathRooms"] = $tval->getNoOfBathRooms();
                $data[$i]["towerFloorPlan"][$tkey]["superArea"] = $tval->getSuperArea();
                if ($tval->getMstProjectSuperArea()) $data[$i]["towerFloorPlan"][$tkey]["superAreaMeasure"] = $tval->getMstProjectSuperArea()->getProjectArea();
                $data[$i]["towerFloorPlan"][$tkey]["carpetArea"] = $tval->getCarpetArea();
                if ($tval->getMstProjectCarpetArea()) $data[$i]["towerFloorPlan"][$tkey]["carpetAreaMeasure"] = $tval->getMstProjectCarpetArea()->getProjectArea();
            }
            foreach($pval->getMstTowerAmenities() as $akey => $aval ){
                $data[$i]["towerAmenities"][$akey] = $aval->getProjectAmenities();
            }
            $i++;
        }
        return $data;
    }

    public function bannerPosition(): array
    {
        return array(
            'Top' => 'top',
            'Right' => 'right',
            'Left' => 'left',
            'Middle' => 'middle'
        );
    }
    public function adPosition(): array
    {
        return array(
            'Right' => 'right',
            'Middle' => 'middle'
        );
    }

    public function cmsPageContentPosition(): array
    {
        return array(
            'Title' => 'title',
            'Top' => 'top',
            'Bottom' => 'bottom',
            'Middle' => 'middle',
            'Left' => 'left',
            'Right' => 'right',
        );
    }

    public function cmsPageContentMediaPosition(): array
    {
        return array(
            'Left' => 'left',
            'Right' => 'right',
        );
    }

    public function additionalDetailType(): array
    {
        return array(
            'Amenities' => 'amenities',
            'Surroundings' => 'surroundings',
            'Price and Payment Terms' => 'price_and_payment_terms',
            'Terms and Conditions' => 'terms_and_conditions',
            'Contact Details' => 'contact_details',
            'General' => 'general',
        );
    }

    public function scopeOfWork(): array
    {
        return array(
            'Furniture' => 'furniture',
            'False Ceiling' => 'false-ceiling',
            'Electrical' => 'electrical',
            'Painting' => 'painting',
            'Bathroom' => 'bathroom',
            'Windows' => 'windows',
            'Kitchen' => 'kitchen',
            'Bedroom' => 'bedroom',
            'Living Room' => 'living-room',
        );
    }
    function find_project_in_array($data, $projectName) {
        $index = 0;
        foreach($data as $val) {
            if($val['id'] == $projectName) $index = 1;
        }
        return $index;
    }

    public function getGlobalData(){
        $data = [];
        $trnProjects = $this->em->getRepository(TrnProject::class)->findBy(['isActive'=>1]);

        foreach($trnProjects as $trnProject){
            if (!$this->find_project_in_array($data,$trnProject->getProjectName())){
                $data[] = [
                    'id'=>$trnProject->getProjectName(),
                    'href'=>'/properties/'.$trnProject->getId().'/detail/',
                    'text'=> $trnProject->getProjectName()." - ".$trnProject->getMstCity()->getCity(),
                    'search'=>$trnProject->getProjectName().":".$trnProject->getMstCity()->getCity().":".$trnProject->getMstAreaInCity()->getArea().":".$trnProject->getTrnVendorPartnerDetails()->getVendorPartnerName(),
                    'propertyType'=>$trnProject->getMstPropertyType()->getId()
                ];
            }
        }
        return $data;
    }
    public function yearSelection($years)
    {
        $currentYear = date('Y');
        $yearRange = range($currentYear, $currentYear + $years);
        $yearArray = array();
        foreach ($yearRange as $key => $value)
        {
            $yearArray[$value] = $value;
        }
        return $yearArray;

    }

    public function monthSelection()
    {
        $month = [];
        $month['January'] = 'January';
        $month['February'] = 'February';
        $month['March'] = 'March';
        $month['April'] = 'April';
        $month['May'] = 'May';
        $month['June'] = 'June';
        $month['July'] = 'July';
        $month['August'] = 'August';
        $month['September'] = 'September';
        $month['October'] = 'October';
        $month['November'] = 'November';
        $month['December'] = 'December';
        return $month;

    }

    public function possessionDate($dateField,$year,$month){
        $mydate1 = "";
        if ($dateField) {
            $mydate = date(strtotime($dateField));
        } elseif ($year !="" && $month !="") {
            $mydate1 = date('Y-F-d', strtotime($year."-".$month."-01"));
            $mydatetimestamp = date(strtotime($mydate1));
            $mydateMonth = date(strtotime('+1 month',$mydatetimestamp));
            $mydate = date('Y-m-d',strtotime('-1 day',$mydateMonth));
        }else{
            return;
        }
        return $mydate;
   }
}

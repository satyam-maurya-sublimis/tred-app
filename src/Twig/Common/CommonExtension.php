<?php

namespace App\Twig\Common;

use App\Entity\Form\FormEnquiry;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstOfficeCategory;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstPropertyType;
use App\Entity\Master\MstSubCategory;
use App\Entity\Organization\OrgCompanyOffice;
use App\Entity\Transaction\TrnTopVendorPartners;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Service\GeoPlugin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
class CommonExtension extends AbstractExtension
{
    private $em;
    private $params;
    private $geoPlugin;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params , GeoPlugin $geoPlugin)
    {
        $this->em = $em;
        $this->params = $params;
        $this->geoPlugin = $geoPlugin;
    }
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('filter_name', [$this, 'doSomething']),
            new TwigFilter('force_to_float',[$this, 'forceToFloat']),
        ];
    }
    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_country_list', [$this, 'getCountryList']),
            new TwigFunction('get_parameter', [$this, 'getParameter']),
            new TwigFunction('get_office_list', [$this, 'getOfficeList']),
            new TwigFunction('die', [$this, 'killRender']),
            new TwigFunction('get_city_location', [$this, 'getCityLocation']),
            new TwigFunction('get_sub_category', [$this, 'getMstSubCategory']),
            new TwigFunction('get_amenities', [$this, 'getAmenities']),
            new TwigFunction('get_projectstatus', [$this, 'getProjectStatus']),
            new TwigFunction('get_form_enquiry', [$this, 'getFormEnquiry']),
            new TwigFunction('get_productSubType', [$this, 'getProductSubType']),
            new TwigFunction('get_navigation', [$this, 'getNavigationProductType']),
            new TwigFunction('get_product_type_by_id', [$this, 'getProductTypeById']),
            new TwigFunction('get_city_by_id', [$this, 'getCityById']),
            new TwigFunction('get_top_builders', [$this, 'getTopBuilders']),
            new TwigFunction('get_top_real_estates', [$this, 'getTopRealEstates']),
            new TwigFunction('get_availability_from_date', [$this, 'getavailabilityFromDate']),
            new TwigFunction('get_possession_date', [$this, 'getPossessionDate']),
            new TwigFunction('get_possession_format', [$this, 'getPossessionDateFormat']),
            new TwigFunction('get_project_property_count', [$this, 'getProjectPropertyCount']),
        ];
    }
    /**
     * @return MstCountry[]|object[]
     */
    public function getCountryList()
    {
        return $this->em->getRepository(MstCountry::class)->findAll();
    }

    public function getParameter($parameter)
    {
        return $this->params->get($parameter);
    }

    public function getOfficeList()
    {
        $mstOfficeCategory = $this->em->getRepository(MstOfficeCategory::class)->findOneBy(['officeCategory' => 'Registered Office', 'isActive' => 1]);
        return $this->em->getRepository(OrgCompanyOffice::class)->findOneBy(['orgCompany' => 1, 'isActive' => 1,'mstOfficeCategory'=>$mstOfficeCategory]);
    }

    public function killRender($message = null)
    {
        die($message);
        return '';
    }
    public function getCityLocation($ip){
        return $this->geoPlugin->locate($ip);
    }
    public function getMstSubCategory($id){
        return $this->em->getRepository(MstSubCategory::class)->findOneBy([ 'id'=>$id,'isActive' => 1]);
    }
    public function getAmenities($id){
        return $this->em->getRepository(MstProjectAmenities::class)->findOneBy([ 'id'=>$id,'isActive' => 1]);
    }
    public function getProjectStatus($id){
        return $this->em->getRepository(MstPropertyType::class)->findOneBy([ 'id'=>$id,'isActive' => 1]);
    }
    public function getProductSubType($id){
        return $this->em->getRepository(MstProductSubType::class)->findOneBy([ 'id'=>$id,'isActive' => 1]);
    }
    public function getNavigationProductType()
    {
        return $this->em->getRepository(MstProductCategory::class)->getNavigationProductType();
    }

    public function getFormEnquiry(){
        return $this->em->getRepository(FormEnquiry::class)->getFormEnquiry();
    }

    public function getProductTypeById($id)
    {
        return $this->em->getRepository(MstProductType::class)->findOneBy(['isActive'=>1,'id'=>$id]);
    }
    public function getCityById($id)
    {
        return $this->em->getRepository(MstCity::class)->findOneBy(['id'=>$id]);
    }
    public function getTopBuilders()
    {
        return $this->em->getRepository(TrnTopVendorPartners::class)->getTopBuilders();
    }
    public function getTopRealEstates()
    {
        return $this->em->getRepository(TrnTopVendorPartners::class)->getTopRealEstates();
    }
    public function forceToFloat($value){
        return floatval($value);
    }
    public function getavailabilityFromDate($projectAvailabilityFromDate)
    {
        $availabilityFromDate = [];
        foreach($projectAvailabilityFromDate as $value){
            $prjAvailabilityFromDate = \DateTime::createFromFormat('Y-m-d', $value);
            $currentDate = new \DateTime();
            $plus15Date = date( "Y-m-d", strtotime('+15 days'));
            $plus30Date = date( "Y-m-d", strtotime('+30 days'));
            $plus31Date = date( "Y-m-d", strtotime('+31 days'));
            if ($prjAvailabilityFromDate < $currentDate){
                $availabilityFromDate["L|".$currentDate->format('Y-m-d')] =  "Immediate";
            }else{
                if ($prjAvailabilityFromDate > $currentDate && $projectAvailabilityFromDate < $plus15Date ){
                    $availabilityFromDate["R|".$currentDate->format('Y-m-d')."|".$plus15Date->format('Y-m-d')] =  "Within 15Days";
                }
                if ($prjAvailabilityFromDate > $plus15Date && $projectAvailabilityFromDate < $plus30Date ){
                    $availabilityFromDate["R|".$plus15Date->format('Y-m-d')."|".$plus30Date->format('Y-m-d')] =  "Within 30Days";
                }
                if ($prjAvailabilityFromDate > $plus31Date ){
                    $availabilityFromDate["G|".$plus31Date->format('Y-m-d')] =  "After 30Days";
                }
            }
        }
        return $availabilityFromDate;
    }

    public function getPossessionDate(){
        $validPossessionDate = [];
        $today = date('Y-m-d', strtotime("today"));
        $months3 = date('Y-m-d', strtotime("today +3 months"));
        $months6 = date('Y-m-d', strtotime("today +6 months"));
        $year1 = date('Y-m-d', strtotime("today +1 year"));
        $year2 = date('Y-m-d', strtotime("today +2 year"));
        $year3 = date('Y-m-d', strtotime("today +3 year"));
        $validPossessionDate["3m"]= $today."|".$months3;
        $validPossessionDate["6m"]= $today."|".$months6;
        $validPossessionDate["1y"]= $today."|".$year1;
        $validPossessionDate["2y"]= $today."|".$year2;
        $validPossessionDate["3y"]= $today."|".$year3;
        $validPossessionDate["3y+"]= $year3."|";
        return $validPossessionDate;
    }
    public function getPossessionDateFormat($value){
        $format = "";
        if ($value == "3m" ){
            $format= "3 Months";
        }elseif($value == "6m"){
            $format= "6 Months";
        }elseif($value == "1y"){
            $format= "1 Year";
        }elseif($value == "2y"){
            $format= "2 Year";
        }elseif($value == "3y"){
            $format= "3 Year";
        }elseif($value == "3y+"){
            $format= "3+ Year";
        }
        return $format;
    }
    public function getProjectPropertyCount($projects){
        $cnt = 0;
        foreach($projects as $project){
            $cnt += count($project->getTrnProjectRoomConfigurations());
        }
        return $cnt;
    }
}
